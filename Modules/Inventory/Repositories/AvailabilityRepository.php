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
       
        $this->queryBuilder->join('products', 'inv_availabilities.product_id', 'products.id');

        $this->queryBuilder->select('inv_availabilities.id', 'inv_availabilities.product_id', 'inv_availabilities.company_id', 'inv_availabilities.available', 'inv_availabilities.location_id', 'inv_availabilities.on_hand', 'inv_availabilities.on_order', 'inv_availabilities.allocated');
        if (!empty($searchCriteria['product_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'product_name') . '%';
            $this->queryBuilder
            ->where('products.name', 'LIKE', $name)
            ->orWhere('products.sku', 'LIKE', $name);
        }
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
                            //  $product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty, $allocated_qty, $description
    public function updateStock($product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = '')
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

        
        $this->queryBuilder->rightJoin('products', 'inv_availabilities.product_id', 'products.id');
        $this->queryBuilder->join('brands', 'brands.id', 'products.brand_id');
        $this->queryBuilder->join('categories', 'categories.id', 'products.category_id');
        $this->queryBuilder->select('brands.name as brand_name', 'categories.name as category_name', 'products.id', 'products.name', 'products.sku',
        'inv_availabilities.location_id', 'inv_availabilities.on_hand', 'products.category_id', 'products.brand_id');
         if (!empty($searchCriteria['stockcount_id']))
         {
             $this->queryBuilder->addSelect('dt.qty as qty');
             $this->queryBuilder->leftJoin('inv_stock_count_details dt', 'dt.product_id', 'products.id');
         }

         if ($searchCriteria['location_id']) {
            $this->queryBuilder->join('locations', 'locations.id', 'inv_availabilities.location_id')->where('inv_availabilities.location_id', $searchCriteria['location_id']);
         } else {
            $this->queryBuilder->join('locations', 'locations.id', 'inv_availabilities.location_id');
         }

         if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
            ->where('inv_availabilities.location_id', Arr::pull($searchCriteria, 'location_id'));
         }

         if (!empty($searchCriteria['add_discontinued'])) {
            $this->queryBuilder
            ->where('products.status', Arr::pull($searchCriteria, 'add_discontinued'));
         }

         if (!empty($searchCriteria['stockcount_id'])) {
             $this->queryBuilder
             ->where('stockcount_id', Arr::pull($searchCriteria, 'stockcount_id'));
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
