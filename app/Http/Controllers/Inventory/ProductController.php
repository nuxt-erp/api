<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductRepository;
use App\Resources\ProductResource;
use App\Resources\ProductAvailabilityResource;
use App\Resources\ProductAvailabilityStockCountResource;
use Illuminate\Http\Request;

class ProductController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ProductRepository $repository, ProductResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function productAvailabilities(Request $request)
    {
        $itens = $this->repository->productAvailabilities($request->all());
        return $this->respondWithNativeCollection($itens, ProductAvailabilityStockCountResource::class);
    }

}
