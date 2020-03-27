<?php

namespace App\Http\Controllers\Recipes;

use App\Http\Controllers\ControllerService;
use App\Repositories\RecipeRepository;
use App\Resources\RecipeResource;
use Illuminate\Http\Request;

class RecipeController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeRepository $repository, RecipeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function findBySku($sku){

        $item = $this->repository->findBySku($sku);

        if ($item) {
            return $this->respondWithObject($item, $this->resource);
        } else {
            return $this->notFoundResponse(['sku' => $sku]);
        }

    }

}
