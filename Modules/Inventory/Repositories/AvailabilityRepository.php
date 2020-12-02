<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Arr;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\ProductLog;

class AvailabilityRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        $this->queryBuilder->with('product');

        $searchCriteria['order_by'] = [
            'field'         => 'inv_products.name',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
                ->where('inv_products.category_id', Arr::pull($searchCriteria, 'category_id'));
        }

        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
                ->where('inv_availabilities.location_id', Arr::pull($searchCriteria, 'location_id'));
        }

        if (isset($searchCriteria['bin_id'])) {
            if($searchCriteria['bin_id'] > 0){
                $this->queryBuilder
                ->where('inv_availabilities.bin_id', Arr::pull($searchCriteria, 'bin_id'));
            }
            else{
                $this->queryBuilder->whereNull('bin_id');
            }
        }

        if (!empty($searchCriteria['product_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'product_name') . '%';
            $this->queryBuilder
                ->where('inv_products.name', 'ILIKE', $name)
                ->orWhere('inv_products.sku', 'ILIKE', $name);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
                ->where('inv_products.brand_id', Arr::pull($searchCriteria, 'brand_id'));
        }

        return parent::findBy($searchCriteria);
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){

            parent::update($model, $data);

            // ADD MOVEMENT TO PRODUCT LOG
            $type = Parameter::firstOrCreate(
                ['name' => 'product_log_type', 'value' => 'Stock Update']
            );
            $log                = new ProductLog();
            $log->product_id    = $data['product_id'];
            $log->location_id   = $data['location_id'];
            $log->bin_id        = $data['bin_id'] ?? null;
            $log->quantity      = $data['on_hand'];
            $log->ref_code_id   = null;
            $log->type_id       = $type->id;
            $log->description   = 'Finished stock update - changing quantity';
            $log->user_id       =  Auth::user()->id;
            $log->save();
        });
    }

    /*
    *
    * Update stock availability when:
    *
    *    - Transfer between locations, Adjustment, Purchase, Sales, Stock count
    *
    *    params:
    *        $company_id  = Company ID
    *        $product_id  = Product ID
    *        $qty         = Quantity - can be null when we are intent to update on_order or allocated quantities
    *        $location_id = Location updated
    *        $operator    = (+)  Increase stock level, (-) decrease stock level
    *        $type        = Source of changes (Sale, Purchase, Transfer, Adjustment, Stock Count)
    *        $ref_code    = Supplier ID when purchase or Customer ID when sales
    *        $on_order    = When purchase not completed yet - Quantity In transit
    *        $allocated   = When sale not fulfilled yet - Reserved quantity
    *
    */
    //  $product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty, $allocated_qty, $description
    public function updateStock($product_id, $qty, $location_id, $bin_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = '')
    {
        $field_to_update = 'on_hand'; // Default option

        // Check type of operation
        if($qty == 0 && $allocated_qty != 0){
            // Updating purchase (in transit quantity) or Updating Sale (reserved quantity)
            $field_to_update    = $type == 'Purchase' ? 'on_order' : ($type == 'Sale' ? 'allocated' : 'on_hand');
            $qty                = $type == 'Purchase' ? $on_order_qty : ($type == 'Sale' ? $allocated_qty : $qty) ;
        }

        $qb = Availability::where('product_id', $product_id);
        if(!empty($location_id)){
            $qb->where('location_id', $location_id);
        }
        if(!empty($bin_id)){
            $qb->where('bin_id', $bin_id);
        }

        $availability = $qb->first();
        if(!$availability){
            $availability = Availability::create([
                'product_id'    => $product_id,
                'location_id'   => $location_id,
                'bin_id'        => $bin_id
            ]);
        }

        if ($operator == "+") {
            $availability->increment($field_to_update, $qty);
        }
        elseif ($operator == "-") {
            $availability->decrement($field_to_update, $qty);
        }

        $type = Parameter::firstOrCreate(
            ['name' => 'product_log_type', 'value' => $type]
        );

        $log = new ProductLog();
        $log->product_id    = $product_id;
        $log->location_id   = $location_id;
        $log->bin_id        = $bin_id;
        $log->quantity      = ($operator == "-" ? -$qty : $qty);
        $log->ref_code_id   = $ref_code;
        $log->type_id       = $type->id;
        $log->description   = $description;
        if (Auth::user()) {
            $log->user_id       = Auth::user()->id;
        }

        $log->save();
    }

    // USED TO LOAD PRODUCT AVAILABILITIES, STOCK COUNT
    public function productAvailabilities(array $searchCriteria = [])
    {
        $qb = $this->queryBuilder;

        $qb->leftJoin('inv_brands', 'inv_brands.id', 'inv_products.brand_id');
        $qb->leftJoin('inv_categories', 'inv_categories.id', 'inv_products.category_id');
        $qb->leftJoin('inv_location_bins', 'inv_location_bins.id', 'inv_availabilities.bin_id');
        $qb->leftJoin('locations', 'locations.id', 'inv_availabilities.location_id');

        $qb->orderBy('inv_products.sku', 'ASC');

        $qb->select(
            'inv_brands.name as brand_name',
            'inv_categories.name as category_name',
            'inv_products.id',
            'inv_products.name',
            'inv_products.sku',

            'inv_availabilities.location_id',
            'locations.name as location_name',

            'inv_availabilities.bin_id',
            'inv_location_bins.name as bin_name',

            'inv_availabilities.on_hand',
            'inv_products.category_id',
            'inv_products.brand_id'
        );

        // get qty from stockcount details not availabilities
        // @todo check
        if (!empty($searchCriteria['stockcount_id'])) {
            $qb->addSelect('dt.qty as qty');
            $qb->leftJoin('inv_stock_count_details dt', 'dt.product_id', 'inv_products.id');
            unset($searchCriteria['stockcount_id']);
        }

        // STOCK COUNT IS MADE BASED ON WHAT QTY WE HAVE IN THE LOCATION AND BIN (IF BIN IS SET) ---->
        $qb->join('inv_availabilities', function ($join) use($searchCriteria) {
            // GET QTY BASED ON THE
            $join->on('inv_availabilities.product_id', '=', 'inv_products.id')
                 ->where('inv_availabilities.location_id', '=', Arr::pull($searchCriteria, 'location_id'));

            // FILTER AVAILABILITY BY BIN IF IS SET
            if (!empty($searchCriteria['bin_id'])) {
                $join->where('inv_availabilities.bin_id', Arr::pull($searchCriteria, 'bin_id'));
            }

        });

        // PRODUCTS FILTER START ---->
        if (!empty($searchCriteria['add_discontinued'])) {
            $qb->where('inv_products.is_enabled', Arr::pull($searchCriteria, 'add_discontinued'));
        }

        if (!empty($searchCriteria['product_id'])) {
            $qb->where('inv_products.id', Arr::pull($searchCriteria, 'product_id'));
        }

        if (!empty($searchCriteria['barcode'])) {
            $qb->where('inv_products.barcode', Arr::pull($searchCriteria, 'barcode'));
        }

        if (!empty($searchCriteria['category_id'])) {
            $qb->where('inv_products.category_id', Arr::pull($searchCriteria, 'category_id'));
        }

        if (!empty($searchCriteria['brand_id'])) {
            $qb->where('inv_products.brand_id', Arr::pull($searchCriteria, 'brand_id'));
        }

        return $qb->limit(300)->get();
    }

    public function stockOnHand($product_id,$location_id)
    {
       $item= Availability::where([
            'product_id'    => $product_id,
            'location_id'   => $location_id
        ])->first();

       return isset($item)?$item->on_hand:0;
    }

}
