<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\DiscountRepository;
use Modules\Sales\Transformers\DiscountResource;

class DiscountController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(DiscountRepository $repository, DiscountResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
    
}
