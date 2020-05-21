<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\TransferDetails;
use App\Models\Transfer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Traits\StockTrait;
use Illuminate\Support\Collection;

class TransferRepository extends RepositoryService
{

    use StockTrait;

    protected $availabilityRepository;

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

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $data["company_id"] = Auth::user()->company_id;
            parent::store($data);
            // SAVE Transfer DETAILS
            $this->saveTransferDetails($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->saveTransferDetails($data, $this->model->id);
        });
    }

    private function saveTransferDetails($data, $id)
    {

        if (isset($data["list_products"]))
        {
            $object = $data["list_products"];
            $row    = 0;
            $tot    = 0;

            // DELETE ITEMS TO INSERT THEM AGAIN
            TransferDetails::where('transfer_id', $id)->delete();

            foreach ($object as $attributes) // EACH PRODUCT
            {
                $row++;
                $tot = count($attributes); // TOTAL ROWS

                if ($row==1) // ONE ROW CONTAINS ALL PRODUCTS
                {
                    for ($i=0; $i < $tot; $i++) // WHILE FOUND PRODUCTS
                    {
                        if (isset($attributes[$i]))
                        {
                            if ($attributes[$i]!=0)
                            {
                                $qty            = 0;
                                $qty_sent       = 0;
                                $qty_received   = 0;
                                $get_array      = $attributes[$i];

                                if (array_key_exists('qty', $get_array)) {
                                    $qty = isset($get_array["qty"]) ? $get_array["qty"] : 0;
                                } else {
                                    $qty = 0;
                                }

                                if (array_key_exists('qty_sent', $get_array)) {
                                    $qty_sent = isset($get_array["qty_sent"]) ? $get_array["qty_sent"] : 0;
                                } else {
                                    $qty_sent = 0;
                                }

                                if (array_key_exists('qty_received', $get_array)) {
                                    $qty_received = isset($get_array["qty_received"]) ? $get_array["qty_received"] : 0;
                                } else {
                                    $qty_received = 0;
                                }

                                if (array_key_exists('product_id', $get_array)) {
                                    $product_id = $get_array["product_id"];
                                } else {
                                    $product_id = $get_array["name"];
                                }

                                if ($product_id) {

                                    TransferDetails::updateOrCreate([
                                        'transfer_id'    => $id,
                                        'product_id'     => $product_id],
                                    [
                                        'qty'            => $qty,
                                        'qty_sent'       => $qty_sent,
                                        'qty_received'   => $qty_received,
                                        'variance'       => ($qty_sent - $qty_received),
                                    ]);

                                    // Transfer received, update stock levels on both locations
                                    if ($qty_received > 0) {
                                         // Increment stock from receiver location
                                        $this->updateStock(Auth::user()->company_id, $product_id, $qty_received, $data["location_to_id"], "+", "Transfer", $id);

                                        // Decrement stock from sender
                                        $this->updateStock(Auth::user()->company_id, $product_id, $qty_received, $data["location_from_id"], "-", "Transfer", $id);

                                        Transfer::where('id', $id)->update(['status' => 1]); // Transfer status
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function remove($id)
    {
        DB::transaction(function () use ($id)
        {
            $getItem = Transfer::where('id', $id)->with('details')->get();

            foreach ($getItem[0]->details as $value)
            {
                if ($value->qty_received > 0) { // IF ALREADY RECEIVED
                    $this->updateStock(Auth::user()->company_id, $value->product_id, $value->qty_received, $getItem[0]->location_to->id, "-", "Transfer", $id);    // DECREMENT STOCK FROM RECEIVER LOCATION
                    $this->updateStock(Auth::user()->company_id, $value->product_id, $value->qty_received, $getItem[0]->location_from->id, "+", "Transfer", $id);  // INCREMENT STOCK FROM SENDER LOCATION
                }
            }
            // parent::delete($id);
            Transfer::where('id', $id)->delete();
        });
    }
}
