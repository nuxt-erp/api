<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\UserRepository;
use App\Resources\UserResource;
use App\Resources\LoginResource;
use Illuminate\Http\Request;


class UserController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(UserRepository $repository, UserResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function findMe()
    {
        $item = $this->repository->findOne(auth()->user()->id);
        return $this->respondWithObject($item, LoginResource::class);
    }

    public function updateMe(Request $request)
    {
        $id = auth()->user()->id;
        return parent::update($request, $id);
    }
}
