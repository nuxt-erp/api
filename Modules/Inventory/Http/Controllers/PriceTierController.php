<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\PriceTierRepository;
use Modules\Inventory\Transformers\PriceTierResource;

class PriceTierController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PriceTierRepository $repository, PriceTierResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
