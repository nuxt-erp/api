<?php

namespace App\Http\Controllers\Recipes;

use App\Http\Controllers\ControllerService;
use App\Repositories\RecipeItemRepository;
use App\Resources\RecipeItemResource;
use Illuminate\Http\Request;

class RecipeItemController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeItemRepository $repository, RecipeItemResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
