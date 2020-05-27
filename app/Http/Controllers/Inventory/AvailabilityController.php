<?php

namespace App\Http\Controllers\Inventory;

use App\Concerns\WithAllPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\ProductAvailabilityRepository;
use App\Resources\ProductAvailabilityResource;
use App\Resources\ProductAvailabilityStockCountResource;
use Illuminate\Http\Request;

class AvailabilityController extends ControllerService implements WithAllPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductAvailabilityRepository $repository, ProductAvailabilityResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function productAvailabilities(Request $request)
    {
        $itens = $this->repository->productAvailabilities($request->all());
        return $this->respondWithCollection($itens, ProductAvailabilityStockCountResource::class);
        // return $this->respondWithCollection($itens, ProductAvailabilityResource::class);

    }
}
