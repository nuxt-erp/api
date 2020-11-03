<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\StockCount;
use Modules\Inventory\Entities\StockCountDetail;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Auth;
class StockCountRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        $this->queryBuilder->select('id', 'name', 'date' , 'target', 'count_type_id', 'add_discontinued', 'status', 'brand_id',  'category_id', 'location_id');

        // SUCCESS RATE CALCULATION    if(ABS(d.variance) <= inv_stock_counts.target, 1, 0)
        $this->queryBuilder->addSelect(\DB::raw('
        ROUND(((SELECT SUM(CASE WHEN (ABS(d.variance) <= inv_stock_counts.target) IN (true) THEN 1 ELSE 0 END) FROM inv_stock_count_details d WHERE stockcount_id = inv_stock_counts.id)
        /
        (SELECT count(*) FROM inv_stock_count_details d2 WHERE d2.stockcount_id = inv_stock_counts.id) * 100), 2)  as success_rate'));

        // SUM OF VARIANCE
        $this->queryBuilder->addSelect(\DB::raw('(SELECT SUM(variance) FROM inv_stock_count_details sd WHERE sd.stockcount_id = inv_stock_counts.id) as net_variance'));

        // SUM OF ABS VARIANCE
        $this->queryBuilder->addSelect(\DB::raw('(SELECT SUM(abs_variance) FROM inv_stock_count_details sd2 WHERE sd2.stockcount_id = inv_stock_counts.id) as abs_variance'));


        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('category_id', Arr::pull($searchCriteria, 'category_id'));
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
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
            $searchCriteria['sku'] = $name;
        }


        return parent::findBy($searchCriteria);
    }

    public function destroy($id)
    {

        $stockcount = StockCount::where('id', $id->id)->get();
        $availabilityRepository = new AvailabilityRepository(new Availability());

        // GET ALL SAVED QTY FROM COUNTING
        $stock = StockCountDetail::where('stockcount_id', $id->id)->get();

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

            // Undo stock when stock take is finished

            if ($stockcount->status == 1) {
                // Decrement
                $availabilityRepository->updateStock($value->product_id, $value->stock_on_hand, $value->location_id, $value->bin_id, "-", "Stock Count", $id, 0 , 0, "Remove item");
            }

        }

        // parent::delete($id);
         StockCount::where('id', $id->id)->delete();
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            // SAVE STOCK TAKE
            parent::store($data);
            // SAVE STOCK TAKE PRODUCTS
            $this->saveStockCountDetail($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->saveStockCountDetail($data, $this->model->id);
        });
    }

    // ADJUST & FINISH STOCK COUNT
    public function finish($stockcount_id)
    {
        DB::transaction(function () use ($stockcount_id)
        {
            // GET ALL SAVED QTY FROM COUNTING
            $stock_items = StockCountDetail::where('stockcount_id', $stockcount_id)->get();
            foreach ($stock_items as $item){

                // update availability
                if($item->location_id){

                    Availability::updateOrCreate([
                        'product_id'    => $item->product_id,
                        'location_id'   => $item->location_id,
                        'bin_id'        => $item->bin_id
                    ],
                    [
                        'on_hand'       => $item->qty
                    ]);

                    // add movement
                    $type = Parameter::firstOrCreate(
                        ['name' => 'product_log_type', 'value' => 'Stock Count']
                    );
                    $log                = new ProductLog();
                    $log->product_id    = $item->product_id;
                    $log->location_id   = $item->location_id;
                    $log->bin_id        = $item->bin_id;
                    $log->quantity      = $item->qty;
                    $log->ref_code_id   = $stockcount_id;
                    $log->type_id       = $type->id;
                    $log->description   = 'Finished stock count - changing quantity';
                    $log->user_id       =  Auth::user()->id;
                    $log->save();

                }

            }

            // SAVE STATUS AS FINISHED
            StockCount::where('id', $stockcount_id)->update(['status' => true]);
            return true;
        });
    }

    private function saveStockCountDetail($data, $id)
    {

        if (!empty($data['list_products']))
        {
            // DELETE ITEMS TO INSERT THEM AGAIN
            StockCountDetail::where('stockcount_id', $id)->delete();

            foreach ($data['list_products'] as $product) {
                $qty    = $product['qty'] ?? 0;
                $notes  = $product['notes'] ?? null;

                StockCountDetail::updateOrCreate([
                    'stockcount_id' => $id,
                    'product_id'    => $data['product_id'] ?? $product['product_id'],
                    'location_id'   => $data['location_id'],
                    'bin_id'        => $data['bin_id'] ?? null
                ],[
                    'qty'           => $qty,
                    'stock_on_hand' => $product['on_hand'],
                    'variance'      => ($qty - $product['on_hand']),
                    'abs_variance'  => abs($qty - $product['on_hand']),
                    'notes'         => $notes
                ]);
            }
        }
    }

}
