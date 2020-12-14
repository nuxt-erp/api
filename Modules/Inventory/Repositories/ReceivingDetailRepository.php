<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\Receiving;
use Modules\Inventory\Entities\ReceivingDetail;

class ReceivingDetailRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $data['item_status'] = ReceivingDetail::NEW_RECEIVING;

            // SAVE RECEIVING DETAIL
            parent::store($data);

            //update availability if create by admin
            if(!empty($data['admin_create']) && $this->model) {
                $availability_repo = new AvailabilityRepository(new Availability());
                $availability_repo->updateStock($this->model->product_id, $this->model->qty_received, $this->model->receiving->location_id, null, '+', 'Receiving', $this->model->receiving_id, null, null,'Received product - changing quantity');
            }
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){           
            $original_qty_received = $model->qty_received ?? 0;

            // HANDLE ITEM STATUS
            if(!empty($data['qty_allocated']) && $data['qty_allocated']) {
                if(!empty($data['qty_received']) && $data['qty_received']) {
                    if($data['qty_received'] === $data['qty_allocated']) {
                        $data['item_status'] = ReceivingDetail::ALLOCATED;
                    } else {
                        $data['item_status'] = ReceivingDetail::PARTIALLY_ALLOCATED;
                    }
                } else {
                    if($model->qty_received === $data['qty_allocated']) {
                        $data['item_status'] = ReceivingDetail::ALLOCATED;
                    } else {
                        $data['item_status'] = ReceivingDetail::PARTIALLY_ALLOCATED;
                    }
                }
            } else {
                $data['item_status'] = ReceivingDetail::NEW_RECEIVING;
            }

            parent::update($model, $data);

            if($this->model) {
                // CHECK IF ALL PRODUCTS WHERE ALLOCATED AND HANDLE RECEIVING STATUS
                $receiving = Receiving::find($this->model->receiving_id);
                $receiving_details = $receiving->details;

                $finished = true;
                foreach ($receiving_details as $detail) {
                    if($detail->item_status <> ReceivingDetail::ALLOCATED) $finished = false;
                }

                if ($finished) {
                    $receiving->allocation_status = Receiving::RECEIVED;
                } else {
                    if($this->model->qty_allocated > 0) $receiving->allocation_status = Receiving::PARTIALLY_ALLOCATED;
                }
                $receiving->save();

                // update availability if quantity received change
                if(!empty($data['admin_update']) && $receiving->location_id && ($this->model->qty_received <> $original_qty_received)){
                    $availability_repo = new AvailabilityRepository(new Availability());
                    $variation = $this->model->qty_received - $original_qty_received;
                    $operator = $variation >= 0 ? '+' : '-';
                    $availability_repo->updateStock($this->model->product_id, abs($variation), $receiving->location_id, null, $operator,  'Receiving', $receiving->id, null, null,'Received product - changing quantity');

                }
            }
            
        });
    }
}
