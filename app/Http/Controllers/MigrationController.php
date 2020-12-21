<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Attribute;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Flavor;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\ProductAttributes;
use Modules\RD\Entities\Recipe;

class MigrationController extends Controller
{

    public function cvlProductMigration($user_param){

        echo 'Starting migration -------><br><br>';

        $user = User::where('name', 'ILIKE', '%'.$user_param.'%')->orWhere('email', 'ILIKE', '%'.$user_param.'%')->firstOrFail();
        config(['database.connections.tenant.schema' => $user->company->schema]);
        DB::reconnect('tenant');

        echo 'Schema: '.$user->company->schema.'<br><br>';

        // test if products table is empty
        $count = Product::count();

        if($count > 0){
            echo 'Product table is not empty';
            exit;
        }

        $lists  = [];
        $ids    = [];
        $result = ['inserted' => 0, 'errors' => 0];

        $variant                    = Attribute::where('code', 'variant')->firstOrFail();
        $ids['variant']             = $variant->id;

        $strength                   = Attribute::where('code', 'str')->firstOrFail();
        $ids['strength']            = $strength->id;

        $size                       = Attribute::where('code', 'size')->firstOrFail();
        $ids['size']                = $size->id;

        $material                   = Attribute::where('code', 'material')->firstOrFail();
        $ids['material']            = $material->id;

        $color                      = Attribute::where('code', 'color')->firstOrFail();
        $ids['color']               = $color->id;

        $cap_color                  = Attribute::where('code', 'cap_color')->firstOrFail();
        $ids['cap_color']           = $cap_color->id;

        $density                    = Attribute::where('code', 'density')->firstOrFail();
        $ids['density']             = $density->id;

        $batch_code_prefix          = Attribute::where('code', 'batch_code_prefix')->firstOrFail();
        $ids['batch_code_prefix']   = $batch_code_prefix->id;

        $qty_per_tray               = Attribute::where('code', 'qty_per_tray')->firstOrFail();
        $ids['qty_per_tray']        = $qty_per_tray->id;

        $previous_name              = Attribute::where('code', 'previous_name')->firstOrFail();
        $ids['previous_name']       = $previous_name->id;

        DB::connection('tenant')->transaction(function () use (&$lists, &$result, $ids){

            DB::connection('cvl') // set connection to CVL to get products
            ->table('products')
            ->select('products.*')

            ->addSelect('brands.name as brand_name', 'brands.dear as brand_dear_id')
            ->addSelect('product_categories.name as category_name', 'product_categories.dear as category_dear_id')
            ->addSelect('flavors.name as flavor_name')
            ->addSelect('pStatus.parameter_value as status_name')
            ->addSelect('pVariant.parameter_value as variant_name')
            ->addSelect('pMaterial.parameter_value as material_name')
            ->addSelect('pColor.parameter_value as color_name')
            ->addSelect('pCapColor.parameter_value as cap_color_name')

            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('flavors', 'flavors.id', '=', 'products.flavor_id')
            ->leftJoin('parameters as pStatus', 'pStatus.id', '=', 'products.status_id')
            ->leftJoin('parameters as pVariant', 'pVariant.id', '=', 'products.variant_id')
            ->leftJoin('parameters as pMaterial', 'pMaterial.id', '=', 'products.material_id')
            ->leftJoin('parameters as pColor', 'pColor.id', '=', 'products.color_id')
            ->leftJoin('parameters as pCapColor', 'pCapColor.id', '=', 'products.cap_color_id')
            ->whereNotNull('products.name')
            ->orderBy('products.id', 'asc')
            ->chunk(200, function ($products) use(&$lists, &$result, $ids) {

                foreach ($products as $product) {

                    // Brands handle
                    if(!empty($product->brand_id) && !isset($lists['brand'][$product->brand_id])){
                        $brand = Brand::firstOrCreate(
                            ['name'      => $product->brand_name],
                            ['dear_id'   => $product->brand_dear_id]
                        );
                        $lists['brand'][$product->brand_id] = $brand->id; //get the id in the nexterp db
                    }

                    // Categories handle
                    if(!empty($product->category_id) && !isset($lists['category'][$product->category_id])){
                        $category = Category::firstOrCreate(
                            ['name'      => $product->category_name],
                            ['dear_id'   => $product->category_dear_id]
                        );
                        $lists['category'][$product->category_id] = $category->id; //get the id in the nexterp db
                    }

                    // Flavor handle
                    if(!empty($product->flavor_id) && !isset($lists['flavor'][$product->flavor_id])){
                        $flavor = Flavor::firstOrCreate([
                            'name'      => $product->flavor_name
                        ]);
                        $lists['flavor'][$product->flavor_id] = $flavor->id; //get the id in the nexterp db
                    }

                    // Status handle
                    $status = !empty($product->status_id) && (
                        $product->status_name == 'discontinued' || $product->status_name == 'not_released'
                     ) ? 0 : 1;
                    // keeping_stock, not_released, discontinued, for_order

                    $newProduct = Product::create([
                            'id'            => $product->id,
                            'name'          => $product->name,
                            'sku'           => $product->sku,
                            'dear_id'       => $product->dear,
                            'description'   => $product->description,
                            'barcode'       => $product->barcode,
                            'is_enabled'    => $status,
                            'brand_id'      => !empty($product->brand_id) ? $lists['brand'][$product->brand_id] : null,
                            'category_id'   => !empty($product->category_id) ? $lists['category'][$product->category_id] : null,
                            'flavor_id'     => !empty($product->flavor_id) ? $lists['flavor'][$product->flavor_id] : null,
                    ]);

                    if(!$newProduct){
                        echo 'Product not inserted';
                        echo '<pre><br>';
                        print_r($product);
                        exit;
                    }

                    // ATTRIBUTES HANDLE

                    // Variant handle
                    if(!empty($product->variant_id)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['variant'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->variant_name
                            ]
                        );
                    }

                    // Str handle
                    if(!empty($product->strength)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['strength'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->strength
                            ]
                        );
                    }

                    // Size handle
                    if(!empty($product->size)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['size'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->size
                            ]
                        );
                    }

                    // Material handle
                    if(!empty($product->material_name)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['material'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->material_name
                            ]
                        );
                    }

                    // Color handle
                    if(!empty($product->color_name)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['color'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->color_name
                            ]
                        );
                    }

                    // Cap Color handle
                    if(!empty($product->cap_color_name)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['cap_color'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->cap_color_name
                            ]
                        );
                    }

