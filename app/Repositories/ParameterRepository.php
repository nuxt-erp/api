<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class ParameterRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        lad($searchCriteria);
        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('name', 'LIKE', $text)
                ->orWhere('value', 'LIKE', $text);
            });
        }
        
        if(!empty($searchCriteria['order_by'])) {
            $this->queryBuilder->orderBy('value', $searchCriteria['order_by']);
        }

        return parent::findBy($searchCriteria);
    }
}
