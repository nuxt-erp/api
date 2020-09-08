<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductFamilyAttributeRepository;
use Modules\Inventory\Transformers\ProductFamilyAttributeResource;

class ProductFamilyAttributeController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(ProductFamilyAttributeRepository $repository, ProductFamilyAttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
