<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\FamilyAttribute;
use Modules\Inventory\Entities\Family;
use Modules\Inventory\Entities\ProductAttributes;


class FamilyRepository extends RepositoryService
{
    private $result = [];
    private $generate = false; // GENERATE PRODUCT FAMILY

    public function getListProducts($id)
    {
        $searchCriteria =Array();
        $searchCriteria['order_by'] = [
            'field'         => 'attribute_id',
            'direction'     => 'asc'
        ];
        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['list']))
        {
            $this->queryBuilder->where('is_enabled', true);
        }

        $data = [];
        if (isset($id)) {
            $data = Product::where('family_id', $id)->get();
        }

        return $data;
    }


    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['sku'])) {
            $sku = '%' . Arr::pull($searchCriteria, 'sku') . '%';
            $this->queryBuilder
                ->where('sku', 'ILIKE', $sku)
                ->orWhere('name', 'ILIKE', $sku);
        }

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
                ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['category_name'])) {
            $category = Arr::pull($searchCriteria, 'category_name');
            $this->queryBuilder->whereHas('category', function ($query) use ($category) {
                $query->where('product_categories.name', $category);
            });
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
                ->where('category_id', $searchCriteria['category_id']);
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
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
        }

        return parent::findBy($searchCriteria);
    }
    public function store($data)
    {
        DB::transaction(function () use ($data) {
            $data['family_id'] = $this->createFamily($data); // FIRST WE CREATE THE FAMILY
            $this->generateFamily($data, $data['family_id']); // GENERATE FAMILY

        });

    }
    public function update($model, array $data)
    {
        parent::update($model,$data);
        $this->createAttribute($data);
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
            $my_array=$object;

            $row = 0;
            lad($object[0]);

            if(isset($object[0][0]->columns)){
                foreach ($object as $attributes) // EACH ATTRIBUTE
                {
                    lad($attributes);
                    foreach ($attributes as $attribute) // EACH ATTRIBUTE
                    {
                        foreach ($attribute->columns as $columns) // EACH ATTRIBUTE
                    {
                        if ($columns->value != 0) {
                            $get_array = $columns->value;
                            // SAVING ALL FAMILY ATTRIBUTES
                            FamilyAttribute::updateOrCreate(['family_id' => $data["family_id"], 'attribute_id' => $columns->id, 'value' => $columns->value]);
                           // $attributes_concat[$i] = $get_array["id"] . "|" . $get_array["value"];

                        }
                    }
                    }


                }

            }else{
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
                                $attributes_concat[$i] = $get_array["id"] . "|" . $get_array["value"];
    
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
    


            }
            

            lad($my_array);
            foreach ($my_array as $index => $element) {
                // CONCAT ATTRIBUTES TO SAVE ON PRODUCT NAME
                $concat_name = "";

                // NEW SKU 
                if(isset($element->sku)){
                    $data["sku"]=$element->sku;
                }else{
                    $data["sku"] = $sku_family . " - " . $sku_increment;

                    // INCREMENT FOR THE NEXT PRODUCT FAMILY
                    $sku_increment++;
                }
                

                // SET FAMILY ID FOR THIS PRODUCT
                $data["family_id"] = $family_id;

                // CREATE A NEW PRODUCT
               // parent::store($data);
               $new                = new Product();
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
               $new->barcode       = rand();
               $new->family_id     = $family_id;
               $new->sales_channel = 1;
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
                //GETTING PRODUCT ID CREATED
                $new_product_id = $new->id;

                // SAVE ALL PRODUCTS CREATED
                array_push($all_products_id, $new_product_id);

                // [0] => 3|sour,2|50ml,1|50mg - EXAMPLE
                $attributes = explode(',', $element);

                // LOOP ROW OF ATTRIBUTES
                lad($element->columns);
                if(isset($element->columns)){
                    foreach ($element->columns as $v) {
                        if (isset($v->value)) {
                            $get_array = explode('|', $v); // 3|sour 2|50ml 1|50mg - ALL ATTRIBUTES FOR ONE PRODUCT
                            $this->saveProductAttribute($new_product_id, $v->id, $v->name, $family_id); // CREATE NEW PRODUCT ATTRIBUTE
                            $concat_name .= " - " . $v->name;
                        }
                    }
                }else{
                    foreach ($attributes as $v) {
                        if (isset($v[0])) {
                            $get_array = explode('|', $v); // 3|sour 2|50ml 1|50mg - ALL ATTRIBUTES FOR ONE PRODUCT
                            $this->saveProductAttribute($new_product_id, $get_array[0], $get_array[1], $family_id); // CREATE NEW PRODUCT ATTRIBUTE
                            $concat_name .= " - " . $get_array[1];
                        }
                    }
                }
                

                // CONCAT PRODUCT NAME WITH ATTRIBUTE VALUE
                Product::where('id', $new_product_id)->update(['name' => $data["name"] . $concat_name]);
            }
        }
    }
    private function saveProductAttribute($product_id, $attribute_id, $value)
    {
        // SAVE ATTRIBUTE BY PRODUCT
        $d=ProductAttributes::updateOrCreate(
            [
                'product_id'    => $product_id,
                'attribute_id'  => $attribute_id,
            ],
            [
                'value' => $value
            ]
        );
        lad($attribute_id);

        lad($value);

    }

    private function printCombination($arr, $n, $r)
    {
        // A temporary array to store all combination one by one
        $data = array();

        // save all combination using temprary array 'data[]'
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

        // Current cobination is ready, save it
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
    private function createAttribute($data)
    {
        if (!empty($data["family_attributes"])) {

            $family_id         = $data["id"] ?? $this->model->id;; //Get CURRENT PRODUCT ID
            $object             = $data["family_attributes"];
            $row                = 0;
            $tot                = 0;
            $sku_increment      = 1;
            $row = 0;
            $all_products_id    = [];

            $attrib             = array(array());

            $sku_family         = $data["sku"];
            $product_name       = $data["name"];
            $sku_increment      = 1;
            $attributes_concat  = [];
            // DELETE ATTRIBUTES TO INSERT AGAIN
            FamilyAttribute::where('family_id', $family_id)->delete();
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
                            $attributes_concat[$i] = $get_array["id"] . "|" . $get_array["value"];

                            }
                        }
                    }
                }
            }
          /*  $tot_attributes = FamilyAttribute::distinct('attribute_id')->count('attribute_id');
            $r              = $tot_attributes;

            $n              = sizeof($attributes_concat);

            $this->printCombination($attributes_concat, $n, $r);
            $my_array       = [];
            $my_array       = $this->result;

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
               // parent::store($data);
               $new                = new Product();
               $new->name          = $data["name"];
               $new->description   = $data["description"];
               $new->is_enabled    = $data["is_enabled"];
               $new->brand_id      = $data["brand_id"];
               $new->location_id   = $data["location_id"];
               $new->category_id   = $data["category_id"];
               $new->stock_locator = $data["stock_locator"];
               $new->measure_id    = $data["measure_id"];
               $new->supplier_id   = $data["supplier_id"];
               $new->sku           = rand();
               $new->barcode       = rand();
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
                //GETTING PRODUCT ID CREATED
                $new_product_id = $new->id;
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
            }  */
        }
    }
    private function saveFamilyAttribute($family_id, $attribute_id, $value)
    {
        // SAVE ATTRIBUTE BY PRODUCT
        FamilyAttribute::updateOrCreate(
            [
                'family_id'    => $family_id,
                'attribute_id'  => $attribute_id,
            ],
            [
                'value' => $value
            ]
        );
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            Product::where('family_id', $id)->delete();
            parent::delete($id);
        });
    }
}
