<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductFamilyRepository;
use App\Resources\ProductFamilyResource;
use App\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductFamilyController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProductFamilyRepository $repository, ProductFamilyResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function getListProducts(Request $request)
    {
        $itens = $this->repository->getListProducts($request->all());
        return $this->respondWithNativeCollection($itens, ProductResource::class);
    }

    public function remove(Request $request) {
        $this->repository->remove($request->id);
        return $this->respond(['ok' => true]);
    }
}
