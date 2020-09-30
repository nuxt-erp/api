<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductImagesRepository;
use Modules\Inventory\Transformers\ProductImagesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ProductImagesRepository $repository, ProductImagesResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function getImage($filePath)
    {
        if(Storage::disk('s3')->exists(urldecode($filePath))){
            return Storage::disk('s3')->response(urldecode($filePath));
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        lad('store');
        if ($request->hasFile('files') && $request->filled('product_id')) {
            lad('has file');
            $x = 0;
            $files = [];
            foreach ($request->file('files') as $file) {
                $x++;
                $path = $file->store('company_'.$user->company_id.'/product_'.$request->product_id.'/images', ['disk' => 's3']);
                $files[] = $path;
                lad('path', $path);
            }
            $request->merge(['paths' => $files]);
        }

        //@todo delete files if something goes wrong

        return parent::store($request);
    }

}
