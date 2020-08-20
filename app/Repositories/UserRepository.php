<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class UserRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        if(empty($searchCriteria['order_by'])){
            $searchCriteria['order_by']['field']        = 'name';
            $searchCriteria['order_by']['direction']    = 'asc';
        }

        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('email', 'LIKE', $text)
                ->orWhere('name', 'LIKE', $text);
            });
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
                // keep the original password if is a default password value
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
