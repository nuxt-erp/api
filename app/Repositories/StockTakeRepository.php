<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\StockTakeDetails;

class StockTakeRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['sku'])) {
            $sku = '%' . Arr::pull($searchCriteria, 'sku') . '%';
            $this->queryBuilder
            ->where('sku', 'LIKE', $sku)
            ->orWhere('name', 'LIKE', $sku);
        }

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('category_id', $searchCriteria['category_id']);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
            ->where('brand_id', $searchCriteria['brand_id']);
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
            $searchCriteria['sku'] = $name;
        }

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        // SET LOGGED USER'S COMPANY
        $data["company_id"] = Auth::user()->company_id;
        // SAVE STOCK TAKE
        parent::store($data);
        // SAVE STOCK TAKE PRODUCTS
        $this->saveStockTakeDetails($data, $this->model->id);
    }

    private function saveStockTakeDetails($data, $id)
    {

        if (isset($data["prod_attributes"]))
        {
            $object = $data["prod_attributes"];
            $row    = 0;
            $tot    = 0;

            // DELETE ITEMS TO INSERT THEM AGAIN
            StockTakeDetails::where('stocktake_id', $id)->delete();

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
                                $qty        = 0;
                                $get_array  = $attributes[$i];

                                if (array_key_exists('qty', $get_array))
                                {
                                    $qty = isset($get_array["qty"]) ? $get_array["qty"] : 0;
                                }
                                else
                                {
                                    $qty = 0;
                                }

                                StockTakeDetails::updateOrCreate([
                                    'stocktake_id'  => $id,
                                    'product_id'    => $get_array["product_id"]
                                ],[
                                    'qty'           => $qty,
                                    'stock_on_hand' => $get_array["on_hand"],
                                    'variance'      => ($qty - $get_array["on_hand"])
                                ]);

                            }
                        }
                    }
                }
            }
        }
    }

}
