<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockCountRepository;
use Modules\Inventory\Transformers\StockCountResource;

class StockCountController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(StockCountRepository $repository, StockCountResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function start(Request $request)
    {
        //@todo
        // 1 get all products
        // 2 get all availabilities (filtered by location)
        // 3 split by bin if exist
    }

    public function finish($stockcount_id)
    {
        $status = $this->repository->finish($stockcount_id);
        return $this->setStatus($status)->send();
    }
}
