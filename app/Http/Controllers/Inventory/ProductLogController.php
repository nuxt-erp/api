<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductLogRepository;
use App\Resources\ProductLogResource;
use Illuminate\Http\Request;

class ProductLogController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProductLogRepository $repository, ProductLogResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function getLog(Request $request) {
        $itens = $this->repository->getLog($request->all());
        // respondWithCollection it's important to return total of records (useful for pagination)
        return $this->respondWithCollection($itens, ProductLogResource::class);
    }
}
