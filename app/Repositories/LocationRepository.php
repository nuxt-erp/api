<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class LocationRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['list']))
        {
            $this->queryBuilder->where('is_enabled', true);
        }
        
        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('short_name', 'LIKE', $text)
                ->orWhere('name', 'LIKE', $text);
            });
        }

        return parent::findBy($searchCriteria);
    }
}
