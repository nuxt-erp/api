<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductRepository;
use Modules\Inventory\Transformers\AvailabilityStockCountResource;
use Modules\Inventory\Transformers\ProductResource;
use Illuminate\Http\Request;

class ProductController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(ProductRepository $repository, ProductResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function productAvailabilities(Request $request)
    {
        $items = $this->repository->productAvailabilities($request->all());
        return $this->sendFullCollectionResponse($items, AvailabilityStockCountResource::class);

    }
}
