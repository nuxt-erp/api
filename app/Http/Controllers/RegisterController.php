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

    private function artisanResult(){
        echo implode('<br>', explode(PHP_EOL, Artisan::output()));
    }

    public function installModules($name){

        DB::setDefaultConnection('public');

        //$user = auth()->user(); // this is the right option after we make a page to install modules
        $user = User::where('name', 'ILIKE', '%'.$name.'%')->orWhere('email', 'ILIKE', '%'.$name.'%')->first();
        echo '<<<<<<<<< SCRIPT START >>>>>>>><br><br>';

        echo 'Company: '.$user->company->name.'<br><br>';

        echo '----- <br>';
        try {
            echo 'Root migration: <br>';
            Artisan::call('migrate', [
                '--force' => true,
            ]);

            $this->artisanResult();
        } catch (\Throwable $th) {
            echo 'Root migration error!<br>';
            $this->artisanResult();
            echo $th->getMessage();
        }
        echo '----- <br>';

        try {
            echo 'Root seeder: <br>';
            Artisan::call('db:seed', [
                '--force' => true,
            ]);
            $this->artisanResult();
        } catch (\Throwable $th) {
            echo 'Root seeder error!<br>';
            $this->artisanResult();
            echo $th->getMessage();
        }
        echo '----- <br>';

        // run specific migration files for the schema

        // 1 - add modules to the company
        $modules = Module::all();

        if(count($user->company->modules) == 0){
            echo 'Adding modules to the company: <br><br>';
            foreach ($modules as $module){
                //@todo change here to add only selected modules [this must come from the frontend]
                CompanyModules::updateOrCreate([
                    'module_id'     => $module->id,
                    'company_id'    => $user->company_id
                ]);
                echo $module->name . ' module added for: '.$user->company->name.'. <br>';
            }
            echo '----- <br>';
        }


        // 2 - run migrations and seeders for enabled modules

        DB::setDefaultConnection('tenant');
        config(['database.connections.tenant.schema' => $user->company->schema]); // we can remove this after we have a logged user

        try {
            echo 'Tenant Root migration: <br>';
            Artisan::call('migrate', [
                '--path' => '/database/migrations/schema',
                '--force' => true,
            ]);
            $this->artisanResult();
        } catch (\Throwable $th) {
            echo 'Tenant Root migration error!<br>';
            $this->artisanResult();
            echo $th->getMessage();
        }
        echo '----- <br>';

        foreach ($modules as $module){
            echo '<strong>Module: '.$module->name.' => </strong><br>';
            try {
                echo $module->name.' migration: <br>';
                Artisan::call('migrate', [
                    '--path' => '/Modules/'.ucfirst($module->name).'/Database/Migrations/schema',
                    '--force' => true,
                ]);
                $this->artisanResult();
            } catch (\Throwable $th) {
                echo 'Migration error for '.ucfirst($module->name).'!<br>';
                $this->artisanResult();
                echo $th->getMessage();
            }
            echo '-----<br>';
            try {
                echo $module->name.' seeder: <br>';
                Artisan::call('module:seed '.ucfirst($module->name), [
                    '--force' => true,
                ]);
                $this->artisanResult();
            } catch (\Throwable $th) {
                echo 'Seed error for '.ucfirst($module->name).'!<br>';
                $this->artisanResult();
                echo $th->getMessage();
            }
            echo '-----<br>';
        }

        echo '<br><br><<<<<<<< SCRIPT DONE >>>>>>>>';
    }

}
