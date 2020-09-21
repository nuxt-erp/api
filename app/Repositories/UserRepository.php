<?php

namespace App\Repositories;

use App\Models\UserRoles;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Artisan;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends RepositoryService
{

    public function register(array $data)
    {
        //@todo we should use transaction here, the request is cancelling at the end of the request and is taking too much time to finish

        $company = Company::where('name', $data['company'])->first();

        if(!$company){
            // generate unique name for the schema
            do {
                $company_name = implode('_', explode(' ', $data['company']));
                $schema = strtolower('s' . Str::random(7).'_'.substr($company_name, 0, 20));
            } while (Company::where('schema', $schema)->exists());

            // create schema for this company
            $result = DB::unprepared('CREATE SCHEMA ' . $schema . ' AUTHORIZATION '.config('database.connections.public.username', 'postgres').';');
            // save user
            $this->store($data);
            // create company
            $company = Company::create([
                'name'      => $data['company'],
                'schema'    => $schema,
                'owner_id'  => $this->model->id
            ]);
            $this->model->company_id = $company->id; //update company information for the user
            $this->model->save();
        }
        else{
            $result = TRUE;
            $schema = $company->schema;

            $user = User::where('email', $data['email'])->firstOrFail();

            $data['company_id'] = $company->id;
            $this->update($user, $data); // save user
            $company->owner_id = $this->model->id;
            $company->save();
        }

        if ($result) {

            DB::setDefaultConnection('tenant');
            config(['database.connections.tenant.schema' => $schema]);

            // run specific migration files for the schema
            Artisan::call('migrate', [
                '--path' => '/database/migrations/schema'
            ]);

            // add basic roles
            Artisan::call('db:seed', [
                '--class' => 'RoleSeeder'
            ]);

            // set user as admin
            $role_admin = Role::where('code', 'admin')->first();
            $this->model->roles()->sync([$role_admin->id]);
        }
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

        if (!empty($searchCriteria['role'])) {
            $this->queryBuilder->whereHas('roles', function (Builder $query) {
                $role = strtolower(Arr::pull($searchCriteria, 'role'));
                $query->where('code', $role);
            });
        }

        $user = auth()->user();
        if($user){
            $this->queryBuilder->where('company_id', $user->company_id);
        }


        return parent::findBy($searchCriteria);
    }

    public function store(array $data)
    {
        $user = auth()->user();
        if($user) {
            $data['company_id'] = $user->company_id;
        }

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
