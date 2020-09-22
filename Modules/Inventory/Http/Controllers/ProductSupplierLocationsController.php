<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductSupplierLocationsRepository;
use Modules\Inventory\Transformers\ProductSupplierLocationsResource;

class ProductSupplierLocationsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductSupplierLocationsRepository $repository, ProductSupplierLocationsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
