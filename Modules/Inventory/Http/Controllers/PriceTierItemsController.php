<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\PriceTierItemsRepository;
use Modules\Inventory\Transformers\PriceTierItemsResource;

class PriceTierItemsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PriceTierItemsRepository $repository, PriceTierItemsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
