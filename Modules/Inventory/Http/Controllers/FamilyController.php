<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Repositories\FamilyRepository;
use Modules\Inventory\Transformers\FamilyResource;
use Modules\Inventory\Transformers\ProductResource;

class FamilyController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(FamilyRepository $repository, FamilyResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function getListProducts(Request $request)
    {
        $items = $this->repository->getListProducts($request->all());
        return $this->sendCollectionResponse($items, ProductResource::class);
    }

    public function remove(Request $request) {
        $this->repository->remove($request->id);
        return $this->sendArray(['ok' => true]);
    }
}
