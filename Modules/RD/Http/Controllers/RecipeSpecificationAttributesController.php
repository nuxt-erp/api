<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\RecipeSpecificationAttributesRepository;
use Modules\RD\Transformers\RecipeSpecificationAttributesResource;

class RecipeSpecificationAttributesController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeSpecificationAttributesRepository $repository, RecipeSpecificationAttributesResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
