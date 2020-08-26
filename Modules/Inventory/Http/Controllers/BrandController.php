<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\BrandRepository;
use Modules\Inventory\Transformers\BrandResource;

class BrandController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(BrandRepository $repository, BrandResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
