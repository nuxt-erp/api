<?php

namespace App\Http\Controllers;

use App\Models\CompanyModules;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Resources\UserResource;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use ResponseTrait;

    protected $repository;
    protected $resource;

    public function __construct(UserRepository $repository, UserResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function create(Request $request){
        //@todo check if company and email exists
        $this->repository->register($request->all());

        if($this->repository->model){
            return $this->setStatusCode(201)->send();
        }
        else{
            return $this->setStatus(FALSE)->send();
        }

    }

    public function installModules($name){
        //$user = auth()->user();
        $user = User::where('name', 'LIKE', '%'.$name.'%')->first();

        // run specific migration files for the schema

        // 1 - add modules to the company
        $modules = Module::all();
        foreach ($modules as $module){
            CompanyModules::updateOrCreate([
                'module_id'     => $module->id,
                'company_id'    => $user->company_id
            ]);
        }

        // 2 - run migrations and seeders for enabled modules

        DB::setDefaultConnection('tenant');
        config(['database.connections.tenant.schema' => $user->company->schema]);

        foreach ($modules as $module){
            Artisan::call('migrate', [
                '--path' => '/Modules/'.ucfirst($module->name).'/Database/Migrations/schema'
            ]);
            Artisan::call('module:seed '.ucfirst($module->name));

            echo $module->name . ' module registered. <br>';
        }

        // php artisan module:migrate Blog
        // php artisan module:seed Blog
    }

}
