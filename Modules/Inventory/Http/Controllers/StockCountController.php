<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockCountRepository;
use Modules\Inventory\Transformers\StockCountProductsResource;
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
        $result = $this->repository->findProductsAvailabilities($request->all());
        return $this->sendArray($result);
    }

    public function startMobile(Request $request)
    {
        $result = $this->repository->findProductsAvailabilitiesMobile($request->all());
        return $this->sendArray($result);
    }

    public function getStatuses()
    {
        $result = $this->repository->getStockCountStatuses();
        return $this->sendArray($result);
    }

    public function finish($stockcount_id)
    {
        $status = $this->repository->finish($stockcount_id);
        return $this->send();
    }

    public function export($stockcount_id)
    {
        $result = $this->repository->exportStockCount($stockcount_id);
        return $this->setStatusCode(201)->sendArray($result);
    }
}
