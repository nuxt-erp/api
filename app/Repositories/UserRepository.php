<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        if (!empty($searchCriteria['email'])) {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['email'] = '%' . $searchCriteria['email'] . '%';
        }

        if (!empty($searchCriteria['name'])) {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        return parent::findBy($searchCriteria);
    }

    public function store(array $data)
    {

        $data['password']   = bcrypt($data['password']);
        parent::store($data);

        if (Arr::has($data, 'roles')) {
            $this->model->roles()->sync($data['roles']);
        }
    }

    public function update($model, array $data)
    {
        if (Arr::has($data, 'password')) {
            if ($data['password'] == '[keep password]' || empty($data['password'])) {
                // keep the original password if is default accounts or is a default password value
                unset($data['password']);
            } else {
                // encrypt the password
                $data['password'] = bcrypt($data['password']);
            }
        }
        parent::update($model, $data);

        if (Arr::has($data, 'roles')) {
            $this->model->roles()->sync($data['roles']);
        }
    }
}
