<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserRepository extends RepositoryService
{

    public function register(array $data)
    {
        DB::transaction(function () use ($data) {

            // generate unique name for the schema
            do {
                $company = implode('_', explode(' ', $data['company']));
                $hash = 's' . Str::random(7).'_'.substr($company, 0, 20);
            } while (Company::where('schema', $hash)->exists());

            // create schema for this company
            $result = DB::unprepared('CREATE SCHEMA ' . $hash . ' AUTHORIZATION '.config('database.connections.public.username', 'postgres').';');

            if ($result) {
                $data['roles'] = Role::where('code', 'admin')->get(); // set user as admin
                $this->store($data); // save user
                $company = Company::create([
                    'owner_id'  => $this->model->id,
                    'name'      => $data['company'],
                    'schema'    => $hash
                ]);
                $this->model->company_id = $company->id; //update company information for the user
                $this->model->save();
            }

        });
    }

    public function findBy(array $searchCriteria = [])
    {

        if (empty($searchCriteria['order_by'])) {
            $searchCriteria['order_by']['field']        = 'name';
            $searchCriteria['order_by']['direction']    = 'asc';
        }

        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use ($text) {
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
