<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Arr;
use Auth;
use Modules\Purchase\Entities\PurchaseDetail;
use Modules\Purchase\Entities\Purchase;
use App\Models\Supplier;
use Modules\Inventory\Entities\Product;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
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
            $this->queryBuilder
            ->where('id', Arr::pull($searchCriteria, 'id'));
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            parent::store($data);

            // Update supplier date last purchase
            Supplier::where('id', $data["supplier_id"])->update(['last_order_at' => date('Y-m-d')]);

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

        if (isset($data["list_products"]))
        {
            $object     = $data["list_products"];
            $row        = 0;
            $tot        = 0;
            $total      = 0;
            $total_item = 0;

            // DELETE ITEMS TO INSERT THEM AGAIN
            PurchaseDetail::where('purchase_id', $id)->delete();

            foreach ($object as $attributes) // EACH ATTRIBUTE
            {
                $row++;
                $tot = count($attributes); // TOTAL ATTRIBUTES

                if ($row==1) // ONE ROW CONTAINS ALL ATTRIBUTES
                {
                    for ($i=0; $i < $tot; $i++) // WHILE FOUND ATTRIBUTES
                    {
                        if (isset($attributes[$i]))
                        {
                            if ($attributes[$i]!=0)
                            {
                                $qty            = 0;
                                $received_date  = null;
                                $estimated_date = null;
                                $qty_received   = 0;
                                $price          = "";
                                $ref            = "";
                                $get_array      = $attributes[$i];

                                if (array_key_exists('qty', $get_array)) {
                                    $qty = isset($get_array["qty"]) ? $get_array["qty"] : 0;
                                } else {
                                    $qty = 0;
                                }

                                if (array_key_exists('ref', $get_array)) {
                                    $ref = isset($get_array["ref"]) ? $get_array["ref"] : '';
                                } else {
                                    $ref = '';
                                }

                                if (array_key_exists('qty_received', $get_array)) {
                                    $qty_received = isset($get_array["qty_received"]) ? $get_array["qty_received"] : 0;
                                } else {
                                    $qty_received = 0;
                                }

                                if (array_key_exists('received_date', $get_array)) {
                                    $received_date = isset($get_array["received_date"]) ? date('Y-m-d', strtotime($get_array["received_date"])) : null;
                                } else {
                                    $received_date = null;
                                }

                                if (array_key_exists('estimated_date', $get_array)) {
                                    $estimated_date = isset($get_array["estimated_date"]) ? date('Y-m-d', strtotime($get_array["estimated_date"])) : null;
                                } else {
                                    $estimated_date = null;
                                }

                                if (array_key_exists('price', $get_array)) {
                                    $price = isset($get_array["price"]) ? $get_array["price"] : "";
                                } else {
                                    $price = 0;
                                }

                                if (array_key_exists('product_id', $get_array)) {
                                    $product_id = $get_array["product_id"];
                                } else {
                                    $product_id = $get_array["name"];
                                }

                                if ($product_id) {
                                    $total_item = ($price * $qty);

                                    PurchaseDetail::updateOrCreate([
                                        'purchase_id'    => $id,
                                        'product_id'     => $product_id],
                                    [
                                        'qty'            => $qty,
                                        'ref'            => $ref,
                                        'qty_received'   => $qty_received,
                                        'received_date'  => $received_date,
                                        'estimated_date' => $estimated_date,
                                        'price'          => $price,
                                        'gross_total'    => $total_item,
                                        'total'          => $total_item,
                                        'item_status'    => ($qty == $qty_received), // Fulfillment status for item
                                    ]);

                                    $total += $total_item;

                                    if ($qty == $qty_received) { // Update on hand qty when fulfilled (qty = qty received)

                                        if ($data["status"] == 0) { // Do not update when the stock is already received
                                            // Increase stock quantity
                                            //$this->updateStock(Auth::user()->company_id, $product_id, $qty, $data["location_id"], "+", "Purchase", $id, 0, 0, "Add item");

                                            // Decrease on order quantity
                                            //$this->updateStock(Auth::user()->company_id, $product_id, 0, $data["location_id"], "-", "Purchase", $id, $qty, 0, "Add item");
                                        }

                                    } else { // Not fulfilled, update on order quantity

                                        //$this->updateStock(Auth::user()->company_id, $product_id, 0, $data["location_id"], "+", "Purchase", $id, $qty, 0, "Add item");
                                    }

                                }
                            }
                        }
                    }

                }
            }
            // Total purchase
            Purchase::where('id', $id)->update(['total' => $total]);
        }
    }

    public function remove($id)
    {
        DB::transaction(function () use ($id)
        {
            $getItem = Purchase::where('id', $id)->with('details')->get();

            // Purchase status (Completed or not)
            $status = $getItem[0]->status;

            foreach ($getItem[0]->details as $value)
            {
                if ($status == 1) { // Finished. Update stock available
                    // Decrement on hand qty
                    //$this->updateStock(Auth::user()->company_id, $value->product_id, $value->qty_received, $getItem[0]->location_id, "-", "Purchase", $id, 0, 0, "Remove item");
                } else { // Not finished, just update on order quantity
                    // Decrement on order qty
                    //$this->updateStock(Auth::user()->company_id, $value->product_id, 0, $getItem[0]->location_id, "-", "Purchase", $id, $value->qty, 0, "Remove item");
                }
            }

            // parent::delete($id);
            Purchase::where('id', $id)->delete();

        });
    }
}
