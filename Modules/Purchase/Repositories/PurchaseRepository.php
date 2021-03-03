<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;

use App\Models\Supplier;
use Carbon\Carbon;
use Modules\Inventory\Entities\Availability;
use Modules\Purchase\Entities\PurchaseDetail;
use Modules\Purchase\Entities\Purchase;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Repositories\AvailabilityRepository;

//use App\Traits\StockTrait;

class PurchaseRepository extends RepositoryService
{

    //use StockTrait;

    //protected $availabilityRepository;

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];
        lad($searchCriteria);

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder->where('id', Arr::pull($searchCriteria, 'id'));
        }
        if (!empty($searchCriteria['tracking_number'])) {
            $this->queryBuilder
                ->where('tracking_number', 'ILIKE', $searchCriteria['tracking_number']);
        }
        if (!empty($searchCriteria['supplier_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'supplier_name') . '%';

            $this->queryBuilder->whereHas('supplier', function ($query) use ($name) {
                $query->where('name', 'ILIKE', $name);
            });

            $this->queryBuilder
                ->orWhere('tracking_number', 'ILIKE', $name)
                ->orWhere('invoice_number', 'ILIKE', $name);
        }
        // 0 comes through so don't check is empty or else it returns false
        if (isset($searchCriteria['status_id'])) {
            $statuses = $this->getPurchaseStatuses();
            $id = Arr::pull($searchCriteria, 'status_id');
            lad($statuses);
            lad($id);

            foreach ($statuses as $status) {
                if ($status['id'] == $id) {
                    lad($id);
                    $this->queryBuilder->where('status', 'ILIKE', $status['name']);
                    break;
                }
            }
        }


        if (!empty($searchCriteria['exclude_received'])) {
            $this->queryBuilder->where('status', '<>', Purchase::RECEIVED);
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data) {
            if (!empty($data['name']) && $data['name']) $data['po_number']  = $data['name'];

            if (empty($data['tracking_number']) && !empty($data['status']) && $data['status'] !== Purchase::AWAITING_PAYMENT) $data['status'] = Purchase::DRAFT_ORDER;

            parent::store($data);

            if (!empty($data["supplier_id"])) {
                // Update supplier date last purchase
                Supplier::where('id', $data["supplier_id"])->update(['last_order_at' => date('Y-m-d')]);
            }

            // Save purchase details

            $status = $this->savePurchaseDetails($data, $this->model->id);
            if (!empty($status)) {
                $this->model->status = $status;
                if ($status === Purchase::RECEIVED) {
                    $this->model->purchase_date = now();
                }
                $this->model->save();
            }
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model) {
            if (empty($data['tracking_number']) && !empty($data['status']) && $data['status'] !== Purchase::AWAITING_PAYMENT) $data['status'] = Purchase::DRAFT_ORDER;

            parent::update($model, $data);

            $status = $this->savePurchaseDetails($data, $this->model->id);
            if (!empty($status)) {
                $this->model->status = $status;
                if ($status === Purchase::RECEIVED) {
                    $this->model->purchase_date = now();
                }
                $this->model->save();
            }
            // UPDATE STOCK TAKE PRODUCTS
        });
    }

    private function savePurchaseDetails($data, $id)
    {
        $status = null;
        //@todo update product cost?
        DB::transaction(function () use ($data, $id, &$status) {

            if (!empty($data["list_products"])) {
                $total      = 0;
                $total_item = 0;

                // DELETE ITEMS TO INSERT THEM AGAIN
                PurchaseDetail::where('purchase_id', $id)->delete();

                $availability_repository = new AvailabilityRepository(new Availability());
                $all_received   = true;
                $none_received  = true;
                foreach ($data['list_products'] as $item)  // EACH ATTRIBUTE
                {

                    $qty            = $item['qty'] ?? 0;
                    $received_date  = !empty($item['received_date']) ? date('Y-m-d', strtotime($item['received_date'])) : null;
                    $estimated_date = !empty($item['estimated_date']) ? date('Y-m-d', strtotime($item['estimated_date'])) : null;
                    $qty_received   = $item['qty_received'] ?? 0;
                    $qty_allocated  = $item['qty_allocated'] ?? 0;
                    $discounts      = $item['discounts'] ?? 0;
                    $price          = $item['price'] ?? 0;
                    $ref            = $item['ref'] ?? '';
                    $product_id     = $item['product_id'] ?? null;
                    $tax_rule_id    = $item['tax_rule_id'] ?? null;
                    $bin_id         = $item['bin_id'] ?? null;
                    $location_id    = $item['location_id'] ?? null;

                    $new_status     = $qty == $qty_received;
                    lad($qty_allocated);
                    if ($product_id) {

                        $total_item = ($price * $qty);

                        $detail = PurchaseDetail::updateOrCreate(
                            [
                                'purchase_id'        => $id,
                                'product_id'         => $product_id
                            ],
                            [
                                'qty'                => $qty,
                                'ref'                => $ref,
                                'qty_received'       => $qty_received,
                                'qty_allocated'      => $qty_received,
                                'received_date'      => $received_date,
                                'estimated_date'     => $estimated_date,
                                'discounts'          => $discounts,
                                'price'              => $price,
                                'gross_total'        => $total_item,
                                'total'              => $total_item,
                                'item_status'        => $new_status, // Fulfillment status for item
                                'tax_rule_id'        => $tax_rule_id,
                                'bin_id'             => $bin_id,
                                'location_id'        => $location_id
                            ]
                        );

                        $total += $total_item;


                        if ($qty_received < $qty && $qty_received > 0) {
                            lad('$qty < $qty_received && $qty_received > 0');
                            lad('$qty: ' . $qty . ' $qty_received: ' . $qty_received);

                            // updateStock($product_id, $qty, $location_id, $bin_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = '')
                            $all_received = false;
                            $none_received = false;

                            // Increase stock quantity
                            if ($qty_received !== $qty_allocated) {
                                $availability_repository->updateStock($product_id, $qty_received - $qty_allocated, $data['location_id'], $bin_id, '+', 'Purchase', $id, 0, 0, 'Received');

                                // Decrease on order quantity
                                $availability_repository->updateStock($product_id, 0, $data['location_id'], $bin_id, '-', 'Purchase', $id, $qty_received - $qty_allocated, $qty_received - $qty_allocated, 'Ordered');
                            }
                        } else if ($qty_received < $qty  && $qty_received === 0) {
                            lad('$qty < $qty_received  && $qty_received === 0');
                            lad('$qty: ' . $qty . ' $qty_received: ' . $qty_received);

                            $all_received = false;
                            if ($detail->allocation_created === 0) {
                                $availability_repository->updateStock($product_id, 0, $data['location_id'], $bin_id, '+', 'Purchase', $id, $qty, $qty, 'Ordered');
                                $detail->allocation_created = 1;
                                $detail->save();
                            }
                        } else if ($qty_received >= $qty) {
                            lad('$qty >= $qty_received');
                            lad('$qty: ' . $qty . ' $qty_received: ' . $qty_received);
                            if ($qty_received !== $qty_allocated) {

                                // Increase stock quantity
                                $availability_repository->updateStock($product_id, $qty_received - $qty_allocated, $data['location_id'], $bin_id, '+', 'Purchase', $id, 0, 0, 'Received');

                                // Decrease on order quantity
                                $availability_repository->updateStock($product_id, 0, $data['location_id'], $bin_id, '-', 'Purchase', $id, $qty_received - $qty_allocated, $qty_received - $qty_allocated, 'Ordered');
                            }
                        }
                    }
                }
                if ($none_received && !$all_received && !empty($data['tracking_number'])) {
                    $status     = Purchase::AWAITING_DELIVERY;
                } else if ($all_received) {
                    $status     = Purchase::RECEIVED;
                } else if (!$none_received && !$all_received) {
                    $status     = Purchase::PARTIALLY_RECEIVED;
                }
            }
            // Total purchase
            // Purchase::where('id', $id)->update(['total' => $total]);

        });
        return $status;
    }

    public function clone($id)
    {
        $new_model = null;
        DB::transaction(function () use ($id, &$new_model) {
            $purchase = Purchase::findOrFail($id);
            $new_model = $purchase->replicate();
            $iter = $purchase->iteration;
            lad($iter);
            $iter = chr(ord($purchase->iteration) + 1);
            lad($iter);

            $new_model->iteration = $iter;
            $number = preg_replace("/[^0-9]/", "", $new_model->po_number);
            $new_model->po_number = 'PO-'. $number . $iter;
            $new_model->status = Purchase::AWAITING_DELIVERY;
            $new_model->save();
          
            $this->clonePurchaseDetails($purchase, $new_model, $id);
            // if (empty($data['tracking_number']) && !empty($data['status']) && $data['status'] !== Purchase::AWAITING_PAYMENT) $data['status'] = Purchase::DRAFT_ORDER;

            // parent::update($model, $data);

            // $status = $this->savePurchaseDetails($data, $this->model->id);
            // if (!empty($status)) {
            //     $this->model->status = $status;
            //     if ($status === Purchase::RECEIVED) {
            //         $this->model->purchase_date = now();
            //     }
            //     $this->model->save();
            // }
            // // UPDATE STOCK TAKE PRODUCTS
        });
        return $new_model;
    }

    private function clonePurchaseDetails($old, $new, $id)
    {
        DB::transaction(function () use ($old, $new, $id) {
            $details = PurchaseDetail::where('purchase_id', '=', $id)->get();
            lad($details);
            foreach ($details as $detail) {

                if ($detail->qty_received < $detail->qty) {
                    PurchaseDetail::updateOrCreate(
                        [
                            'purchase_id'        => $new->id,
                            'product_id'         => $detail->product_id
                        ],
                        [
                            'qty'                => $detail->qty - $detail->qty_received,
                            'ref'                => $detail->ref,
                            'qty_received'       => 0,
                            'qty_allocated'      => 0,
                            'received_date'      => null,
                            'estimated_date'     => null,
                            'discounts'          => $detail->discounts,
                            'price'              => $detail->price,
                            'gross_total'        => 0,
                            'total'              => 0,
                            'item_status'        => 0, 
                            'tax_rule_id'        => $detail->tax_rule_id,
                            'bin_id'             => $detail->bin_id,
                            'location_id'        => $detail->location_id
                        ]
                    );
                } 

            }
        });

    }

    public function delete($model)
    {
        //@todo must remove product history
        DB::transaction(function () use ($model) {
            $availability_repository = new AvailabilityRepository(new Availability());
            $purchase = Purchase::where('id', $model->id)->with('details')->first();

            foreach ($purchase->details as $value) {
                if ($purchase->status == Purchase::RECEIVED) { // Finished. Update stock available
                    // Decrement on hand qty
                    $availability_repository->updateStock($value->product_id, $value->qty_received, $purchase->location_id, null, "-", "Purchase", $model->id, $value->qty_received, 0, "Remove item");
                } else if ($purchase->status == Purchase::PARTIALLY_RECEIVED) {
                    $availability_repository->updateStock($value->product_id, $value->qty_received, $purchase->location_id, null, "-", "Purchase", $model->id, $value->qty_received, 0, "Remove item");
                    $availability_repository->updateStock($value->product_id, $value->qty - $value->qty_received, $purchase->location_id, null, "-", "Purchase", $model->id, $value->qty - $value->qty_received, $value->qty - $value->qty_received, "Remove item");
                    // $availability_repository->updateStock($value->product_id, 0,  $value->qty, null, "-", "Purchase", $model->id, 0, 0, "Ordered");

                } else {
                    $availability_repository->updateStock($value->product_id, 0, $purchase->location_id, null, "-", "Purchase", $model->id, $value->qty, $value->qty, "Remove item");
                }
            }
        });
        return parent::delete($model);
    }

    public function getPurchaseStatuses()
    {
        $statuses = $this->model->getStatuses();
        $keyValue = [];
        $i = 0;
        foreach ($statuses as $key => $nested) {
            foreach ($nested as $value => $id) {
                $keyValue[$i]['id'] = $id;
                $keyValue[$i]['name'] = ucfirst($value);
                $keyValue[$i]['value'] = ucfirst($value);
                $i++;
            }
        }
        return $keyValue;
    }

    public function getNextPONumber()
    {
        $last_id = Purchase::latest('id')->pluck('id')->first();
        return $last_id > 0 ? ($last_id + 1) : 1;
    }

    public function checkPoNumber($po_number)
    {
        $purchase = Purchase::where('po_number', $po_number)->first();
        return $purchase;
    }
}
