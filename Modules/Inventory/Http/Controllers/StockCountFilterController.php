<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockCountFilterRepository;
use Modules\Inventory\Transformers\StockCountFilterResource;

class StockCountFilterController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(StockCountFilterRepository $repository, StockCountFilterResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
