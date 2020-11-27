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
        parent::__construct();
    }

    public function getListProducts($id)
    {
        $items = $this->repository->getListProducts($id);

        return $this->sendCollectionResponse($items, ProductResource::class);
    }

    public function remove(Request $request) {
        $this->repository->remove($request->id);
        return $this->sendArray(['ok' => true]);
    }
    public function storeProductImage(Request $request) {
       lad($request->all());
       $this->repository->store($request);

    }
    
}