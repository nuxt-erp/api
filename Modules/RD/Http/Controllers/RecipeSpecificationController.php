<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\RecipeSpecificationRepository;
use Modules\RD\Transformers\RecipeSpecificationResource;

class RecipeSpecificationController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeSpecificationRepository $repository, RecipeSpecificationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
