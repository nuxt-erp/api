<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\Receiving;
use Modules\Inventory\Entities\ReceivingDetail;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseDetail;
use Nwidart\Modules\Exceptions\ModuleNotFoundException;
use Response;

class ReceivingRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['is_allocated'])) {
            $is_allocated = '%' . Arr::pull($searchCriteria, 'is_allocated') . '%';
            $this->queryBuilder->where('allocation_status', 'ILIKE', Receiving::ALLOCATED);
        } else {
            $this->queryBuilder->where('allocation_status', '<>', Receiving::ALLOCATED);
        }

        if (!empty($searchCriteria['not_received'])) {
            $not_received = '%' . Arr::pull($searchCriteria, 'not_received') . '%';
            $this->queryBuilder->where('status', 'ILIKE', Receiving::NEW_RECEIVING)->orWhere('status', 'ILIKE', Receiving::PARTIALLY_RECEIVED);
        }

        if (!empty($searchCriteria['name']) && $searchCriteria['name']) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder->where('name', 'ILIKE', $name);
        }

        return parent::findBy($searchCriteria);
    }
    public function store($data)
    {
        DB::transaction(function () use ($data) {

            $data['author_id'] = auth()->id();
            $data['status'] = Receiving::NEW_RECEIVING;
            $data['allocation_status'] = Receiving::NEW_RECEIVING;

            // SAVE STOCK TAKE
            parent::store($data);

            if (!empty($data['list_products'])) {
                $this->model->details()->sync($data['list_products']);
            }
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model) {
            $data['status'] = Receiving::PARTIALLY_RECEIVED;
            parent::update($model, $data);
            if (!empty($data['list_products'])) $model->details()->sync($data['list_products']);
        });
    }

    // ADJUST & FINISH STOCK COUNT
    public function finish($receiving_id)
    {
        $receiving = DB::transaction(function () use ($receiving_id) {
            // GET ALL SAVED QTY FROM COUNTING
            $receiving = Receiving::find($receiving_id);
            $po_number = $receiving->po_number;
            $receiving_details = ReceivingDetail::where('receiving_id', $receiving_id)->get()->toArray();
            $po_allocation['id'] = $receiving_id;
            $po_allocation['selected_items'] = [];
            foreach ($receiving_details as $item) {
                // update availability
                if ($receiving->location_id) {
                    $availability_repo = new AvailabilityRepository(new Availability());
                    $availability_repo->updateStock($item['product_id'], $item['qty_received'], $receiving['location_id'], null, '+', 'Receiving', $receiving_id, null, null, 'Received product - changing quantity');

                    $item['qty'] = $item['qty_received'];
                    $item['qty_allocated'] = $item['qty_received'];
                    array_push($po_allocation['selected_items'], $item);
                }
            }

            if (!empty($po_number)) {
                $purchase_id = Purchase::where('po_number', $po_number)->first();
                $po_allocation['purchase_id'] = $purchase_id->id;
                $this->poAllocation($po_allocation);
            }

            // SAVE STATUS AS FINISHED
            $receiving->status = Receiving::RECEIVED;
            $receiving->save();
            return $receiving;
        });

        return $receiving;
    }

    public function poAllocation($data)
    {
        $receiving = DB::transaction(function () use ($data) {
            $selected_items = (!empty($data['selected_items']) && count($data['selected_items']) > 0) ? $data['selected_items'] : [];
            $purchase_id = (!empty($data['purchase_id']) && $data['purchase_id']) ? $data['purchase_id'] : null;

            foreach ($selected_items as $row) {
                $purchase_item = PurchaseDetail::where('purchase_id', $purchase_id)->where('product_id', $row['product_id'])->first();
                if ($purchase_item) {
                    if ($purchase_item->qty < $row['qty']) $purchase_item->qty += $row['qty'];
                    $purchase_item->qty_received += $row['qty'];
                    $purchase_item->received_date = Carbon::now()->format('Y-m-d');
                    $purchase_item->save();
                } else {
                    PurchaseDetail::create([
                        'purchase_id'   => $purchase_id,
                        'product_id'    => $row['product_id'],
                        'qty_received'  => $row['qty'],
                        'qty'           => $row['qty'],
                        'received_date' => Carbon::now()->format('Y-m-d')
                    ]);
                }

                $receiving_item =   ReceivingDetail::where('receiving_id', $data['id'])->where('product_id', $row['product_id'])->first();
                if ($receiving_item) {
                    $receiving_item->qty_allocated = $row['qty_allocated'];
                    $receiving_detail_repo = new ReceivingDetailRepository(new ReceivingDetail());
                    $receiving_item->item_status = $receiving_detail_repo->updateItemStatus($receiving_item, $row);
                    if ($receiving_item->qty_allocated == $receiving_item->qty_received) $receiving_item->item_status = ReceivingDetail::ALLOCATED;
                    $receiving_item->save();
                }
            }

            $receiving = Receiving::find($data['id']);
            $receiving->allocation_status = $this->updateAllocationStatus($receiving, $this->model);
            $receiving->save();

            return $receiving;
        });

        return $receiving;
    }

    public function updateAllocationStatus($receiving, $model)
    {
        // CHECK IF ALL PRODUCTS WHERE ALLOCATED AND HANDLE RECEIVING STATUS
        $receiving_details = $receiving->details;

        $finished = true;
        $partially_finished = false;
        foreach ($receiving_details as $detail) {
            if ($detail->item_status <> ReceivingDetail::ALLOCATED) $finished = false;
            if ($detail->qty_allocated > 0) $partially_finished = true;
        }

        if ($finished) {
            $receiving->allocation_status = Receiving::ALLOCATED;
        } else {
            if ($partially_finished) $receiving->allocation_status = Receiving::PARTIALLY_ALLOCATED;
        }

        return $receiving->allocation_status;
    }
}
