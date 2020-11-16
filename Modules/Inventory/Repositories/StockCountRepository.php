<?php

namespace Modules\Inventory\Repositories;

use App\Models\Location;
use App\Models\Parameter;
use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\StockCount;
use Modules\Inventory\Entities\StockCountDetail;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\Product;

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

    public function findProductsAvailabilities($filter){

        $qb = Product::with(['brand', 'product_attributes.attribute', 'category']);

        if(!empty($filter['brand_id'])){
            $qb->where('brand_id', $filter['brand_id']);
        }

        if(!empty($filter['product_id'])){
            $qb->where('id', $filter['product_id']);
        }

        if(!empty($filter['category_id'])){
            $qb->where('category_id', $filter['category_id']);
        }

        if(!empty($filter['stock_locator'])){
            $qb->where('stock_locator', $filter['stock_locator']);
        }
        // @todo check, tag is a many relation
        if(!empty($filter['tag_ids'])){
            $qb->whereIn('tag_ids', $filter['tag_ids']);
        }

        if(isset($filter['is_enabled'])){
            $qb->where('is_enabled', $filter['is_enabled']);
        }

        if(!empty($filter['per_page'])){
            $products = $qb->paginate($filter['per_page']);
        }
        else{
            $products = $qb->limit(1)->get();
        }


        $location       = Location::find($filter['location_id']);
        $bins           = $location->bins;
        $availabilities = [];
        foreach ($products as $product) {

            if(!empty($bins) && isset($filter['bin_ids'])){
                foreach ($bins as $bin) {
                    // no bin filter OR the bin match the filter
                    if(empty($filter['bin_ids']) || in_array($bin->id, $filter['bin_ids'])){
                        $availability = $product->availabilities()
                        ->where('location_id', $location->id)
                        ->where('bin_id', $bin->id)
                        ->first();

                        $availabilities[] = $this->getAvailability($product, $location, $availability, $bin);
                    }
                }
            }
            else{
                lad('else');
                $availability = $product->availabilities()
                    ->where('location_id', $location->id)
                    ->whereNull('bin_id')
                    ->first();

                lad('$availability', $availability);

                $availabilities[] = $this->getAvailability($product, $location, $availability);
            }
        }

        $collection = $products->toArray();

        return !empty($filter['per_page']) ? ['list' => $availabilities, 'pagination' => Arr::except($collection, 'data')] : $availabilities;
    }

    private function getAvailability($product, $location, $availability, $bin = null){

        $on_hand        = $availability ? $availability->on_hand : 0;
        $available      = $availability ? $availability->available : 0;
        $location_id    = $availability ? $availability->location_id : $location->id;
        $location_name  = $availability ? optional($availability->location)->name : $location->name;
        $bin_id         = $availability ? $availability->bin_id : ($bin->id ?? null);
        $bin_name       = $availability ? optional($availability->bin)->name : ($bin->name ?? null);

        return [
            'product_id'        => $product->id,
            'product_name'      => $product->name,
            'product_sku'       => $product->sku,
            'product_brand'     => optional($product->brand)->name,
            'product_category'  => optional($product->category)->name,
            'location_id'       => $location_id,
            'location_name'     => $location_name,
            'bin_id'            => $bin_id,
            'bin_name'          => $bin_name,
            'on_hand'           => $on_hand,
            'available'         => $available,
            'qty'               => 0,
            'variance'          => 0,
            'notes'             => null
        ];
    }

    public function destroy($id)
    {

        $stockcount = StockCount::where('id', $id->id)->get();
        $availabilityRepository = new AvailabilityRepository(new Availability());

        // GET ALL SAVED QTY FROM COUNTING
        $stock = StockCountDetail::where('stockcount_id', $id->id)->get();

        foreach ($stock as $value)
        {
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
            $this->model->details()->sync($data['list_products']);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->model->details()->sync($data['list_products']);
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
                    $log->user_id       =  auth()->user()->id;
                    $log->save();

                }

            }

            // SAVE STATUS AS FINISHED
            StockCount::where('id', $stockcount_id)->update(['status' => true]);
            return true;
        });
    }
}