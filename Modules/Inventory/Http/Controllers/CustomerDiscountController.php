<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\CustomerDiscountRepository;
use Modules\Inventory\Transformers\CustomerDiscountResource;

class CustomerDiscountController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(CustomerDiscountRepository $repository, CustomerDiscountResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}