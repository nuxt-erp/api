<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class ParameterTypeRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('value', 'ILIKE', $text)
                ->orWhere('description', 'ILIKE', $text);
            });
        }

        return parent::findBy($searchCriteria);
    }
}
