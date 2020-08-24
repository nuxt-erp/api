<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductAttributeRepository;
use Modules\Inventory\Resources\ProductAttributeResource;

class ProductAttributeController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductAttributeRepository $repository, ProductAttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
