<?php

namespace Modules\RD\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\RD\Entities\Recipe;
use Modules\RD\Entities\RecipeImportSettings;
use Modules\RD\Entities\RecipeItems;
use App\Models\Parameter;

class RecipesImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = [];
    public $recipes = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows)
        {

            $settings = RecipeImportSettings::all();

            $custom_names = [];
            $settings->each(function ($item, $key) use(&$custom_names){
                $custom_names[$item->entity.'_'.$item->column_name] = strtolower($item->custom_name);
            });

            lad($custom_names);
            if(count($rows) > 0){
                lad("row0", $rows[0]);
            }
            lad($custom_names['recipe_code'] ?? 'code');
            lad($custom_names['recipe_name'] ?? 'name');
            lad($custom_names['ingredients_quantity'] ?? 'quantity');
            lad($custom_names['ingredients_product_sku']);

            $FK = Parameter::where([
                'name'  => 'recipe_type',
                'value' => 'FK'
            ])->first();
    
            $FL = Parameter::where([
                'value' => 'FL',
                'order' => 2
            ])->first();
    
            $SL = Parameter::where([
                'name'  => 'recipe_type',
                'value' => 'SM'
            ])->first();

            foreach ($rows as $key => $row)
            {
                $recipe_code               = $row[$custom_names['recipe_code'] ?? 'code'] ?? null;
                $recipe_name               = $row[$custom_names['recipe_name'] ?? 'name'] ?? null;
                $ingredients_quantity      = $row[$custom_names['ingredients_quantity'] ?? 'quantity'] ?? null;
                $ingredients_product_sku   = $row[$custom_names['ingredients_product_sku'] ?? 'product_sku'] ?? null;
            

                if(!empty($recipe_code) && !empty($recipe_name)){
                    $recipe_type_id = NULL;

                    if(substr($recipe_code , 0, 2) === 'FK') {
                        $recipe_type_id = $FK->id;
                    } else if(substr($recipe_code , 0, 2) === 'FL') {
                        $recipe_type_id = $FL->id;
                    } else if(substr($recipe_code , 0, 2) === 'SM') {
                        $recipe_type_id = $SL->id;
                    }

                    $recipe = Recipe::updateOrCreate([
                        'code'   => $recipe_code
                    ],[
                        'status'        => 'new',
                        'code'          => $recipe_code,
                        'name'          => $recipe_name,
                        'type_id'       => $recipe_type_id
                    ]);
                    if(!empty($ingredients_product_sku)) {
                        $product_id = null;
                        $product = Product::where('sku', '=', $ingredients_product_sku)->first();

                        

                        RecipeItems::updateOrCreate([
                            'recipe_id'       => $recipe->id,
                            'product_id'      => $product->id

                        ],[
                            'recipe_id'       => $recipe->id,
                            'product_id'      => $product->id,
                            'quantity'        => $ingredients_quantity
                        ]);
                    }

                    $this->rows++;
                }
                else{
                    $this->errors[] = 'Line: '.$key.'. Message: Empty SKU';
                }

            }
        });
    }
}
