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
use Modules\Inventory\Entities\LocationBin;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\StockCountFilter;

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

        $qb = Product::has('availabilities')->with(['brand', 'product_attributes.attribute', 'category']);

        if(!empty($filter['brand_ids'])){
            $qb->whereIn('brand_id', $filter['brand_ids']);
        }

        if(!empty($filter['product_id'])){
            $qb->where('id', $filter['product_id']);
        }

        if(!empty($filter['bin_ids'])){
            $qb->with(['availabilities' => function ($query) use($filter) {
                $query->whereIn('bin_id', $filter['bin_ids']);
            }, 'availabilities.bin']);
        }
        else {
            $qb->with(['availabilities', 'availabilities.bin']);
        }

        if(!empty($filter['barcode'])){
            $qb->where('barcode', $filter['barcode']);
        }

        if(!empty($filter['searchable'])){
            $qb->where('barcode', 'ILIKE', $filter['searchable'])->orWhere('sku', 'ILIKE',  $filter['searchable']);
        }

        if(!empty($filter['category_ids'])){
            $qb->whereIn('category_id', $filter['category_ids']);
        }

        if(!empty($filter['stock_locator_ids'])){
            $qb->whereIn('stock_locator', $filter['stock_locator_ids']);
        }

        if (!empty($filter['tag_ids'])) {
            $qb->whereHas('tags', function ($query) use($filter) {
                $query->whereIn('id', $filter['tag_ids']);
            });
        }

        if(isset($filter['is_enabled'])){
            $qb->where('is_enabled', $filter['is_enabled']);
        }

        if(!empty($filter['per_page'])){
            $products = $qb->paginate($filter['per_page']);
        }
        else{
            $products = $qb->limit(1)->get(); // get product with all bins
        }

        $location       = Location::find($filter['location_id']);
        $availabilities = [];

        foreach ($products as $product) {
            foreach($product['availabilities'] as $availability) {
                $availabilities[] = $this->getAvailability($product, $location, $availability, !empty($availability['bin']) ? $availability['bin'] : null);
            }
        }

        $collection = $products->toArray();

        return !empty($filter['per_page']) ? ['list' => $availabilities, 'pagination' => Arr::except($collection, 'data')] : $availabilities;
    }
    public function findProductsAvailabilitiesMobile($filter){

        $qb = Product::with(['brand', 'product_attributes.attribute', 'category']);

        if(!empty($filter['brand_ids'])){
            $qb->whereIn('brand_id', $filter['brand_ids']);
        }

        if(!empty($filter['product_id'])){
            $qb->where('id', $filter['product_id']);
        }

        if(!empty($filter['barcode'])){
            $qb->where('barcode', $filter['barcode']);
        }

        if(!empty($filter['searchable'])){
            $qb->where('barcode', 'ILIKE', $filter['searchable'])->orWhere('sku', 'ILIKE',  $filter['searchable']);
        }

        if(!empty($filter['category_ids'])){
            $qb->whereIn('category_id', $filter['category_ids']);
        }

        if(!empty($filter['stock_locator_ids'])){
            $qb->whereIn('stock_locator', $filter['stock_locator_ids']);
        }

        if (!empty($filter['tag_ids'])) {
            $qb->whereHas('tags', function ($query) use($filter) {
                $query->whereIn('id', $filter['tag_ids']);
            });
        }

        if(isset($filter['is_enabled'])){
            $qb->where('is_enabled', $filter['is_enabled']);
        }

        if(!empty($filter['per_page'])){
            $products = $qb->paginate($filter['per_page']);
        }
        else{
            $products = $qb->get(); // get product with all bins
        }
        $multiple = false;
        $location       = Location::find($filter['location_id']);
        $availabilities_to_send = [];
        lad("TEST");
        // lad($products);
        // if(count($products) > 1) {
        //     $multiple = true;
        // }
        // $availabilities_to_send['multiple'] = $multiple;

        foreach ($products as $product) {
            $availabilities = $product->availabilities()
            ->where('location_id', $location->id)
            ->get();
            // if ($multiple) {
            //     foreach ($availabilities as $availability) {
            //         // lad($availability);

            //         if(!empty($filter['bin_ids']) && !empty($availability->bin_id)) {
            //             if(in_array($availability->bin_id, $filter['bin_ids'])) {
            //                 $bin = LocationBin::find($availability->bin_id);
            //                 $availabilities_to_send[$product->id][] = $this->getAvailability($product, $location, $availability, $bin);
            //             }
            //         } else {
            //             $availabilities_to_send[$product->id][] = $this->getAvailability($product, $location, $availability);
            //         }
            //     }
            // } else {
                foreach ($availabilities as $availability) {
                    lad($availability);
                    if(!empty($filter['bin_ids']) && !empty($availability->bin_id)) {
                        if(in_array($availability->bin_id, $filter['bin_ids'])) {
                            $bin = LocationBin::find($availability->bin_id);
                            $availabilities_to_send[] = $this->getAvailability($product, $location, $availability, $bin);
                        }
                    } else {
                        $availabilities_to_send[] = $this->getAvailability($product, $location, $availability);
                    }
                }
            // }

        }

        $collection = $products->toArray();
        return !empty($filter['per_page']) ? ['list' => $availabilities_to_send, 'pagination' => Arr::except($collection, 'data')] : $availabilities_to_send;
    }

    private function getAvailability($product, $location, $availability, $bin = null){

        $on_hand        = $availability ? $availability->on_hand : 0;
        $available      = $availability ? $availability->available : 0;
        $location_id    = $availability ? $availability->location_id : $location->id;
        $location_name  = $availability ? optional($availability->location)->name : $location->name;
        $bin_id         = $availability ? $availability->bin_id : ($bin->id ?? null);
        $bin_name       = $availability ? optional($availability->bin)->name : ($bin->name ?? null);
        $bin_searchable = $availability ? optional($availability->bin)->barcode : ($bin->barcode ?? null);

        return [
            'product_id'        => $product->id,
            'product_name'      => $product->sku .' - '.$product->name,
            'product_full_name' => $product->full_description,
            'product_sku'       => $product->sku,
            'product_brand'     => optional($product->brand)->name,
            'product_category'  => optional($product->category)->name,
            'searchable'        => $product->barcode ?? $product->sku,
            'location_id'       => $location_id,
            'location_name'     => $location_name,
            'bin_id'            => $bin_id,
            'bin_name'          => $bin_name,
            'bin_searchable'    => $bin_searchable,
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
            if(empty($data['date'])) {
                $data['date'] = now();
            }
            // SAVE STOCK TAKE
            parent::store($data);
            // SAVE STOCK TAKE PRODUCTS
            if(!empty($data['stock_count_filters'])) {
                foreach($data['stock_count_filters'] as $key => $list) {
                    if($key === 'tag_ids') {
                        foreach($list as $val) {
                            StockCountFilter::create([
                                'type' => 'App\\Models\\Tag',
                                'type_id' => $val,
                                'stocktake_id' => $this->model->id,
                            ]);
                        }

                    }
                    else if($key === 'stock_locator_ids') {
                        foreach($list as $val) {
                            StockCountFilter::create([
                                'type' => 'Modules\\Inventory\\Entities\\StockLocator',
                                'type_id' => $val,
                                'stocktake_id' => $this->model->id,
                            ]);
                        }
                    }
                    else if($key === 'bin_ids') {

                        foreach($list as $val) {
                            StockCountFilter::create([
                                'type' => 'Modules\\Inventory\\Entities\\LocationBin',
                                'type_id' => $val,
                                'stocktake_id' => $this->model->id,
                            ]);
                        }
                    }
                    else if($key === 'category_ids') {

                        foreach($list as $val) {
                            StockCountFilter::create([
                                'type' => 'Modules\\Inventory\\Entities\\Category',
                                'type_id' => $val,
                                'stocktake_id' => $this->model->id,
                            ]);
                        }
                    }
                    else if($key === 'brand_ids') {
                        foreach($list as $val) {
                            StockCountFilter::create([
                                'type' => 'Modules\\Inventory\\Entities\\Brand',
                                'type_id' => $val,
                                'stocktake_id' => $this->model->id,
                            ]);
                        }

                    }
                }
            }
            if(!empty($data['list_products'])) {
                $this->model->details()->sync($data['list_products']);
            }
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
