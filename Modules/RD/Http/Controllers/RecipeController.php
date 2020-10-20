<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Models\Company;
use App\Models\User;
use Modules\RD\Repositories\RecipeRepository;
use Modules\RD\Transformers\RecipeResource;
use PDF;
use Illuminate\Support\Facades\DB;
use Modules\RD\Entities\RecipeSpecification;

class RecipeController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeRepository $repository, RecipeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function print($user_id, $recipe_id){

        $user = User::find($user_id);

        DB::setDefaultConnection('tenant');
        config(['database.connections.tenant.schema' => $user->company->schema]);
        DB::reconnect('tenant');

        $recipe = Recipe::with(['ingredients', 'type', 'attributes'])->find($recipe_id);

        if($recipe){
            return view('rd::recipe', ['recipe' => $recipe]);
            //$pdf = PDF::loadView('rd::recipe', ['recipe' => $recipe]);
            //return $pdf->download('recipe.pdf');
        }

    }
    public function printSpecification($user_id, $recipe_specification_id){

        $user = User::find($user_id);

        DB::setDefaultConnection('tenant');
        config(['database.connections.tenant.schema' => $user->company->schema]);
        DB::reconnect('tenant');

        $recipe_specification = RecipeSpecification::with(['approver', 'project_sample', 'attributes'])->find($recipe_specification_id);
    
        if($recipe_specification){
            return view('rd::recipe-specifications', ['recipe_specification' => $recipe_specification]);
            //$pdf = PDF::loadView('rd::recipe', ['recipe' => $recipe]);
            //return $pdf->download('recipe.pdf');
        }

    }
}
