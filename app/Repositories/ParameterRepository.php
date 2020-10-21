<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class ParameterRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('name', 'ILIKE', $text)
                ->orWhere('value', 'ILIKE', $text);
            });
        }        

        return parent::findBy($searchCriteria);
    }
}
