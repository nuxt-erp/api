<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ProductImagesRepository;
use Modules\Inventory\Transformers\ProductImagesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
        $files = [];
        if ($request->hasFile('files') && $request->filled('product_id')) {
            lad('has file');
            $x = 0;

            foreach ($request->file('files') as $file) {
                $x++;
                //@todo DRY - add this to some helper function to reuse the same folder pattern
                // save original file
                $path = $file->store('company_'.$user->company_id.'/product_'.$request->product_id.'/images', ['disk' => 's3']);

                // Intervention image trick
                $img = Image::make($file);
                // Resize
                $img->resize(null, 150, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Secret to get the image back
                $resource       = $img->stream()->detach();

                // Generate thumbnail path
                $path_explode   = explode('/', $path);
                $path_explode[3]= 'small_'.$path_explode[3]; // e.g: small_4WqmiVAgOLnYZ8bbEclVnuor3HM2iAenCBnJfnmh.png
                $thumb_path     = implode('/', $path_explode);

                // save thumbnail
                Storage::disk('s3')->put($thumb_path, $resource);

                $files[] = ['path' => $path, 'thumb_path' => $thumb_path];
            }
            $request->merge(['paths' => $files]);
        }

        if($this instanceof CheckPolicies){
            $this->authorize('store', get_class($this->repository->model));
        }

        // Validation
        $validatorResponse = $this->validateRequest($request);

        // Send failed response if empty request
        if (empty($request->all())) {
            return $this->emptyResponse();
        }

        // Send failed response if validation fails and return array of errors
        if (!empty($validatorResponse)) {
            return $this->validationResponse($validatorResponse);
        }

        $this->repository->store($request->all());
        if($this->repository->model){
            return $this->setStatusCode(201)->sendObjectResource($this->repository->model, $this->resource);
        }
        else{
            foreach ($files as $file) {
                Storage::disk('s3')->delete($file['path']);
                Storage::disk('s3')->delete($file['thumb_path']);
            }
            return $this->setStatus(FALSE)
                ->setStatusCode(500)
                ->setMessage('Upload failed!')
                ->send();
        }

    }

}
