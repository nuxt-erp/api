<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class ConfigRepository extends RepositoryService
{

    public function findOne($id)
    {
        return $this->model->first();
    }

    public function findBy(array $searchCriteria = [])
    {
        $this->queryBuilder->limit(1);
        return parent::findBy($searchCriteria);
    }

}
