<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use App\Models\ProductAttribute;

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
        $data["company_id"] = Auth::user()->company_id;
        parent::store($data);
        $this->createAttribute($data); // SAVE PRODUCT ATTRIBUTES
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
        $this->createAttribute($data); // SAVE PRODUCT ATTRIBUTES
    }

    function get_combinations($arrays) {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_key => $property_value) {
                    $tmp[] = $result_item + array($property_key => $property_value);
                }
            }
            $result = $tmp;
        }
        return $result;
    }


    public function createAttribute($data)
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
                                        parent::store($data); // CREATE A NEW PRODUCT
                                        $new_product_id = $this->model->id; //GETTING PRODUCT ID CREATED

                                        array_push($all_products_id, $new_product_id); // SAVE ALL PRODUCTS CREATED
                                        $ids .= ", " . $new_product_id; //SAVING BY COMMA ALL PRODUCT IDS

                                        ProductAttribute::updateOrCreate([
                                            'product_id'    => $new_product_id,
                                            'attribute_id'  => $get_array["id"],
                                        ],
                                        [
                                            'value' => $get_array["value"]
                                        ]);
                                    }
                                    else
                                    {
                                        ProductAttribute::updateOrCreate([
                                            'product_id'    => $product_id,
                                            'attribute_id'  => $get_array["id"],
                                        ],
                                        [
                                            'value' => $get_array["value"]
                                        ]);
                                    }

                                }
                            } else { // CREATING PRODUCT ATTRIBUTE
                                ProductAttribute::updateOrCreate([
                                    'product_id'    => $product_id,
                                    'attribute_id'  => $get_array["id"],
                                ],
                                [
                                    'value' => $get_array["value"]
                                ]);
                            }

                        }
                    }

                }
            }
        }

        // CREATE PRODUCT FAMILY
        if ($generate)
        {
            $sku_family = $data["sku"];
            $sku_increment = 1;

            // FIND MAIN PRODUCT (WITH MORE ATTRIBUTES SAVED)
            $main_prod = \DB::select('SELECT product_id, count(attribute_id) as tot FROM product_attributes WHERE product_id IN (' . $ids .') GROUP BY product_id ORDER BY 2 DESC LIMIT 1');
            if ($main_prod)
            {
                foreach ($all_products_id as $key => $value) // EACH ALL PRODUCTS CREATED
                {
                    if ($value != $main_prod[0]->product_id) // WE DON'T NEED THE MAIN PRODUCT
                    {
                        // GET THE ATTRIBUTES FROM THE MAIN PRODUCT - WE NEED TO CHECK WHICH ATTRIBUTE THE CHILD NEED INHERANCE FROM PARENT
                        $child = $value;
                        $sQuery = 'SELECT attribute_id, value FROM product_attributes WHERE product_id = ' . $main_prod[0]->product_id . ' and attribute_id NOT IN (SELECT attribute_id FROM product_attributes WHERE product_id = ' . $child . ')';
                        $get_attributes = \DB::select($sQuery);

                        foreach ($get_attributes as $v) // EACH ATTRIBUTE FROM THE PARENT
                        {
                            // CREATE NEW ATTRIBUTE FOR CHILD
                            ProductAttribute::updateOrCreate([
                                'product_id'    => $child,
                                'attribute_id'  => $v->attribute_id,
                            ],
                            [
                                'value' => $v->value
                            ]);
                        }
                    }
                }
            }

            // FIND PRODUCTS WITH SAME ATTRIBUTE BUT DIFF VALUES
            $main_prod = \DB::select('SELECT product_id, count(attribute_id) as tot FROM product_attributes WHERE product_id IN (' . $ids .') GROUP BY product_id ORDER BY 2 DESC LIMIT 1');
            if ($main_prod)
            {
                // GET THE ATTRIBUTES FROM THE MAIN PRODUCT
                $sQuery = 'SELECT attribute_id, value FROM product_attributes WHERE product_id in (' . $ids . ') and value not in (select value from product_attributes where product_id = ' . $main_prod[0]->product_id . ')';
                $get_attributes = \DB::select($sQuery);

                parent::store($data); // CREATE A NEW PRODUCT
                $new_product_id = $this->model->id;

                foreach ($get_attributes as $v)
                {
                    // CREATE NEW ATTRIBUTE FOR CHILD
                    ProductAttribute::updateOrCreate([
                        'product_id'    => $new_product_id,
                        'attribute_id'  => $v->attribute_id,
                    ],
                    [
                        'value' => $v->value
                    ]);
                }

                // FINAL STEP - FIND MISSING ATTRIBUTES FOR THE NEW PRODUCT CREATED
                $sQuery = 'SELECT DISTINCT a1.attribute_id, a2.product_id, a1.value
                FROM product_attributes AS a1
                CROSS JOIN product_attributes as a2
                WHERE a1.product_id IN (' . $ids . ') AND a1.attribute_id not in (select attribute_id from product_attributes where product_id = a2.product_id )';
                $get_attributes = \DB::select($sQuery);

                foreach ($get_attributes as $v)
                {
                    // CREATE NEW ATTRIBUTE FOR CHILD
                    ProductAttribute::updateOrCreate([
                        'product_id'    => $new_product_id,
                        'attribute_id'  => $v->attribute_id,
                    ],
                    [
                        'value' => $v->value
                    ]);
                }
            }
        }


    }

}
