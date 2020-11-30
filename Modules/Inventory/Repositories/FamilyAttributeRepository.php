<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class FamilyAttributeRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        if(empty($searchCriteria['order_by'])){
            $searchCriteria['order_by'] = [
                'field'         => 'attribute_id',
                'direction'     => 'asc'
            ];
        }


        if (!empty($searchCriteria['value'])) {
            $value = '%' . Arr::pull($searchCriteria, 'value') . '%';
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['value'] = $value;
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        parent::store($data);
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
    }
    public function getAttributeValue(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 20;
       
        if (!empty($searchCriteria['id'])) {

            $this->queryBuilder->where('id', $searchCriteria['id']);

            unset($searchCriteria['id']);
        }
        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder->with(['availabilities' => function ($query) use($searchCriteria) {
                $query->where('location_id', $searchCriteria['location_id']);
            }]);
            unset($searchCriteria['location_id']);
        }
        return parent::findBy($searchCriteria);

    }
   /* public function getAttributeValue($family_id,$attribute_id)
    {
      
       
        if (!empty($family_id)) {

            $this->queryBuilder->where('family_id', $family_id);

        }
        if (!empty($attribute_id)) {
            $this->queryBuilder->where('attribute_id ',$attribute_id);
        }
        return $this->queryBuilder->get();


    }*/
    
}
