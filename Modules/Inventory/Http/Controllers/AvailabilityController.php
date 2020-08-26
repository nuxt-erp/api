<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Inventory\Transformers\AvailabilityResource;
use Modules\Inventory\Transformers\AvailabilityStockCountResource;

class AvailabilityController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(AvailabilityRepository $repository, AvailabilityResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function productAvailabilities(Request $request)
    {
        $items = $this->repository->productAvailabilities($request->all());
        return $this->sendFullCollectionResponse($items, AvailabilityStockCountResource::class);

    }
}
