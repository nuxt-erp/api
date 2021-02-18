<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Support\Arr;
use Modules\Inventory\Repositories\ProductSuppliersRepository;
use Modules\Inventory\Transformers\ProductSuppliersResource;

class ProductSuppliersController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductSuppliersRepository $repository, ProductSuppliersResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
    
    public function skuSuppliers(Request $request)
    {
        $result = $this->repository->skuSuppliers($request->all());
        return $result;
    }
    
}
