<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Models\Company;
use App\Models\Parameter;
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

        $recipe         = Recipe::with(['ingredients', 'type', 'attributes'])->find($recipe_id);
        if(!$recipe){
            die('Recipe not found!');
        }

        $sample_size    = Parameter::where('name', 'recipe_sample_size')->first();
        if(!$sample_size){
            die('Sample Size not found!');
        }

        $total_material_cost= 0;
        $total_material     = 0;
        $total_material_perc= 0;
        foreach ($recipe->ingredients as $ingredient) {

            // PERCENT IS SET
            if($ingredient->percent && $ingredient->percent > 0){
                $ingredient->quantity = $sample_size->value * ($ingredient->percent / 100);
            }
            // QTY IS SET
            elseif($ingredient->quantity && $ingredient->quantity > 0){
                $ingredient->percent = (100 * $ingredient->quantity) / $sample_size->value;
            }

            if($ingredient->quantity && $ingredient->quantity > 0){
                $total_material_cost+= $ingredient->cost;
                $total_material     += $ingredient->quantity;
            }
        }

        $total_material_perc = (100 * $total_material) / $sample_size->value;

        $total_carrier_cost = 0;
        $total_carrier      = 0;
        $total_carrier_perc = 0;
        if($recipe->carrier){
            $total_carrier      = $total_material >= $sample_size->value ? 0 : $sample_size->value - $total_material;
            $total_carrier_cost = $total_carrier * $recipe->carrier->cost;
            $total_carrier_perc = 100 - $total_material_perc;
        }

        $data = [
            'total_material_cost'   => $total_material_cost,
            'total_material'        => $total_material,
            'total_material_perc'   => $total_material_perc,
            'total_carrier_cost'    => $total_carrier_cost,
            'total_carrier'         => $total_carrier,
            'total_carrier_perc'    => $total_carrier_perc,
            'sample_size'           => $sample_size->value,
            'sample_uom'            => $sample_size->description,
            'recipe'                => $recipe,
        ];

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
        $all_attributes = Parameter::where('name', 'recipe_spec_attributes')->get()->toArray();
        $key_value = [];
        $key_titles = [
            'isPeanut' => 'Peanut and Peanut Product Derivatives',
            'isMilk' => 'Milk or Dairy Derivatives',
            'isEgg' => 'Egg and Egg Products',
            'isSoy' => 'Soy Products',
            'isWheat' => 'Wheat Products (Wheat Gluten)',
            'isTreeNut' => 'Tree Nuts',
            'isFish' => 'Fish Protein',
            'isShellfish' => 'Shellfish',
            'isMustard' => 'Mustard',
            'isSesame' => 'Sesame Seeds'
        ];

        foreach ($all_attributes as $attribute) {
            $key_value [$attribute['value']] = false;
        }

        foreach ($recipe_specification->spec_attributes as $attribute) {
            $key_value[$attribute->value] = true;
        }

        if($recipe_specification){
            // return view('rd::recipe-specifications', ['recipe_specification' => $recipe_specification,'key_titles' => $key_titles, 'key_value' => $key_value ]);
            $pdf = PDF::loadView('rd::recipe-specifications', ['recipe_specification' => $recipe_specification,'key_titles' => $key_titles, 'key_value' => $key_value ]);
            return $pdf->download('recipe-specifications.pdf');
        }

    }
}
