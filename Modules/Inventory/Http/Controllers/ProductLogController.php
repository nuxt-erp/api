<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Repositories\ProductLogRepository;
use Modules\Inventory\Transformers\ProductLogResource;

class ProductLogController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(ProductLogRepository $repository, ProductLogResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();

    }

    public function getLog(Request $request) {
        $items = $this->repository->getLog($request->all());
        // respondWithCollection it's important to return total of records (useful for pagination)
        return $this->respondWithCollection($items, ProductLogResource::class);
    }
}
