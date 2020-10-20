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
        //$user = auth()->user(); // this is the right option after we make a page to install modules
        $user = User::where('name', 'ILIKE', '%'.$name.'%')->first();

        // run specific migration files for the schema

        // 1 - add modules to the company
        $modules = Module::all();
        foreach ($modules as $module){
            //@todo change here to add only selected modules [this must come from the frontend]
            CompanyModules::updateOrCreate([
                'module_id'     => $module->id,
                'company_id'    => $user->company_id
            ]);
            echo $module->name . ' module added for: '.$user->company->name.'. <br>';
        }

        echo '----- <br>';

        // 2 - run migrations and seeders for enabled modules

        DB::setDefaultConnection('tenant');
        config(['database.connections.tenant.schema' => $user->company->schema]); // we can remove this after we have a logged user

        foreach ($modules as $module){
            try {
                Artisan::call('migrate', [
                    '--path' => '/Modules/'.ucfirst($module->name).'/Database/Migrations/schema'
                ]);
                echo $module->name . ' migration done. <br>';
            } catch (\Throwable $th) {
                echo 'Migration error for '.ucfirst($module->name).'!<br>';
                dump(Artisan::output());
                echo '<br>';
                echo $th->getMessage();
                echo '<br>';
            }
            try {
                Artisan::call('module:seed '.ucfirst($module->name));
                echo $module->name . ' seeder done. <br>';
            } catch (\Throwable $th) {
                echo 'Seed error for '.ucfirst($module->name).'!<br>';
                dump(Artisan::output());
                echo '<br>';
                echo $th->getMessage();
            }
            echo '----- <br>';
        }
    }

}
