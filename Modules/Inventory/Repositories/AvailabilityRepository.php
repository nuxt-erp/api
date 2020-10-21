<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Arr;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;

class AvailabilityRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        $searchCriteria['order_by'] = [
            'field'         => 'inv_products.name',
            'direction'     => 'asc'
        ];

        $this->queryBuilder->leftJoin('inv_products', 'inv_availabilities.product_id', 'inv_products.id');

        $this->queryBuilder->select('inv_products.sku', 'inv_availabilities.location_id', 'inv_products.brand_id', 'inv_products.category_id', 'inv_availabilities.id', 'inv_availabilities.product_id', 'inv_availabilities.available', 'inv_availabilities.on_hand', 'inv_availabilities.on_order', 'inv_availabilities.allocated');

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
                ->where('inv_products.category_id', Arr::pull($searchCriteria, 'category_id'));
        }

        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
                ->where('inv_availabilities.location_id', Arr::pull($searchCriteria, 'location_id'));
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
            $log->quantity      = $data['on_hand'];
            $log->ref_code_id   = null;
            $log->type_id       = $type->id;
            $log->description   = 'Finished stock update - changing quantity';
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
    public function updateStock($product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = '')
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

        $availability = $qb->first();
        if(!$availability){
            $availability = Availability::create([
                'product_id'    => $product_id,
                'location_id'   => $location_id
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
        $log->quantity      = ($operator == "-" ? -$qty : $qty);
        $log->ref_code_id   = $ref_code;
        $log->type_id       = $type->id;
        $log->description   = $description;
        $log->save();
    }

    // USED TO LOAD PRODUCT AVAILABILITIES, STOCK TAKE AND PRODUCTS

    public function productAvailabilities(array $searchCriteria = [])
    {

        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 20;

        $this->queryBuilder->rightJoin('inv_products', 'inv_availabilities.product_id', 'inv_products.id');
        $this->queryBuilder->leftJoin('inv_brands', 'inv_brands.id', 'inv_products.brand_id');
        $this->queryBuilder->leftJoin('inv_categories', 'inv_categories.id', 'inv_products.category_id');
        $this->queryBuilder->select(
            'inv_brands.name as brand_name',
            'inv_categories.name as category_name',
            'inv_products.id',
            'inv_products.name',
            'inv_products.sku',

            'inv_products.location_id',
            'locations.name as location_name',

            'l2.id as location_id2',
            'l2.name as location_name2',

            'inv_availabilities.on_hand',
            'inv_products.category_id',
            'inv_products.brand_id'
        );
        if (!empty($searchCriteria['stockcount_id'])) {
            $this->queryBuilder->addSelect('dt.qty as qty');
            $this->queryBuilder->leftJoin('inv_stock_count_details dt', 'dt.product_id', 'inv_products.id');
            unset($searchCriteria['stockcount_id']);
        }

        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder->leftJoin('locations', 'locations.id', 'inv_products.location_id')
            ->leftJoin('locations as l2', 'l2.id', 'inv_availabilities.location_id');

            $this->queryBuilder->where(function($query) use($searchCriteria) {
                $query->where('inv_products.location_id', $searchCriteria['location_id'])
                      ->orWhere('inv_availabilities.location_id', $searchCriteria['location_id']);
            });
            unset($searchCriteria['location_id']);
        } else {
            $this->queryBuilder->leftJoin('locations', 'locations.id', 'inv_products.location_id');
        }

        if (!empty($searchCriteria['add_discontinued'])) {
            $this->queryBuilder
                ->where('inv_products.is_enabled', Arr::pull($searchCriteria, 'add_discontinued'));
            unset($searchCriteria['add_discontinued']);
        }

        if (!empty($searchCriteria['stockcount_id'])) {
            $this->queryBuilder
                ->where('stockcount_id', Arr::pull($searchCriteria, 'stockcount_id'));
            unset($searchCriteria['stockcount_id']);
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
                ->where('category_id', Arr::pull($searchCriteria, 'category_id'));
            unset($searchCriteria['category_id']);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
                ->where('brand_id', Arr::pull($searchCriteria, 'brand_id'));
            unset($searchCriteria['brand_id']);
        }

        return parent::findBy($searchCriteria);
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
