<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Auth;

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
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
        $this->generate($data); // GENERATE PRODUCT FAMILY
    }

    // GENERATE FAMILY PRODUCT
    /*
    attribute_id product_id value
    1 - size        1        30ml
    1 - size        1        50ml
    3 - color       1        white
    4 - variation   1        salt

    prod 1 30ml white salt
    prod 1 50 ml white salt
    */
    public function generate($data)
    {

        $object = $data["prod_attributes"];
        $attrib = array(array());
        $row    = 0;
        $col    = 0;
        $main   = [];
        $tot    = 0;

        foreach ($object as $key => $attributes)
        {
            echo "count geral: " . count($attributes) . "\n";
            $tot = count($attributes);

            for ($i=0; $i < $tot; $i++)
            {
                if (isset($attributes[$i]))
                {
                    $get_array = $attributes[$i];
                    $attrib["id"][$i] = $get_array["id"];
                    $attrib["value"][$i] = $get_array["value"];
                }
            }

            // FAMILY DETAILS
            if (isset($attributes["generate"]))
            {
                $generate = $attributes["generate"];
            }

            if (isset($attributes["family"]))
            {
                $family_name  = $attributes["family"];
            }

        }

        if ($generate)
        {
            // LOOP INTO $data, check same attribute_id, if found create combinations with all other attributes
            print_r($attrib);

            $tmp        = $attrib["id"];
            $tmp_value  = $attrib["value"];
            $insert     = [];
            for ($i=0; $i < count($attrib["id"]); $i++)
            {
                if ($i==0) { // FIRST INSERT
                    $insert[$i]  = $attrib["id"][$i] . "|" . $tmp_value["value"][$i];
                }
            }

            /*
            $result = array(array());
            foreach ($attrib as $property => $property_values)
            {
                $tmp = array();
                foreach ($result as $result_item)
                {
                    foreach ($property_values as $property_value)
                    {
                        $tmp[] = array_merge($result_item, array($property => $property_value));
                    }
                }
                $result = $tmp;
            }
            */

        }


    }

}
