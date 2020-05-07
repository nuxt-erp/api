<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\StockTakeDetails;
use App\Models\StockTake;
use App\Models\ProductAvailability;
use Illuminate\Support\Facades\DB;
use App\Traits\StockTrait;

class StockTakeRepository extends RepositoryService
{
    use StockTrait;

    public function findBy(array $searchCriteria = [])
    {
        $this->queryBuilder->select('id', 'name', 'date' , 'target', 'count_type_id', 'skip_today_received', 'add_discontinued', 'variance_last_count_id', 'company_id', 'status', 'brand_id',  'category_id', 'location_id');

        // SUCCESS RATE CALCULATION
        $this->queryBuilder->addSelect(\DB::raw('
        ROUND(((SELECT SUM(if(ABS(d.variance) <= stocktake.target, 1, 0)) FROM stocktake_details d WHERE stocktake_id = stocktake.id)
        /
        (SELECT count(*) FROM stocktake_details d2 WHERE d2.stocktake_id = stocktake.id) * 100), 2)  as success_rate'));

        // SUM OF VARIANCE
        $this->queryBuilder->addSelect(\DB::raw('(SELECT SUM(variance) FROM stocktake_details sd WHERE sd.stocktake_id = stocktake.id) as net_variance'));

        // SUM OF ABS VARIANCE
        $this->queryBuilder->addSelect(\DB::raw('(SELECT SUM(abs_variance) FROM stocktake_details sd2 WHERE sd2.stocktake_id = stocktake.id) as abs_variance'));

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('category_id', Arr::pull($searchCriteria, 'category_id'));
        }

        if (!empty($searchCriteria['skip_today_received'])) {
            $this->queryBuilder
            ->whereDate('date', '<>', date("y-m-d"));
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
            ->where('brand_id', Arr::pull($searchCriteria, 'brand_id'));
        }

        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
            ->where('location_id', Arr::pull($searchCriteria, 'location_id'));
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

    public function delete($id)
    {
        // GET ALL SAVED QTY FROM COUNTING
        $stock = StockTakeDetails::where('stocktake_id', $id->id)->get();

        foreach ($stock as $value)
        {
            /*
            ProductAvailability::updateOrCreate([
                'product_id'  => $value->product_id,
                'company_id'  => Auth::user()->company_id,
                'location_id' => $value->location_id
            ],
            [
                'available' => $value->stock_on_hand, // PREVIOUS QTY
                'on_hand'   => $value->stock_on_hand  // PREVIOUS QTY
            ]);*/

            // DECREMENT STOCK
            $this->updateStock($value->product_id, $value->stock_on_hand, $value->location_id, "-");
        }

        parent::destroy($id);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            // SET LOGGED USER'S COMPANY
            $data["company_id"] = Auth::user()->company_id;
            // SAVE STOCK TAKE
            parent::store($data);
            // SAVE STOCK TAKE PRODUCTS
            $this->saveStockTakeDetails($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            $data["status"] = ($data["status"] == "IN PROGRESS" ? 1 : 0);
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->saveStockTakeDetails($data, $this->model->id);
        });
    }

    // ADJUST & FINISH STOCK COUNT
    public function finish($stocktake_id)
    {
        DB::transaction(function () use ($stocktake_id)
        {

            // GET ALL SAVED QTY FROM COUNTING
            $stock = StockTakeDetails::where('stocktake_id', $stocktake_id)->get();

            foreach ($stock as $value)
            {
                ProductAvailability::updateOrCreate([
                    'product_id'  => $value->product_id,
                    'company_id'  => Auth::user()->company_id,
                    'location_id' => $value->location_id
                ],
                [
                    'available' => $value->stock_on_hand, //PREVIOUS QTY
                    'on_hand'   => $value->qty // QTY COUNTED
                ]);
            }

            // SAVE STATUS AS FINISHED
            StockTake::where('id', $stocktake_id)->update(['status' => true]);
            return true;
        });
    }


    private function saveStockTakeDetails($data, $id)
    {

        if (isset($data["list_products"]))
        {
            $object = $data["list_products"];
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
                                $notes      = "";
                                $get_array  = $attributes[$i];

                                if (array_key_exists('qty', $get_array)) {
                                    $qty = isset($get_array["qty"]) ? $get_array["qty"] : 0;
                                } else {
                                    $qty = 0;
                                }

                                if (array_key_exists('notes', $get_array)) {
                                    $notes = isset($get_array["notes"]) ? $get_array["notes"] : "";
                                } else {
                                    $notes = "";
                                }

                                StockTakeDetails::updateOrCreate([
                                    'stocktake_id'  => $id,
                                    'product_id'    => $get_array["product_id"],
                                    'location_id'   => $data["location_id"]
                                ],[
                                    'qty'           => $qty,
                                    'stock_on_hand' => $get_array["on_hand"],
                                    'variance'      => ($qty - $get_array["on_hand"]),
                                    'abs_variance'  => abs($qty - $get_array["on_hand"]),
                                    'notes'         => $notes
                                ]);

                            }
                        }
                    }
                }
            }
        }
    }

}
