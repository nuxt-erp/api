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
                $query->where('name', 'LIKE', $text)
                ->orWhere('value', 'LIKE', $text);
            });
        }

        return parent::findBy($searchCriteria);
    }
}
