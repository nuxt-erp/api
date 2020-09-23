<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\RecipeAttributesRepository;
use Modules\RD\Transformers\RecipeAttributesResource;

class RecipeAttributesController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeAttributesRepository $repository, RecipeAttributesResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
