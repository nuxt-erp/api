<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\RecipeItemsRepository;
use Modules\RD\Transformers\RecipeItemsResource;


class RecipeItemsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeItemsRepository $repository, RecipeItemsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
