<?php

namespace App\Http\Controllers\Recipes;

use App\Http\Controllers\ControllerService;
use App\Repositories\RecipeProposalItemRepository;
use App\Resources\RecipeProposalItemResource;
use Illuminate\Http\Request;

class RecipeProposalItemController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeProposalItemRepository $repository, RecipeProposalItemResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
