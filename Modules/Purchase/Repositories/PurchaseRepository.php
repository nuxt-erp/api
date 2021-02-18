<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;

use App\Models\Supplier;
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
       
        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder->where('id', Arr::pull($searchCriteria, 'id'));
        }

        if (!empty($searchCriteria['supplier_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'supplier_name') . '%';

            $this->queryBuilder->whereHas('supplier', function ($query) use($name) {
                $query->where('name', 'ILIKE', $name);
            });

            $this->queryBuilder
                ->orWhere('tracking_number', 'ILIKE', $name)
                ->orWhere('invoice_number', 'ILIKE', $name);

        }

        if (!empty($searchCriteria['exclude_received'])) {
            $this->queryBuilder->where('status', 0);
        }
        
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            if(!empty($data['name']) && $data['name']) $data['po_number']  = $data['name'];

            parent::store($data);

            if(!empty($data["supplier_id"])){
                // Update supplier date last purchase
                Supplier::where('id', $data["supplier_id"])->update(['last_order_at' => date('Y-m-d')]);
            }            

            // Save purchase details
            
            $this->savePurchaseDetails($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->savePurchaseDetails($data, $this->model->id);
        });
    }

    private function savePurchaseDetails($data, $id)
    {
        //@todo update product cost?
        DB::transaction(function () use ($data, $id)
        {
            if (!empty($data["list_products"]))
            {
                $total      = 0;
                $total_item = 0;

                // DELETE ITEMS TO INSERT THEM AGAIN
                PurchaseDetail::where('purchase_id', $id)->delete();

                $availability_repository = new AvailabilityRepository(new Availability());

                foreach ($data['list_products'] as $item) // EACH ATTRIBUTE
                {

                    $qty            = $item['qty'] ?? 0;
                    $received_date  = !empty($item['received_date']) ? date('Y-m-d', strtotime($item['received_date'])) : null;
                    $estimated_date = !empty($item['estimated_date']) ? date('Y-m-d', strtotime($item['estimated_date'])) : null;
                    $qty_received   = $item['qty_received'] ?? 0;
                    $price          = $item['price'] ?? 0;
                    $ref            = $item['ref'] ?? '';
                    $product_id     = $item['product_id'] ?? null;
                    $tax_rule_id    = $item['tax_rule_id'] ?? null;
                    $bin_id         = $item['bin_id'] ?? null;
                    $location_id    = $item['location_id'] ?? null;

                    $new_status     = $qty == $qty_received;
                   
                    if ($product_id) {

                        $total_item = ($price * $qty);

                        PurchaseDetail::updateOrCreate(
                            [
                                'purchase_id'    => $id,
                                'product_id'     => $product_id
                            ],
                            [
                                'qty'            => $qty,
                                'ref'            => $ref,
                                'qty_received'   => $qty_received,
                                'received_date'  => $received_date,
                                'estimated_date' => $estimated_date,
                                'price'          => $price,
                                'gross_total'    => $total_item,
                                'total'          => $total_item,
                                
                                'item_status'    => $new_status, // Fulfillment status for item
                                'tax_rule_id'    => $tax_rule_id,
                                'bin_id'         => $bin_id,
                                'location_id'    => $location_id
                            ]
                        );

                        $total += $total_item;
                        lad($data['status']);
                        // We need to check update stock
                        if ($data['status']) { // Do not update when the stock is already received
                            // Increase stock quantity
                            $availability_repository->updateStock($product_id, $qty_received, $data['location_id'], $bin_id, '+', 'Purchase', $id, 0, 0, 'Received');

                            // Decrease on order quantity
                            $availability_repository->updateStock($product_id, $qty_received, $data['location_id'], $bin_id, '-', 'Purchase', $id, $qty, 0, 'Ordered');
                        }
                        else{ // Not fulfilled, update on order quantity
                            $availability_repository->updateStock($product_id, $qty, $data['location_id'], $bin_id, '+', 'Purchase', $id, $qty, 0, 'Ordered');
                        }
                    }
                }
                // Total purchase
               // Purchase::where('id', $id)->update(['total' => $total]);
            }
        });
    }

    public function destroy($id)
    {
        //@todo must remove product history
        DB::transaction(function () use ($id)
        {
            $availability_repository = new AvailabilityRepository(new Availability());
            $purchase = Purchase::where('id', $id)->with('details')->first();

            foreach ($purchase->details as $value)
            {
                if ($purchase->status == 1) { // Finished. Update stock available
                    // Decrement on hand qty
                    $availability_repository->updateStock($value->product_id, $value->qty_received, $purchase->location_id, null, "-", "Purchase", $id, 0, 0, "Remove item");
                } else { // Not finished, just update on order quantity
                    // Decrement on order qty
                    $availability_repository->updateStock($value->product_id, 0, $purchase->location_id, null, "-", "Purchase", $id, $value->qty, 0, "Remove item");
                }
            }
            Purchase::where('id', $id)->delete();
        });
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
