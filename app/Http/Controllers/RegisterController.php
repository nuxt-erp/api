<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Resources\UserResource;
use Illuminate\Http\Request;

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
        return $this->setStatusCode(201)->send();
    }

}
