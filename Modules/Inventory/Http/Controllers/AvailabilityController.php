<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Inventory\Transformers\AvailabilityResource;

class AvailabilityController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(AvailabilityRepository $repository, AvailabilityResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();

    }

    public function stockOnHand(Request $request)
    {
        $items = $this->repository->stockOnHand($request->product_id,$request->location_id);
        return $items;

    }


}
