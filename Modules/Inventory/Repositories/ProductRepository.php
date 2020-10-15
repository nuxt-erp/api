<?php

namespace Modules\Inventory\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Family;
use Modules\Inventory\Entities\FamilyAttribute;

use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\ProductAttributes;
use Modules\Inventory\Entities\ProductPromo;
use Modules\Inventory\Entities\ProductReorderLevel;
use Modules\Inventory\Entities\ProductSupplierLocations;
use Modules\Inventory\Entities\ProductSuppliers;
use Modules\Inventory\Entities\CustomerDiscount;
use Modules\Inventory\Entities\ProductCustomPrice;

class ProductRepository extends RepositoryService
{
    private $result = [];
    private $generate = false; // GENERATE PRODUCT FAMILY

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'field'         => 'is_enabled',
            'direction'     => 'desc'
        ];
        //@todo add important relations (improve performance for queries)
        // $this->queryBuilder->with([])

        if (!empty($searchCriteria['list']))
        {
            $this->queryBuilder->where('is_enabled', true);
        }

        if (!empty($searchCriteria['sku'])) {
            $sku = '%' . Arr::pull($searchCriteria, 'sku') . '%';
            $this->queryBuilder
                ->where('sku', 'LIKE', $sku)
                ->orWhere('name', 'LIKE', $sku);
        }

        if (!empty($searchCriteria['id']) && empty($searchCriteria['list'])) {
            $this->queryBuilder
                ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['category_name'])) {
            $category = Arr::pull($searchCriteria, 'category_name');
            $this->queryBuilder->whereHas('category', function ($query) use ($category) {
                $query->where('product_categories.name', $category);
            });
        }

        if (!empty($searchCriteria['categories_id'])) {
            $this->queryBuilder
                ->whereIn('category_id', Arr::pull($searchCriteria, 'categories_id'));
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
                ->where('brand_id', $searchCriteria['brand_id']);
        }
        if (!empty($searchCriteria['stock_locator'])) {
            $this->queryBuilder
                ->where('stock_locator', $searchCriteria['stock_locator']);
        }
        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
                ->where('location_id', $searchCriteria['location_id']);
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
            $searchCriteria['sku'] = $name;
        }

        if (Arr::has($searchCriteria, 'complete_name')) {
            $searchCriteria['query_type']   = 'LIKE';
            $searchCriteria['where']        = 'OR';
            $name = Arr::pull($searchCriteria, 'complete_name');
            $searchCriteria['sku']          = '%' . $name . '%';
        }

        return parent::findBy($searchCriteria);
    }


    public function store($data)
    {

        DB::transaction(function () use ($data) {

            $this->generate = !empty($data["generate_family"]);
            $this->suppliers = !empty($data["suppliers"]);
            $this->discounts = !empty($data["discounts"]);
            if ($this->generate == true) // It came from product family
            {
                $data['family_id'] = $this->createFamily($data); // FIRST WE CREATE THE FAMILY

                $this->generateFamily($data, $data['family_id']);                    // GENERATE FAMILY

            }else {

                parent::store($data);
                $this->createAttribute($data); // CREATE ATTRIBUTE
                if($this->suppliers) {
                    $this->createSuppliers($data); // CREATE ATTRIBUTE
                }
                if($this->discounts) {
                    $this->createDiscounts($data); // CREATE ATTRIBUTE
                }
                if(!empty($data["reorder_levels"])) {
                    foreach($data["reorder_levels"] as $reorder_level) {
                        ProductReorderLevel::create([
                            'product_id'    => $this->model->id,
                            'location_id'   => $reorder_level['location_id'],
                            'safe_stock'    => $reorder_level['safe_stock'],
                            'reorder_qty'   => $reorder_level['reorder_qty']
                        ]);
                    }
                }

                if(!empty($data["promos"])) {
                    foreach($data["promos"] as $promo) {
                        ProductPromo::create([
                            'product_id'            => $this->model->id,
                            'discount_percentage'   => $promo['discount_percentage'] ?? 0,
                            'buy_qty'               => $promo['buy_qty'] ?? 0,
                            'get_qty'               => $promo['get_qty'] ?? 0,
                            'date_from'             => $promo['date_from'],
                            'date_to'               => $promo['date_to'],
                            'gift_product_id'       => $promo['gift_product_id'] ?? $this->model->id,
                        ]);
                    }
                }

                if(!empty($data["custom_prices"])) {
                    foreach($data["custom_prices"] as $price) {
                        ProductCustomPrice::create([
                            'product_id'            => $this->model->id,
                            'customer_id'           => $price['customer_id'],
                            'currency'              => strtoupper($price['currency']),
                            'custom_price'          => $price['custom_price'] ?? 0,
                            'is_enabled'            => $price['is_enabled'],
                            'disabled_at'           => !$price['is_enabled'] ? now() : null,
                        ]);
                    }
                }
            }
        });

    }
    public function update($model, array $data)
    {
        $this->suppliers = !empty($data["suppliers"]);
        $this->discounts = !empty($data["discounts"]);

        parent::update($model,$data);
        $this->createAttribute($data);
        if($this->suppliers) {
            $this->updateSuppliers($data);
        }
        if($this->discounts) {
            $this->updateDiscounts($data);
        }
        if(!empty($data['deleteDiscounts'])) {
            foreach ($data['deleteDiscounts'] as $deleteDiscount) {
                CustomerDiscount::where('id', $deleteDiscount['id'])->delete();
            }
        }
        if(!empty($data['deleteSuppliers'])) {
            foreach ($data['deleteSuppliers'] as $deleteSupplier) {
                ProductSuppliers::where('id', $deleteSupplier['id'])->delete();
            }
        }
        if(!empty($data['deleteSupplierLocations'])) {
            foreach ($data['deleteSupplierLocations'] as $deleteSupplierLocation) {
                ProductSupplierLocations::where('id', $deleteSupplierLocation['id'])->delete();
            }
        }

        if(!empty($data["reorder_levels"])) {
            $this->updateReorderLevels($data);
        }

        if(!empty($data["delete_reorder_levels"])) {
            foreach($data["delete_reorder_levels"] as $delete_reorder_level) {
                ProductReorderLevel::where('id', $delete_reorder_level['id'])->delete();
            }
        }

        if(!empty($data["promos"])) {
            $this->updatePromos($data);
        }

        if(!empty($data["delete_promos"])) {
            foreach($data["delete_promos"] as $delete_promo) {
                ProductPromo::where('id', $delete_promo['id'])->delete();
            }
        }

        if(!empty($data["custom_prices"])) {
            $this->updateCustomPrices($data);
        }

        if(!empty($data["delete_custom_prices"])) {
            foreach($data["delete_custom_prices"] as $delete_custom_price) {
                ProductCustomPrice::where('id', $delete_custom_price['id'])->delete();
            }
        }
    }

    private function createAttribute($data)
    {
        if (!empty($data["prod_attributes"])) {

            $product_id         = $data["id"] ?? $this->model->id;; //Get CURRENT PRODUCT ID
            $object             = $data["prod_attributes"];
            $row                = 0;
            $tot                = 0;
            $sku_increment      = 1;

            $row = 0;

            // DELETE ATTRIBUTES TO INSERT AGAIN
            ProductAttributes::where('product_id', $product_id)->delete();

            foreach ($object as $attributes) // EACH ATTRIBUTE
            {
                $row++;
                $tot = count($attributes); // TOTAL ATTRIBUTES

                if ($row == 1) // ONE ROW CONTAINS ALL ATTRIBUTES
                {
                    for ($i = 0; $i < $tot; $i++) // WHILE FOUND ATTRIBUTES
                    {
                        if (isset($attributes[$i])) {
                            if ($attributes[$i] != 0) {
                                $get_array = $attributes[$i];
                                $this->saveProductAttribute($product_id, $get_array["id"], $get_array["value"]);
                            }
                        }
                    }
                }
            }
        }
    }

    private function createFamily($data)
    {
        $new                = new Family();
        $new->name          = $data["name"];
        $new->description   = $data["description"];
        $new->is_enabled    = $data["is_enabled"];
        $new->brand_id      = $data["brand_id"];
        $new->location_id   = $data["location_id"];
        $new->category_id   = $data["category_id"];
        $new->stock_locator = $data["stock_locator"];
        $new->measure_id    = $data["measure_id"];
        $new->supplier_id   = $data["supplier_id"];
        $new->sku           = $data["sku"];
        $new->barcode       = $data["barcode"];
        $new->price         = $data["price"];
        $new->width         = $data["width"];
        $new->height        = $data["height"];
        $new->length        = $data["length"];
        $new->weight        = $data["weight"];
        $new->carton_width  = $data["carton_width"];
        $new->carton_height = $data["carton_height"];
        $new->carton_length = $data["carton_length"];
        $new->carton_weight = $data["carton_weight"];
        $new->launch_at     = $data["launch_at"];
        $new->is_enabled    = $data["is_enabled"];
        $new->save();

        return $new->id;
    }

    private function updateSuppliers($data) {
        $product_id         = $data['id'] ?? $this->model->id;; //Get CURRENT PRODUCT ID
        $suppliers = $data['suppliers'];
        $supplierLocations = $data['supplierLocations'];


        foreach ($suppliers as $supplier)
        {
            $supplierArray = [
                'product_id'    => $product_id,
                'supplier_id'   => $supplier['supplier_id'],
                'product_name'  => $supplier['product_name'],
                'product_sku'   => $supplier['product_sku'],
                'currency'      => $supplier['currency'],
                'last_price'    => $supplier['last_price'],
                'last_supplied' => $supplier['last_supplied'],
                'minimum_order' => $supplier['minimum_order']
            ];
            if(!empty($supplier['id'])) {
                $new = ProductSuppliers::updateOrCreate(['id' =>  $supplier['id']], $supplierArray);
            } else {
                $new = ProductSuppliers::updateOrCreate($supplierArray);
            }

            if(!empty($supplierLocations)) {

                foreach ($supplierLocations as $supplierLocation) {
                    if($supplierLocation['supplier_id'] == $new['supplier_id']) {
                        $supplierLocation['product_supplier_id'] = $new['id'];
                    }
                }
            }

        }
        if(!empty($supplierLocations)) {

            foreach ($supplierLocations as $supplierLocation) {
                if(empty($supplierLocation['product_supplier_id'])) {
                    $allSups = ProductSuppliers::where('product_id', $product_id)->get();
                    foreach ($allSups as $sup) {
                        if($sup->supplier->id == $supplierLocation['supplier_id']) {
                            $supplierLocation['product_supplier_id'] = $sup->id;
                        }
                    }


                }
                $supplierLocationsArray = [
                    'product_supplier_id'  => $supplierLocation['product_supplier_id'],
                    'location_id'          => $supplierLocation['location_id'],
                    'lead_time'            => $supplierLocation['lead_time'],
                    'safe_stock'           => $supplierLocation['safe_stock'],
                    'reorder_qty'          => $supplierLocation['reorder_qty'],
                ];

                if(!empty($supplierLocation['id'])) {
                    $new = ProductSupplierLocations::updateOrCreate(['id' =>  $supplierLocation['id']], $supplierLocationsArray);
                } else {
                    $new = ProductSupplierLocations::updateOrCreate($supplierLocationsArray);
                }
            }
        }
    }
    private function updateDiscounts($data) {
        $product_id         = $data['id'] ?? $this->model->id;; //Get CURRENT PRODUCT ID
        $discounts = $data['discounts'];


        foreach ($discounts as $discount)
        {
            $discountsArray = [
                'product_id'    => $product_id,
                'customer_id'   => $discount['customer_id'],
                'reason'        => $discount['reason'],
                'perc_value'    => $discount['perc_value'],
                'start_date'    => $discount['start_date'],
                'end_date'      => $discount['end_date']
            ];
            if(!empty($discount['id'])) {
                $new = CustomerDiscount::updateOrCreate(['id' =>  $discount['id']], $discountsArray);
            } else {
                $new = CustomerDiscount::updateOrCreate($discountsArray);
            }
        }
    }
    private function updateReorderLevels($data) {
        foreach($data["reorder_levels"] as $reorder_level) {

            $reorderLevelData = [
                'product_id'    => $this->model->id,
                'location_id'   => $reorder_level['location_id'],
                'safe_stock'    => $reorder_level['safe_stock'],
                'reorder_qty'   => $reorder_level['reorder_qty']
            ];

            if(!empty($reorder_level['id'])) {
                ProductReorderLevel::updateOrCreate(['id' =>  $reorder_level['id']], $reorderLevelData);
            } else {
                ProductReorderLevel::create($reorderLevelData);
            }
        }
    }

    private function updatePromos($data) {
        foreach($data["promos"] as $promo) {

            $promoData = [
                'product_id'            => $this->model->id,
                'discount_percentage'   => $promo['discount_percentage'] ?? 0,
                'buy_qty'               => $promo['buy_qty'] ?? 0,
                'get_qty'               => $promo['get_qty'] ?? 0,
                'date_from'             => $promo['date_from'],
                'date_to'               => $promo['date_to'],
                'gift_product_id'       => $promo['gift_product_id'] ?? $this->model->id
            ];

            if(!empty($promo['id'])) {
                ProductPromo::updateOrCreate(['id' =>  $promo['id']], $promoData);
            } else {
                ProductPromo::create($promoData);
            }
        }
    }

    private function updateCustomPrices($data) {
        foreach($data["custom_prices"] as $price) {

            $customPriceDate = [
                'product_id'            => $this->model->id,
                'customer_id'           => $price['customer_id'],
                'currency'              => strtoupper($price['currency']),
                'custom_price'          => $price['custom_price'] ?? 0,
                'is_enabled'            => $price['is_enabled'],
                'disabled_at'           => !$price['is_enabled'] ? now() : null,
            ];

            if(!empty($price['id'])) {
                ProductCustomPrice::updateOrCreate(['id' =>  $price['id']], $customPriceDate);
            } else {
                ProductCustomPrice::create($customPriceDate);
            }
        }
    }

    private function createDiscounts ($data)
    {

        $discounts = $data['discounts'];

        foreach ($discounts as $discount)
        {
            $new                = new CustomerDiscount();
            $new->product_id    = $this->model->id;
            $new->customer_id   = $discount["customer_id"];
            $new->reason        = $discount["reason"];
            $new->perc_value    = $discount["perc_value"];
            $new->start_date    = $discount["start_date"];
            $new->end_date      = $discount["end_date"];
            $new->save();
        }

        return $new->id;
    }
    private function createSuppliers($data)
    {
        $suppliers = $data['suppliers'];
        $supplierLocations = $data['supplierLocations'];
        foreach ($suppliers as $supplier)
        {
            $new                = new ProductSuppliers();
            $new->product_id    = $this->model->id;
            $new->product_name  = $supplier["product_name"];
            $new->product_sku   = $supplier["product_sku"];
            $new->supplier_id   = $supplier["supplier_id"];
            $new->currency      = $supplier["currency"];
            $new->last_price    = $supplier["last_price"];
            $new->last_supplied = $supplier["last_supplied"];
            $new->minimum_order = $supplier["minimum_order"];
            $new->save();
            if(!empty($supplierLocations)) {

                foreach ($supplierLocations as $supplierLocation) {
                    if($supplierLocation['supplier_id'] == $new['supplier_id']) {
                        $newLocation = new ProductSupplierLocations();
                        $newLocation->product_supplier_id = $new["id"];
                        $newLocation->location_id = $supplierLocation["location_id"];
                        $newLocation->lead_time = $supplierLocation["lead_time"];
                        $newLocation->safe_stock = $supplierLocation["safe_stock"];
                        $newLocation->reorder_qty = $supplierLocation["reorder_qty"];
                        $newLocation->save();
                    }
                }
            }

        }

        return $new->id;
    }

    private function saveProductAttribute($product_id, $attribute_id, $value)
    {
        // SAVE ATTRIBUTE BY PRODUCT
        ProductAttributes::updateOrCreate(
            [
                'product_id'    => $product_id,
                'attribute_id'  => $attribute_id,
            ],
            [
                'value' => $value
            ]
        );
    }

    public function generateFamily($data, $family_id = null)
    {
        if (isset($data["family_attributes"])) {

            $all_products_id    = [];
            $object             = $data["family_attributes"];
            $attrib             = array(array());
            $row                = 0;
            $tot                = 0;
            $sku_family         = $data["sku"];
            $product_name       = $data["name"];
            $sku_increment      = 1;
            $attributes_concat  = [];

            $row = 0;

            foreach ($object as $attributes) // EACH ATTRIBUTE
            {
                $row++;
                $tot = count($attributes); // TOTAL ATTRIBUTES

                if ($row == 1) // ONE ROW CONTAINS ALL ATTRIBUTES
                {
                    for ($i = 0; $i < $tot; $i++) // WHILE FOUND ATTRIBUTES
                    {
                        if (isset($attributes[$i])) {
                            if ($attributes[$i] != 0) {
                                $get_array = $attributes[$i];
                                // SAVING ALL FAMILY ATTRIBUTES
                            FamilyAttribute::updateOrCreate(['family_id' => $data["family_id"], 'attribute_id' => $get_array["id"], 'value' => $get_array["value"]]);

                            }
                        }
                    }
                }
            }

            $tot_attributes = FamilyAttribute::distinct('attribute_id')->count('attribute_id');
            $r              = $tot_attributes;
            $n              = sizeof($attributes_concat);
            $this->printCombination($attributes_concat, $n, $r);
            $my_array       = [];
            $my_array       = $this->result;

            /* EXAMPLE
            Array
            (
                [0] => 3|sour,2|50ml,1|50mg,
                [1] => 3|sour,2|50ml,1|20mg,
                [2] => 3|sour,2|50ml,1|35mg,
                [3] => 3|sour,2|30ml,1|50mg,
                [4] => 3|sour,2|30ml,1|20mg,
                [5] => 3|sour,2|30ml,1|35mg,
                [6] => 3|salt,2|50ml,1|50mg,
                [7] => 3|salt,2|50ml,1|20mg,
                [8] => 3|salt,2|50ml,1|35mg,
                [9] => 3|salt,2|30ml,1|50mg,
                [10] => 3|salt,2|30ml,1|20mg,
                [11] => 3|salt,2|30ml,1|35mg,
                )
            */

            // HERE WE NEED TO LOOP COMBINATIONS AND CREATE ONE PRODUCT BY ROW
            foreach ($my_array as $index => $element) {
                // CONCAT ATTRIBUTES TO SAVE ON PRODUCT NAME
                $concat_name = "";

                // NEW SKU
                $data["sku"] = $sku_family . " - " . $sku_increment;

                // INCREMENT FOR THE NEXT PRODUCT FAMILY
                $sku_increment++;

                // SET FAMILY ID FOR THIS PRODUCT
                $data["family_id"] = $family_id;

                // CREATE A NEW PRODUCT
                parent::store($data);

                //GETTING PRODUCT ID CREATED
                $new_product_id = $this->model->id;

                // SAVE ALL PRODUCTS CREATED
                array_push($all_products_id, $new_product_id);

                // [0] => 3|sour,2|50ml,1|50mg - EXAMPLE
                $attributes = explode(',', $element);

                // LOOP ROW OF ATTRIBUTES
                foreach ($attributes as $v) {
                    if (isset($v[0])) {
                        $get_array = explode('|', $v); // 3|sour 2|50ml 1|50mg - ALL ATTRIBUTES FOR ONE PRODUCT
                        $this->saveProductAttribute($new_product_id, $get_array[0], $get_array[1], $family_id); // CREATE NEW PRODUCT ATTRIBUTE
                        $concat_name .= " - " . $get_array[1];
                    }
                }

                // CONCAT PRODUCT NAME WITH ATTRIBUTE VALUE
                Product::where('id', $new_product_id)->update(['name' => $data["name"] . $concat_name]);
            }
        }
    }

    private function printCombination($arr, $n, $r)
    {
        // A temporary array to store all combination one by one
        $data = array();

        // save all combination using temporary array 'data[]'
        return $this->combinationUtil($arr, $n, $r, 0, $data, 0);
    }

    /*
        arr[]   ---> Input Array
        n       ---> Size of input array
        r       ---> Size of a combination to be saved
        index   ---> Current index in data[]
        data[]  ---> Temporary array to store current combination
        i       ---> index of current element in arr[]
    */
    private function combinationUtil($arr, $n, $r, $index, $data, $i)
    {
        $qt_elements    = 0;
        $comb           = "";

        // Current combination is ready, save it
        if ($index == $r) {
            for ($j = 0; $j < $r; $j++) {
                if (isset($data[$j - 1])) {
                    if (substr($data[$j], 0, 1) != substr($data[$j - 1], 0, 1)) {
                        $comb .= $data[$j] . ",";
                        $qt_elements++;
                    }
                } else {
                    $comb .= $data[$j] . ",";
                    $qt_elements++;
                }
            }

            // WE NEED THE COMPLETE COMBINATION (# r passed as parameter)
            if ($qt_elements == $r) {
                array_push($this->result, $comb);
            }

            return;
        }

        // When no more elements are there to put in data[]
        if ($i >= $n) {
            return;
        }

        // current is included, put next at next location
        $data[$index] = $arr[$i];
        $this->combinationUtil(
            $arr,
            $n,
            $r,
            $index + 1,
            $data,
            $i + 1
        );

        // Remove duplicates
        if (isset($arr[$i]) && isset($arr[$i + 1])) {
            while ($arr[$i] == $arr[$i + 1]) {
                $i++;
            }
        }

        // current is excluded, replace it with next (Note that i+1 is passed, but index is not changed)
        $this->combinationUtil($arr, $n, $r, $index, $data, $i + 1);
    }

    // USED TO LOAD PRODUCT AVAILABILITIES, STOCK TAKE AND PRODUCTS
    public function productAvailabilities(array $searchCriteria = [])
    {

        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 20;

        $this->queryBuilder->with('brand')
        ->with('category');

        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder->with(['availabilities' => function ($query) use($searchCriteria) {
                $query->where('location_id', $searchCriteria['location_id']);
            }]);
            unset($searchCriteria['location_id']);
        }

        return parent::findBy($searchCriteria);
    }
}
