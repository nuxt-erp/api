<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Resources\ListResource;
use Modules\Inventory\Repositories\ProductRepository;
use Modules\Inventory\Transformers\AvailabilityStockCountResource;
use Modules\Inventory\Transformers\ProductResource;
use Illuminate\Http\Request;
use Modules\Inventory\Entities\ProductCategory;

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

    public function findCarriers(Request $request)
    {
        $isList = $request->has('list') && $request->list;
        $category = ProductCategory::where('name', 'LIKE', '%carrier%')->first();
        if($category){
            $request->merge(['category_id' => $category->id]);
            $items = $this->repository->findBy($request->all());
        }
        else{
            $items = null;
        }

        if($isList){
            return $this->sendCollectionResponse($items, ListResource::class);
        }
        else{
            return $this->sendFullCollectionResponse($items, $this->resource);
        }
    }
}
