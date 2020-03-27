<?php

namespace App\Http\Controllers;

use App\Imports\ProductionOrdersImport;
use App\Imports\RecipesImport;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Import;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductAvailability;
use App\Models\ProductCategory;
use App\Models\Recipe;
use App\Models\RecipeItems;
use App\Models\User;
use App\Resources\ProductResource;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use stdClass;

class ImportController extends ControllerService
{

    public function syncProduct($sku)
    {
        $api = resolve('Dear\API');
        $product = $api->syncProds($sku);
        return $this->respondWithObject($product, ProductResource::class);
    }

    public function xlsSyncRecipes(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new RecipesImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);

            Import::create([
                'name' => Import::XLS_SYNC_RECIPES,
                'author_id' => $this->user_id,
                'rows'  => $import->rows,
                'status' => ''
            ]);
        }

        return $this->respondWithNativeObject($import);
    }

    public function xlsInsertPO(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new ProductionOrdersImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if(!isset($import->rows) || $import->rows < 1){
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->respondWithNativeObject($import);
    }

    public function dearSyncCategories()
    {
        $api = resolve('Dear\API');
        $result = $api->syncCategories();

        Import::create([
            'name' => Import::DEAR_SYNC_CATEGORIES,
            'author_id' => $this->user_id,
            'rows'  => $result,
            'status' => ''
        ]);

        return $this->respondWithArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncLocations()
    {
        $total      = 0;
        $flagLoop   = FALSE;

        do {
            $result = $this->makeRequest('ref/location', [
                'Page'  => $this->page,
                'Limit' => $this->limit
            ]);

            if ($result->status) {
                $total = $result->Total;
                $list = $result->LocationList;
                if ($total > 0) {
                    foreach ($list as $item) {
                        Location::updateOrCreate(
                            ['dear' => $item->ID],
                            ['name' => formatName($item->Name)]
                        );
                        $this->count++;
                    }
                }

                $flagLoop = $total > $this->limit && $this->page <= $total / $this->limit;
                $this->page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        Import::create([
            'name' => Import::DEAR_SYNC_LOCATIONS,
            'author_id' => $this->user_id,
            'rows'  => $this->count,
            'status' => 'Errors found: ' . $this->errors_count
        ]);

        return $this->respondWithArray([
            'errors'    => $this->errors,
            'rows'      => $this->count
        ]);
    }

    public function dearSyncAvailabilities()
    {
        $total      = 0;
        $flagLoop   = FALSE;

        do {
            $result = $this->makeRequest('ref/productavailability', [
                'Page'  => $this->page,
                'Limit' => $this->limit
            ]);

            if ($result->status) {
                $total = $result->Total;
                $list = $result->ProductAvailabilityList;
                if ($total > 0) {
                    foreach ($list as $item) {
                        $product = Product::where('dear', $item->ID)
                        ->first();
                        $location = !empty($item->Location) ? Location::where('name', $item->Location)
                        ->first() : null;

                        if ($product) {
                            ProductAvailability::updateOrCreate(
                                ['product_id' => $product->id],
                                [
                                    'location_id'       => $location->id ?? null,
                                    'available_quantity'=> $item->Available >= 0 ? $item->Available : 0
                                ]
                            );
                        }
                        else{
                            $this->count++;
                            if(count($this->errors) <= 50)
                                $this->errors[] = 'Product not found: ' . 'SKU: ' . $item->SKU;
                            $this->errors_count++;
                        }

                    }
                }

                $flagLoop = $total > $this->limit && $this->page <= $total / $this->limit;
                $this->page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        Import::create([
            'name' => Import::DEAR_SYNC_LOCATIONS,
            'author_id' => $this->user->id,
            'rows'  => $this->count,
            'status' => 'Errors found: ' . $this->errors_count
        ]);

        return $this->respondWithArray([
            'errors'    => $this->errors,
            'rows'      => $this->count
        ]);
    }

    public function dearSyncBrands()
    {
        $api = resolve('Dear\API');
        $result = $api->syncBrands();

        Import::create([
            'name' => Import::DEAR_SYNC_BRANDS,
            'author_id' => $this->user_id,
            'rows'  => $result,
            'status' => ''
        ]);

        return $this->respondWithArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncProducts()
    {
        $api = resolve('Dear\API');
        $result = $api->syncProds();

        Import::create([
            'name' => Import::DEAR_SYNC_PRODUCTS,
            'author_id' => $this->user_id,
            'rows'  => $result,
            'status' => ''
        ]);

        return $this->respondWithArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncRecipes()
    {
        $total  = 0;
        $flagLoop = FALSE;

        do {
            $result = $this->makeRequest('product', [
                'Page'              => $this->page,
                'Limit'             => $this->limit,
                'IncludeBOM'        => 'true',
                'includeDeprecated' => 0
            ]);

            if ($result->status) {
                $total = $result->Total;
                $products = $result->Products;
                $status_new_id = Recipe::getNewStatus()->id;
                if ($total > 0) {
                    foreach ($products as $product) {
                        if (in_array($product->Category, ['STLTH PAILS', 'MIXTURE', 'MIXTURE EXTRACT', 'NYX MIXTURE', 'OEM MIXTURE'])) {

                            DB::transaction(function () use ($product, $status_new_id) {

                                $formated_product = $this->formatProduct($product);
                                $brand = Brand::where('name', formatName($formated_product->brand))
                                ->whereNotNull('dear')
                                ->first();

                                $recipe = Recipe::updateOrCreate(
                                    ['dear' => $product->ID],
                                    [
                                        'sku'               => $formated_product->sku,
                                        'strength'          => $formated_product->strength,
                                        'size'              => $formated_product->size,
                                        'name'              => $formated_product->name,
                                        'author_id'         => $this->user->id,
                                        'brand_id'          => $brand ? $brand->id : null,
                                        'last_updater_id'   => $this->user->id,
                                        'status_id'         => $status_new_id
                                    ]
                                );

                                $recipe_items = $product->BillOfMaterialsProducts;

                                foreach ($recipe_items as $recipe_item) {
                                    $our_product = Product::where('dear', $recipe_item->ComponentProductID)
                                    ->first();

                                    if ($our_product) {
                                        $item_found = RecipeItems::where('recipe_id', $recipe->id)
                                            ->where('product_id', $our_product->id)->first();

                                        if (!$item_found) {
                                            RecipeItems::create([
                                                'recipe_id'         => $recipe->id,
                                                'product_id'        => $our_product->id,
                                                'percent'           => round($recipe_item->Quantity * 100, 2),
                                                'initial_percent'   => round($recipe_item->Quantity * 100, 2)
                                            ]);
                                            $this->count++;
                                        }
                                        //@todo maybe we can create an else to update recipe items where percent and initial_percet still the same
                                    } else {
                                        $this->errors_count++;
                                        if(count($this->errors) <= 50)
                                            $this->errors[] = 'Product not found: ' . $recipe_item->Name;
                                    }
                                }
                            });
                        }
                    }
                }
                $flagLoop = $total > $this->limit && $this->page <= $total / $this->limit;
                $this->page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);


        Import::create([
            'name' => Import::DEAR_SYNC_RECIPE,
            'author_id' => $this->user->id,
            'rows'  => $this->count,
            'status' => 'Errors found: ' . $this->errors_count
        ]);

        return $this->respondWithArray([
            'errors'    => $this->errors,
            'rows'      => $this->count
        ]);
    }

}
