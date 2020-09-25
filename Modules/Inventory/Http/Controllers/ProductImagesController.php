<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductImagesRepository;
use Modules\Inventory\Transformers\ProductImagesResource;
use Illuminate\Http\Request;

class ProductImagesController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductImagesRepository $repository, ProductImagesResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $request->product_id;
            $file->storeAs('c'.$user->company_id.'/product_images', $fileName, ['disk' => 's3']);

        }
        return parent::store($request);
    }

}