                    // Density handle
                    if(!empty($product->density)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['density'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->density
                            ]
                        );
                    }

                    // Batch Code Prefix handle
                    if(!empty($product->batch_code_prefix)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['batch_code_prefix'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->batch_code_prefix
                            ]
                        );
                    }

                    // Bottles Per Tray handle
                    if(!empty($product->bottles_per_tray)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['qty_per_tray'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->bottles_per_tray
                            ]
                        );
                    }

                    // Previous Name handle
                    if(!empty($product->previous_name)){
                        ProductAttributes::create(
                            [
                                'attribute_id'  => $ids['previous_name'],
                                'product_id'    => $newProduct->id,
                                'value'         => $product->previous_name
                            ]
                        );
                    }

                    $result['inserted']++;
                    //: $result['errors']++;
                }


            });
        });

        echo 'inserted: '.$result['inserted'];
        echo '<br><br><<<<<<<< SCRIPT DONE >>>>>>>>';
    }

    public function cvlRecipeMigration($user_param){

        echo 'Starting migration -------><br><br>';

        $user = User::where('name', 'ILIKE', '%'.$user_param.'%')->orWhere('email', 'ILIKE', '%'.$user_param.'%')->firstOrFail();
        config(['database.connections.tenant.schema' => $user->company->schema]);
        DB::reconnect('tenant');

        echo 'Schema: '.$user->company->schema.'<br><br>';

        // test if products table is empty
        $count = Recipe::count();

        if($count > 0){
            echo 'Recipe table is not empty';
            exit;
        }

        $ids = [];

        DB::connection('tenant')->transaction(function () use (&$lists, &$result, $ids){

            DB::connection('cvl') // set connection to CVL to get products
            ->table('recipes')
            ->select('recipes.*')

            ->addSelect('brands.name as brand_name', 'brands.dear as brand_dear_id')
            ->addSelect('product_categories.name as category_name', 'product_categories.dear as category_dear_id')
            ->addSelect('flavors.name as flavor_name')
            ->addSelect('pStatus.parameter_value as status_name')
            ->addSelect('pVariant.parameter_value as variant_name')
            ->addSelect('pMaterial.parameter_value as material_name')
            ->addSelect('pColor.parameter_value as color_name')
            ->addSelect('pCapColor.parameter_value as cap_color_name')

            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('flavors', 'flavors.id', '=', 'products.flavor_id')
            ->leftJoin('parameters as pStatus', 'pStatus.id', '=', 'products.status_id')
            ->leftJoin('parameters as pVariant', 'pVariant.id', '=', 'products.variant_id')
            ->leftJoin('parameters as pMaterial', 'pMaterial.id', '=', 'products.material_id')
            ->leftJoin('parameters as pColor', 'pColor.id', '=', 'products.color_id')
            ->leftJoin('parameters as pCapColor', 'pCapColor.id', '=', 'products.cap_color_id')
            ->whereNotNull('products.name')
            ->orderBy('products.id', 'asc')
            ->chunk(200, function ($products) use(&$lists, &$result, $ids) {

                foreach ($products as $product) {
                }
            });
        });

    }

}
