<?php

namespace App\Http\Controllers\Recipes;

use App\Http\Controllers\ControllerService;
use App\Repositories\RecipeProposalRepository;
use App\Resources\RecipeProposalResource;
use Illuminate\Http\Request;

class RecipeProposalController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeProposalRepository $repository, RecipeProposalResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function approve($id)
    {
        //@todo only admin can do this
        $item = $this->repository->findOne($id);

        $this->repository->update($item, [
            'status' => RecipeProposal::APPROVED
        ]);

        $recipe = Recipe::where('id', $this->repository->model->recipe_id)->first();

        return $this->respondWithObject($recipe, RecipeResource::class);

    }

    public function disapprove($id)
    {
        //@todo only admin can do this
        $item = $this->repository->findOne($id);

        $this->repository->update($item, [
            'status' => RecipeProposal::REJECTED
        ]);

        $recipe = Recipe::where('id', $this->repository->model->recipe_id)->first();

        return $this->respondWithObject($recipe, RecipeResource::class);

    }

}
