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
        $category = ProductCategory::where('name', 'ILIKE', '%carrier%')->first();
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

    public function findRawMaterials(Request $request)
    {
        $isList = $request->has('list') && $request->list;
        $categories_id = ProductCategory::where('name', 'ILIKE', '%Raw Material%')
        ->orWhere('name', 'ILIKE', '%Flavor%')
        ->orWhere('name', 'ILIKE', '%Flavor Key%')
        ->orWhere('name', 'ILIKE', '%Solution Material%')
        ->orWhere('name', 'ILIKE', '%Water%')
        ->get()->pluck('id')->toArray();

        if(!empty($categories_id)){
            $request->merge(['categories_id' => $categories_id]);
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
