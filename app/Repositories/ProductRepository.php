<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use App\Models\ProductAttribute;
use App\Models\ProductFamily;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository extends RepositoryService
{

    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if(!empty($searchCriteria['category_name'])){
            $category = Arr::pull($searchCriteria, 'category_name');
            $this->queryBuilder->whereHas('category', function ($query) use ($category) {
                $query->where('product_categories.name', $category);
            });
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder
            ->where('name', 'LIKE', $name)
            ->orWhere('sku', 'LIKE', $name);
        }

        if (!empty($searchCriteria['sku'])) {
            $this->queryBuilder
            ->where('sku', 'LIKE', '%' . Arr::pull($searchCriteria, 'sku') . '%');
        }

        return parent::getList($searchCriteria);
    }

    public function findBy(array $searchCriteria = [])
    {

        if (!empty($searchCriteria['sku'])) {
            $sku = '%' . Arr::pull($searchCriteria, 'sku') . '%';
            $this->queryBuilder
            ->where('sku', 'LIKE', $sku)
            ->orWhere('name', 'LIKE', $sku);
        }

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
            $searchCriteria['sku'] = $name;
        }

        if(Arr::has($searchCriteria, 'complete_name')){
            $searchCriteria['query_type']   = 'LIKE';
            $searchCriteria['where']        = 'OR';
            $name = Arr::pull($searchCriteria, 'complete_name');
            $searchCriteria['sku']          = '%' . $name . '%';
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $data["company_id"] = Auth::user()->company_id; // COMPANY ID FROM THE CURRENT USER LOGGED
            $family_id = null;

            if ($data["generate_family"]) // IT CAMES FROM PRODUCT FAMILY
            {
                $family_id = $this->createFamily($data);
            }

            $data["family_id"] = $family_id;
            parent::store($data);

            $this->createAttribute($data, $family_id); // SAVE PRODUCT ATTRIBUTES
        });

    }

    private function createFamily($data)
    {
        $new                = new ProductFamily;
        $new->name          = $data["name"];
        $new->description   = $data["description"];
        $new->status        = $data["status"];
        $new->brand_id      = $data["brand_id"];
        $new->category_id   = $data["category_id"];
        $new->supplier_id   = $data["supplier_id"];
        $new->company_id    = $data["company_id"];
        $new->sku           = $data["sku"];
        $new->location_id   = $data["location_id"];
        $new->launch_date   = $data["launch_date"];
        $new->save();
        return $new->id;
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
        $this->createAttribute($data); // SAVE PRODUCT ATTRIBUTES
    }

    private function saveProductAttribute($product_id, $attribute_id, $value)
    {
        ProductAttribute::updateOrCreate([
            'product_id'    => $product_id,
            'attribute_id'  => $attribute_id,
        ],
        [
            'value' => $value
        ]);
    }

    public function createAttribute($data, $family_id = null)
    {
        $product_id         = $this->model->id; //SAVE CURRENT PRODUCT ID
        $all_products_id    = [];
        $ids                = $product_id;
        $object             = $data["prod_attributes"];
        $attrib             = array(array());
        $row                = 0;
        $tot                = 0;
        $generate           = $data["generate_family"];
        $sku_family         = $data["sku"];
        $product_name       = $data["name"];
        $sku_increment      = 1;
        array_push($all_products_id, $product_id);

        // REMOVE ALL TO INSERT AGAIN
        ProductAttribute::where('product_id', $product_id)->delete();
        $row=0;

        foreach ($object as $attributes) // EACH ATTRIBUTE
        {
            $row++;
            $tot = count($attributes);

            if ($row==1)
            {
                for ($i=0; $i < $tot; $i++)
                {
                    if (isset($attributes[$i]))
                    {
                        if ($attributes[$i]!=0)
                        {
                            $get_array = $attributes[$i];

                            // FIND IF PRODUCT ALREADY HAS THE ATTRIBUTE
                            $find = ProductAttribute::where(['product_id' => $product_id, 'attribute_id' => $get_array["id"]])->select('value')->first();

                            if ($find) { //FOUND THIS ATTRIBUTE FOR THE PRODUCT
                                if ($find->value != $get_array["value"]) {  // SAME ATTRIBUTE, BUT DIFFERENT VALUE. CREATE A NEW PRODUCT

                                    // CREATE PRODUCT FAMILY
                                    if ($generate)
                                    {
                                        $data["sku"] = $sku_family . " - " . $sku_increment;  // NEW SKU
                                        $sku_increment++; // INCREMENT FOR THE NEXT PRODUCT FAMILY
                                        $data["family_id"] = $family_id; // SET FAMILY ID FOR THIS PRODUCT
                                        parent::store($data); // CREATE A NEW PRODUCT
                                        $new_product_id = $this->model->id; //GETTING PRODUCT ID CREATED

                                        array_push($all_products_id, $new_product_id); // SAVE ALL PRODUCTS CREATED
                                        $ids .= ", " . $new_product_id; //SAVING BY COMMA ALL PRODUCT IDS

                                        $this->saveProductAttribute($new_product_id, $get_array["id"], $get_array["value"]); // CREATE NEW PRODUCT ATTRIBUTE
                                    }
                                    else
                                    {
                                        $this->saveProductAttribute($product_id, $get_array["id"], $get_array["value"]);
                                    }

                                }
                            } else { // CREATING PRODUCT ATTRIBUTE
                                $this->saveProductAttribute($product_id, $get_array["id"], $get_array["value"]);
                            }

                        }
                    }

                }
            }
        }

        // CREATE PRODUCT FAMILY
        if ($generate)
        {
            // FIND MAIN PRODUCT (WITH MORE ATTRIBUTES SAVED)
            $main_prod = DB::select('SELECT product_id, count(attribute_id) as tot FROM product_attributes WHERE product_id IN (' . $ids .') GROUP BY product_id ORDER BY 2 DESC LIMIT 1');
            if ($main_prod)
            {
                foreach ($all_products_id as $key => $value) // EACH ALL PRODUCTS CREATED
                {
                    if ($value != $main_prod[0]->product_id) // WE DON'T NEED THE MAIN PRODUCT
                    {
                        // GET THE ATTRIBUTES FROM THE MAIN PRODUCT - WE NEED TO CHECK WHICH ATTRIBUTE THE CHILD NEED INHERANCE FROM PARENT
                        $child = $value;
                        $sQuery = 'SELECT attribute_id, value FROM product_attributes WHERE product_id = ' . $main_prod[0]->product_id . ' and attribute_id NOT IN (SELECT attribute_id FROM product_attributes WHERE product_id = ' . $child . ')';
                        $get_attributes = DB::select($sQuery);

                        foreach ($get_attributes as $v) // EACH ATTRIBUTE FROM THE PARENT
                        {
                            // CREATE NEW ATTRIBUTE FOR CHILD
                            $this->saveProductAttribute($child, $v->attribute_id, $v->value);
                        }
                    }
                }
            }

            // FIND PRODUCTS WITH SAME ATTRIBUTE BUT DIFF VALUES
            $main_prod = DB::select('SELECT product_id, count(attribute_id) as tot FROM product_attributes WHERE product_id IN (' . $ids .') GROUP BY product_id ORDER BY 2 DESC LIMIT 1');
            if ($main_prod)
            {
                // GET THE ATTRIBUTES FROM THE MAIN PRODUCT
                $sQuery = 'SELECT attribute_id, value FROM product_attributes WHERE product_id in (' . $ids . ') and value not in (SELECT value FROM product_attributes WHERE product_id = ' . $main_prod[0]->product_id . ')';
                $get_attributes = DB::select($sQuery);

                $data["sku"]  = $sku_family . " - " . $sku_increment;  // NEW SKU
                $sku_increment++; // INCREMENT FOR THE NEXT PRODUCT FAMILY
                $data["family_id"] = $family_id; // SET FAMILY ID FOR THIS PRODUCT

                parent::store($data); // CREATE A NEW PRODUCT
                $new_product_id = $this->model->id;

                array_push($all_products_id, $new_product_id); // SAVE ALL PRODUCTS CREATED
                $ids .= ", " . $new_product_id; //SAVING BY COMMA ALL PRODUCT IDS

                foreach ($get_attributes as $v)
                {
                    // CREATE NEW ATTRIBUTE FOR CHILD
                    $this->saveProductAttribute($new_product_id, $v->attribute_id, $v->value);
                }

                // FINAL STEP - FIND MISSING ATTRIBUTES FOR THE NEW PRODUCT CREATED
                $sQuery = 'SELECT DISTINCT a1.attribute_id, a2.product_id, a1.value
                FROM product_attributes AS a1
                CROSS JOIN product_attributes as a2
                WHERE a1.product_id IN (' . $ids . ') AND a1.attribute_id not in (SELECT attribute_id FROM product_attributes WHERE product_id = a2.product_id )';
                $get_attributes = DB::select($sQuery);

                foreach ($get_attributes as $v)
                {
                    // CREATE NEW ATTRIBUTE FOR CHILD
                    $this->saveProductAttribute($new_product_id, $v->attribute_id, $v->value);
                }
            }

            // CONCAT PRODUCT NAME WITH ATTRIBUTE VALUE
            $read            = DB::select('SELECT product_id, attribute_id, value, p.name FROM product_attributes pa INNER JOIN products p ON p.id = pa.product_id WHERE product_id IN (' . $ids .') ORDER BY product_id, attribute_id');
            $new_name        = "";
            $product_control = "";

            foreach ($read as $v)
            {
                Product::where('id', $v->product_id)->update(['name' => ($new_name=="" ? ($v->name . " - " . $v->value) : ($new_name . " - " . $v->value))]);

                // RESET WHEN CHANGE PRODUCT
                if ($product_control!="" || $product_control != $v->product_id) {
                    $new_name = "";
                    $product_control = $v->product_id;
                }

                if ($new_name=="") { // CONCAT NAME + ATTRIBUTE VALUE
                    $new_name = $v->name . " - " . $v->value;
                } else {
                    $new_name = $new_name . " - " . $v->value;
                }
            }
        }

    }

}
