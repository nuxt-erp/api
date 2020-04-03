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
        $this->generate($data); // GENERATE PRODUCT FAMILY
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
        $this->generate($data); // GENERATE PRODUCT FAMILY
    }

    public function generate($data)
    {
        $product_id = $this->model->id;
        $object     = $data["prod_attributes"];
        $attrib     = array(array());
        $row        = 0;
        $col        = 0;
        $main       = [];
        $tot        = 0;
        $generate   = false;

        // REMOVE ALL TO INSERT AGAIN
        ProductAttribute::where('product_id', $product_id)->delete();

        foreach ($object as $key => $attributes)
        {
            $tot = count($attributes);

            for ($i=0; $i < $tot; $i++)
            {
                if (isset($attributes[$i]))
                {
                    if ($attributes[$i]!=0) {
                        $get_array          = $attributes[$i];
                        ProductAttribute::updateOrCreate([
                            'product_id'    => $product_id,
                            'attribute_id'  => $get_array["id"],
                        ],
                        [
                            'value' => $get_array["value"]
                        ]);
                        // $attrib["id"][$i] = $get_array["id"];
                        // $attrib["value"][$i] = $get_array["value"];
                    }
                }
            }
        }

        if ($generate)
        {
            $tmp        = $attrib["id"];
            $tmp_value  = $attrib["value"];
            $insert     = [];
            for ($i=0; $i < count($attrib["id"]); $i++)
            {
                if ($i==0) { // FIRST INSERT
                    $insert[$i]  = $attrib["id"][$i] . "|" . $tmp_value["value"][$i];
                }
            }
        }
    }

}
