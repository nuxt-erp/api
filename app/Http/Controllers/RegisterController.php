<?php

namespace App\Http\Controllers;

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

    public function installModules(Request $request){

        // 1 - run module migrations
        // 2 - run seeders for each module

        // php artisan module:migrate Blog
        // php artisan module:seed Blog

        DB::setDefaultConnection('tenant'); // it's important because the migration table will be created in the public schema

        try {
            Artisan::call('migrate', [
                '--path' => '/database/migrations/schema'
            ]);
            echo 'SUCCESS! ----- ';
        } catch (\Throwable $th) {
            echo $th->getMessage();
            echo 'ERROR! ----- ';
        }

        // Artisan::call('db:seed', [
        //     '--class' => 'CountryProvinceSeeder'
        // ]);


        dd(Artisan::output());

        //@todo run seeders?

        // php artisan migrate --path=/database/migrations/fileName.php
        // php artisan db:seed --class=classNameTableSeeder

    }

}
