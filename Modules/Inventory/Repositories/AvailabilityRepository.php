<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use App\Repositories\RepositoryService;

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
                ->where('inv_products.name', 'LIKE', $name)
                ->orWhere('inv_products.sku', 'LIKE', $name);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
                ->where('inv_products.brand_id', Arr::pull($searchCriteria, 'brand_id'));
        }

        return parent::findBy($searchCriteria);
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

}
