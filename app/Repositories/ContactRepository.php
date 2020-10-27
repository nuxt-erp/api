<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class ContactRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])

    {
        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                      $query->where('email', 'ILIKE', $text)
                ->orWhere('name', 'ILIKE', $text);
            });
        }
        return parent::findBy($searchCriteria);
    }

}
