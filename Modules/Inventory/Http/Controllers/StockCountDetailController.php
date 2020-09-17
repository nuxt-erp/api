<?php

namespace Modules\Inventory\Http\Controllers;


use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockCountDetailRepository;
use Modules\Inventory\Transformers\StockCountDetailResource;

class StockCountDetailController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(StockCountDetailRepository $repository, StockCountDetailResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
