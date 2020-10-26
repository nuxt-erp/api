<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class CustomerRepository extends RepositoryService
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
    // public function store(array $data)
    // {
    //     DB::transaction(function () use ($data)
    //     {
    //         parent::store($data);   
    //     });

    // }
    // public function update($model, array $data)
    // {
    //     DB::transaction(function () use ($data, $model)
    //     {
    //         parent::update($model, $data);
    //     });
    // }

}
