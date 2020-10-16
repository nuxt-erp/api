<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class RoleRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('code', 'ILIKE', $text)
                ->orWhere('name', 'ILIKE', $text);
            });
        }

        return parent::findBy($searchCriteria);
    }

}
