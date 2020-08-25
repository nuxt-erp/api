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
            'field'         => 'products.name',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['product_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'product_name') . '%';
            $this->queryBuilder
            ->where('products.name', 'LIKE', $name)
            ->orWhere('products.sku', 'LIKE', $name);
        }

        $this->queryBuilder->select('availabilities.id', 'availabilities.product_id', 'availabilities.company_id', 'availabilities.available', 'availabilities.location_id', 'availabilities.on_hand', 'availabilities.on_order', 'availabilities.allocated');
        $this->queryBuilder->join('products', 'availabilities.product_id', 'products.id');

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('products.category_id', Arr::pull($searchCriteria, 'category_id'));
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
            ->where('products.brand_id', Arr::pull($searchCriteria, 'brand_id'));
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

    public function updateStock($company_id, $product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = '')
    {

        $field_to_update = 'on_hand'; // Default option

        // Check type of operation
        if ($type == "Purchase" && $qty == 0 && $on_order_qty != 0) {  // Updating purchase (in transit quantity)
            $field_to_update = 'on_order';
            $qty = $on_order_qty;
        } elseif ($type == "Sale" && $qty == 0 && $allocated_qty != 0) { // Updating Sale (reserved quantity)
            $field_to_update = 'allocated';
            $qty = $allocated_qty;
        }

        // Update stock
        if ($operator == "+") {
            if ($location_id == null) {
                Availability::where(['product_id' => $product_id])->increment($field_to_update, $qty);
            } else {
                Availability::where(['product_id' => $product_id, 'location_id' => $location_id])->increment($field_to_update, $qty);
            }
        } elseif ($operator == "-") {
            if ($location_id == null) {
                Availability::where(['product_id' => $product_id])->decrement($field_to_update, $qty);
            } else {
                Availability::where(['product_id' => $product_id, 'location_id' => $location_id])->decrement($field_to_update, $qty);
            }
        }

        // Create product log
        //if ($on_order_qty == 0 && $allocated_qty == 0) { // Log just for finished tasks

            $type = Parameter::firstOrCreate(
                ['name' => 'product_log_type', 'value' => $type]
                //'order', 'description', 'is_internal', 'is_default'
            );

            $log = new ProductLog();
            $log->product_id    = $product_id;
            $log->location_id   = $location_id;
            $log->date          = date('Y-m-d H:s:i');
            $log->quantity      = ($operator == "-" ? -$qty : $qty);
            $log->ref_code_id   = $ref_code;
            $log->type_id       = $type->id;
            $log->description   = $description;
            $log->save();
        //}

    }

     // USED TO LOAD PRODUCT AVAILABILITIES, STOCK TAKE AND PRODUCTS
     public function productAvailabilities(array $searchCriteria = [])
     {
        $searchCriteria['order_by'] = [
             'field'         => 'name',
             'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 20;

        $this->queryBuilder->select('brands.name as brand_name', 'categories.name as category_name', 'products.id', 'products.name', 'products.sku',
         'availabilities.location_id', 'availabilities.on_hand', 'products.category_id', 'products.brand_id');
        $this->queryBuilder->rightJoin('products', 'availabilities.product_id', 'products.id');
        $this->queryBuilder->join('brands', 'brands.id', 'products.brand_id');
        $this->queryBuilder->join('categories', 'categories.id', 'products.category_id');

         // EDITING STOCKTAKE
         if (!empty($searchCriteria['stocktake_id']))
         {
             $this->queryBuilder->addSelect('dt.qty as qty');
             $this->queryBuilder->leftJoin('stocktake_details dt', 'dt.product_id', 'products.id');
         }

         if ($searchCriteria['location_id']) {
            $this->queryBuilder->join('locations', 'locations.id', 'availabilities.location_id')->where('availabilities.location_id', $searchCriteria['location_id']);
         } else {
            $this->queryBuilder->join('locations', 'locations.id', 'availabilities.location_id');
         }

         if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
            ->where('availabilities.location_id', Arr::pull($searchCriteria, 'location_id'));
         }

         if (!empty($searchCriteria['add_discontinued'])) {
            $this->queryBuilder
            ->where('products.status', Arr::pull($searchCriteria, 'add_discontinued'));
         }

         if (!empty($searchCriteria['stocktake_id'])) {
             $this->queryBuilder
             ->where('stocktake_id', Arr::pull($searchCriteria, 'stocktake_id'));
         }

         if (!empty($searchCriteria['category_id'])) {
             $this->queryBuilder
             ->where('category_id', Arr::pull($searchCriteria, 'category_id'));
         }

         if (!empty($searchCriteria['brand_id'])) {
             $this->queryBuilder
             ->where('brand_id', Arr::pull($searchCriteria, 'brand_id'));
         }

         return parent::findBy($searchCriteria);

     }
}
