<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Repositories\PriceTierRepository;
use Modules\Inventory\Transformers\PriceTierResource;
use Modules\Inventory\Transformers\ProductResource;

class PriceTierController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PriceTierRepository $repository, PriceTierResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function applyChanges(Request $request)
    {
        $items = $this->repository->applyChanges($request->all());
        //return $this->sendArray(['ok' => true]);
        return $this->sendObjectResource($items, $this->resource);
    }
}
