<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\AttributeRepository;
use App\Resources\AttributeResource;

class AttributeController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(AttributeRepository $repository, AttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
