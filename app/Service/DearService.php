<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Import;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Recipe;
use App\Models\RecipeItems;
use App\Models\Supplier;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use stdClass;
use Carbon\Carbon;

class DearService
{


    private $dear_id;
    private $dear_key;
    private $dear_url;
    private $client;
    private $limit;
    private $user;

    public function __construct($dear_id, $dear_key, $dear_url)
    {
        $this->dear_id = $dear_id;
        $this->dear_key = $dear_key;
        $this->dear_url = $dear_url;
        $this->client = new Client([
            'base_uri' => $this->dear_url,
        ]);

        $this->limit = 500;
        $this->user = auth()->user() ?? User::where('email', 'like', '%dear%')->first();
    }

    public function syncProds($sku = null)
    {
        $lastUpdate = Import::where('name', 'DEAR_SYNC_PRODUCTS')->orderBy('created_at', 'desc')->first();
        $updateDate = $lastUpdate ? $lastUpdate->created_at->toIso8601String() : null;
        
        $result     = FALSE;
        $flagLoop   = FALSE;
        $total      = 0;
        $page       = 1;
        $count      = 0;

        do {

            $filters = [
                'Page'              => $page,
                'Limit'             => !empty($sku) ? 1 : $this->limit,
                'IncludeDeprecated' => 0,
                'ModifiedSince'     => $updateDate 
            ];
            if (!empty($sku)) {
                $filters['Sku'] = $sku;
            }

            $categories_list = [];
            $brands_list = [];

            $dear_result = $this->makeRequest('product', $filters);

                    if ($dear_result->status) {
                $total = $dear_result->Total;
                foreach ($dear_result->Products as $prod) {
                    //$prod
                    $formatted_product = $this->formatProduct($prod);

                    if(!empty($sku) && $formatted_product->sku !== $sku){
                        continue;
                    }

                    if (!empty($formatted_product->brand)) {
                        // CATEGORY HANDLE
                        if (!isset($categories_list[$formatted_product->category])) {
                            $category = ProductCategory::where('name', $formatted_product->category)
                                ->first();
                            if (!$category) {
                                $category = $this->syncCategories($formatted_product->category);
                            }
                            $categories_list[$formatted_product->category] = $category;
                        } else {
                            $category = $categories_list[$formatted_product->category];
                        }

                        // BRAND HANDLE
                        if (!isset($brands_list[$formatted_product->brand])) {
                            $brand = Brand::where('name', $formatted_product->brand)
                                ->first();
                            if (!$brand) {
                                $brand = $this->syncBrands($formatted_product->brand);
                            }
                            $brands_list[$formatted_product->brand] = $brand;
                        } else {
                            $brand = $brands_list[$formatted_product->brand];
                        }

                        $product = Product::where('dear', $prod->ID)
                        ->first();

                        if (!$product) {
                            $product = Product::create([
                                'dear'          => $prod->ID,
                                'category_id'   => $category->id,
                                'brand_id'      => $brand->id ?? null,
                                'sku'           => $formatted_product->sku,
                                'name'          => $formatted_product->name,
                                'strength'      => $formatted_product->strength,
                                'size'          => $formatted_product->size,
                                'barcode'       => $formatted_product->barcode,
                                'density'       => $formatted_product->density
                            ]);
                            $count++;
                        } else {
                            $product->barcode = $formatted_product->barcode;
                            $product->density = $formatted_product->density;
                            $product->save();
                        }

                        if (!empty($sku)) {
                            $result = $product;
                        } else {
                            $result = $count;
                        }
                    }
                }
                $flagLoop = $total > $this->limit && $page <= ($total / $this->limit);
                $page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        return $result;
    }

    public function productsDiffReport()
    {
        $diff_list = [];

        $flagLoop   = FALSE;
        $total      = 0;
        $page       = 1;

        do {

            $filters = [
                'Page'              => $page,
                'Limit'             => $this->limit,
                'includeDeprecated' => 0,
            ];

            $dear_result = $this->makeRequest('product', $filters);

            if ($dear_result->status) {
                $total = $dear_result->Total;

                foreach ($dear_result->Products as $prod) {
                    if (in_array($prod->Category, ['STLTH PAILS', 'ELIQUID', 'NICOTINE'])) {

                        //$prod
                        $formatted_product = $this->formatProduct($prod);

                        if(!empty($formatted_product->sku)){
                            $product = Product::where('sku', $formatted_product->sku)->first();

                            if(!empty($formatted_product->density)){
                                if (number_format($formatted_product->density, 4) != $product->density) {
                                    $diff_list[] = [
                                        'sku'       => $formatted_product->sku,
                                        'reason'    => 'Density on Dear: ' . $formatted_product->density . 'Density on intranet: ' . $product->density
                                    ];
                                }
                            }
                            else{
                                $diff_list[] = [
                                    'sku'       => $formatted_product->sku,
                                    'reason'    => 'Density not defined on Dear'
                                ];
                            }
                        }
                    }
                }
                $flagLoop = $total > $this->limit && $page <= ($total / $this->limit);
                $page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        return $diff_list;
    }

    public function syncCategories($name = NULL)
    {

        $page = 1;
        $final_item = FALSE;
        $filters = [
            'Page'              => $page,
            'Limit'             => !empty($name) ? 1 : $this->limit
        ];
        if (!empty($name)) {
            $filters['Name'] = $name;
        }

        $dear_result = $this->makeRequest('ref/category', $filters);

        if ($dear_result->status) {
            foreach ($dear_result->CategoryList as $item) {
                $final_item = ProductCategory::updateOrCreate(
                    ['dear' => $item->ID],
                    ['name' => formatName($item->Name)]
                );
            }
        }

        return $final_item;
    }

    public function syncBrands($name = NULL)
    {

        $page = 1;
        $final_item = FALSE;
        $filters = [
            'Page'              => $page,
            'Limit'             => !empty($name) ? 1 : $this->limit
        ];
        if (!empty($name)) {
            $filters['Name'] = $name;
        }

        $dear_result = $this->makeRequest('ref/brand', $filters);

        if ($dear_result->status) {
            foreach ($dear_result->BrandList as $item) {
                $final_item = Brand::updateOrCreate(
                    ['dear' => $item->ID],
                    ['name' => formatName($item->Name)]
                );
            }
        }

        return $final_item;
    }

    public function diffReport($sku = NULL)
    {

        $diff_list = [];

        $flagLoop = FALSE;
        $total = 0;
        $page = 1;
        $ingredients = [];

        do {
            $filters = [
                'Page'              => $page,
                'Limit'             => $this->limit,
                'IncludeBOM'        => 'true',
                'includeDeprecated' => 0
            ];
            if (!empty($sku)) {
                $filters['Sku'] = $sku;
            }
            $dear_result = $this->makeRequest('product', $filters);

            if ($dear_result->status) {
                $total = $dear_result->Total;

                foreach ($dear_result->Products as $product) {
                    if (in_array($product->Category, ['STLTH PAILS', 'MIXTURE', 'MIXTURE EXTRACT', 'NYX MIXTURE', 'OEM MIXTURE'])) {

                        $formatted_product = $this->formatProduct($product);

                        $recipe = Recipe::where('sku', $formatted_product->sku)->first();

                        if (
                            !$recipe ||
                            $recipe->strength != $formatted_product->strength ||
                            $recipe->size != $formatted_product->size ||
                            $recipe->name != $formatted_product->name
                        ) {
                            $diff_list[] = [
                                'sku'       => $formatted_product->sku,
                                'reason'    => !$recipe ? 'Recipe not found on intranet, (maybe Brand is missing) ' : 'Recipe info diff: ' . $recipe->name . ' - ' . $formatted_product->name
                            ];
                        } else {

                            $dear_recipe_items  = $product->BillOfMaterialsProducts;
                            $recipe_items       = $recipe->items;


                            // VERIFY INGREDIENTS
                            if (count($dear_recipe_items) != count($recipe_items)) {
                                // validate each ingredient
                                foreach ($dear_recipe_items as $key => $dear_item) {
                                    //save in the array before use
                                    if (empty($ingredients[$dear_item->ProductCode])) {
                                        $result = $this->makeRequest('product', [
                                            'Page'              => 1,
                                            'Limit'             => 1,
                                            'Sku'               => $dear_item->ProductCode,
                                        ]);
                                        if ($result->Total >= 1) {
                                            $ingredients[$dear_item->ProductCode] = $result->Products[0];
                                        }
                                    }
                                    //remove from the array if is deprecated
                                    if (empty($ingredients[$dear_item->ProductCode]) || $ingredients[$dear_item->ProductCode]->Status === 'Deprecated') {
                                        unset($dear_recipe_items[$key]);
                                    }
                                }
                                if (count($dear_recipe_items) != count($recipe_items)) {
                                    $diff_list[] = [
                                        'sku'       => $formatted_product->sku,
                                        'reason'    => 'ingredients on dear: ' . count($dear_recipe_items) . ' ingredients on intranet: ' . count($recipe_items)
                                    ];
                                }
                            } else {
                                foreach ($dear_recipe_items as $dear_item) {
                                    $product = Product::where('dear', $dear_item->ComponentProductID)->first();
                                    if (!$product) {
                                        $diff_list[] = [
                                            'sku'       => $formatted_product->sku,
                                            'reason'    => 'ingredient not found, Name on Dear: ' . $dear_item->Name . ' Code on Dear: ' . $dear_item->ProductCode
                                        ];
                                        break;
                                    }
                                    foreach ($recipe_items as $item) {
                                        if ($item->product_id == $product->id) {
                                            if (round($dear_item->Quantity * 100, 2) != $item->percent) {
                                                $diff_list[] = [
                                                    'sku'       => $formatted_product->sku,
                                                    'reason'    => 'ingredient: ' . $product->name . '(' . $product->sku . ') - ' .
                                                        'diff:
                                                                        DEAR: ' . round($dear_item->Quantity * 100, 2) . '% |
                                                                        INTRANET: ' . round($item->percent, 2) . '%'
                                                ];
                                            }
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $flagLoop = $total > $this->limit && $page <= ($total / $this->limit);
                $page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        return $diff_list;
    }

    public function syncRecipes($sku = null)
    {

        $lastUpdate = Import::where('name', 'DEAR_SYNC_RECIPE')->orderBy('created_at', 'desc')->first();
        $updateDate = $lastUpdate ? $lastUpdate->created_at->toIso8601String() : null;

        $flagLoop = FALSE;
        $total = 0;
        $page = 1;
        $count = 0;

        do {
            $filters = [
                'Page'              => $page,
                'Limit'             => $this->limit,
                'IncludeBOM'        => 'true',
                'IncludeDeprecated' => 0,
                'ModifiedSince'     => $updateDate
            ];
            if (!empty($sku)) {
                $filters['Sku'] = $sku;
            }

            $dear_result = $this->makeRequest('product', $filters);

            if ($dear_result->status) {
                $total = $dear_result->Total;

                foreach ($dear_result->Products as $product) {
                    if (in_array($product->Category, ['STLTH PAILS', 'MIXTURE', 'MIXTURE EXTRACT', 'NYX MIXTURE', 'OEM MIXTURE'])) {

                        $formatted_product = $this->formatProduct($product);

                        if (!empty($formatted_product->brand)) {

                            $recipe = Recipe::where('sku', $formatted_product->sku)->first();

                            // NEW RECIPE
                            if (!$recipe) {

                                $brand = Brand::firstOrCreate([
                                    'name' => $formatted_product->brand
                                ]);

                                $recipe = Recipe::create([
                                    'dear'              => $product->ID,
                                    'sku'               => $formatted_product->sku,
                                    'strength'          => $formatted_product->strength,
                                    'size'              => $formatted_product->size,
                                    'name'              => $formatted_product->name,
                                    'author_id'         => $this->user->id,
                                    'brand_id'          => $brand->id,
                                    'last_updater_id'   => $this->user->id,
                                    'status'            => Recipe::NEW_RECIPE
                                ]);
                                $count++;
                                $recipe_items = $product->BillOfMaterialsProducts;
                                foreach ($recipe_items as $recipe_item) {
                                    $our_product = $product = Product::where('dear', $recipe_item->ComponentProductID)->first();
                                    if ($our_product) {
                                        RecipeItems::create([
                                            'recipe_id'         => $recipe->id,
                                            'product_id'        => $our_product->id,
                                            'percent'           => round($recipe_item->Quantity * 100, 2),
                                            'initial_percent'   => round($recipe_item->Quantity * 100, 2)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                $flagLoop = $total > $this->limit && $page <= ($total / $this->limit);
                $page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        if (!empty($sku)) {
            return $product;
        } else {
            return $count;
        }
    }

    private function getProductByDearID($dear_id)
    {

        $product = Product::where('dear', $dear_id)->first();

        if (!$product) {
            $filters = [
                'Page'  => 1,
                'Limit' => 1,
                'ID'    => $dear_id
            ];

            $dear_result = $this->makeRequest('product', $filters);

            if ($dear_result->status) {
                if ($dear_result->Total > 0) {
                    if ($dear_result->Products[0]->Status != 'Deprecated') {
                        $first_product = $this->formatProduct($dear_result->Products[0]);
                        $product = $this->syncProds($first_product->sku);
                    }
                }
            }
        }

        return $product;
    }

    public function getPurchase($search){

        $result         = new stdClass;
        $result->status = FALSE;

        $purchase_list_result = $this->makeRequest('purchaseList', [
            'Page'              => 1,
            'Limit'             => 1,
            'Search'            => $search
        ]);

        if($purchase_list_result->Total > 0){

            $supplier = Supplier::firstOrCreate(
                ['dear' => $purchase_list_result->PurchaseList[0]->SupplierID],
                ['name' => $purchase_list_result->PurchaseList[0]->Supplier]
            );

            $result->data               = new stdClass;
            $result->data->supplier_id  = $supplier->id;
            $result->data->author_id    = NULL;
            $result->data->supplier_name= $supplier->name;
            $result->data->po_number    = $purchase_list_result->PurchaseList[0]->OrderNumber;
            $created_at                 = new Carbon($purchase_list_result->PurchaseList[0]->OrderDate);
            $updated_at                 = new Carbon($purchase_list_result->PurchaseList[0]->LastUpdatedDate);
            $result->data->created_at   = $created_at->format('Y-m-d H:i:s');
            $result->data->updated_at   = $updated_at->format('Y-m-d H:i:s');
            $result->data->started_at   = NULL;
            $result->data->finished_at  = NULL;
            $result->data->status       = NULL;

            $purchase_result = $this->makeRequest('purchase/order', [
                'Page'      => 1,
                'Limit'     => 1,
                'TaskID'    => $purchase_list_result->PurchaseList[0]->ID
            ]);

            $result->data->items = [];

            if(count($purchase_result->Lines) > 0){
                $result->status = TRUE;
                foreach ($purchase_result->Lines as $item) {

                    $product = Product::where('dear', $item->ProductID)->first();
                    if($product){
                        $new_item                   = new stdClass;
                        $new_item->product_id       = $product->id;
                        $new_item->name             = $item->SKU . ' - '. $item->Name;
                        $new_item->container1_id    = NULL;
                        $new_item->container2_id    = NULL;
                        $new_item->original_qty     = $item->Quantity;
                        $new_item->container1_qty   = 0;
                        $new_item->container2_qty   = 0;
                        $new_item->total            = 0;
                        $new_item->sum_total        = 0;
                        $new_item->child            = FALSE;
                        $result->data->items[]  = $new_item;
                    }
                }
            }
        }

        return $result;
    }

    public function makeRequest($uri, $params)
    {
        try {
            $response = $this->client->get($uri, [
                'headers' => [
                    'Content-type' => 'application/json',
                    'api-auth-accountid' => $this->dear_id,
                    'api-auth-applicationkey' => $this->dear_key
                ],
                'query' => $params
            ]);

            $body = $response->getBody();
            $result = json_decode($body);
            $result->status = TRUE;
            return $result;
        } catch (ClientException $e) {
            $response               = new \stdClass();
            $response->status       = FALSE;
            $response->message      = $e->getResponse();
            $response->request      = $e->getRequest();
            return $response;
        }
    }

    public function formatProduct($product)
    {

        //$percent_regex  = "/[\d]+\s*%{1}/";
        $percent_regex  = "/\d+(?:\.\d+)?%/";

        //^[1-9]\d*(\.\d+)?$

        $mg_regex       = "/[\d]+\s*MG{1}/";
        $ml_regex       = "/[\d]+\s*ML{1}/";

        $prod_name = removeFromString(trim(strtoupper($product->Name)), '- MIXTURE');

        $strength_found = preg_match($mg_regex, $prod_name, $strength);
        $size_found = preg_match($ml_regex, $prod_name, $size);
        $percent_found = preg_match($percent_regex, $prod_name, $percent);


        if ($percent_found) {
            $new_strength = (floatval($percent[0]) * 10) . 'MG';
            $prod_name = removeFromString($prod_name, $percent[0]);
        } elseif ($strength_found) {
            $new_strength = floatval($strength[0]) . 'MG';
            $prod_name = removeFromString($prod_name, $strength[0]);
        } else {
            $new_strength = '0MG';
        }

        $new_size = '0ML';
        if ($size_found) {
            $new_size = intval($size[0]) . 'ML';
            $prod_name = removeFromString($prod_name, $size[0]);
        }

        // remove [By brand]
        $brand_found = stristr($prod_name, ' By ', true);
        $final_name = $brand_found ? $brand_found : $prod_name;
        $final_name = removeFromString($final_name, '()');
        $final_name = str_replace('  ', ' ', $final_name);

        $new_product            = new \stdClass();
        $new_product->sku       = trim($product->SKU);
        $new_product->name      = formatName($final_name);
        $new_product->size      = intval($new_size);
        $new_product->strength  = intval($new_strength);
        $new_product->category  = formatName($product->Category);
        $new_product->brand     = formatName($product->Brand);
        $new_product->barcode   = trim($product->Barcode);
        $new_product->density   = $product->InternalNote ? number_format(floatval(trim($product->InternalNote)), 2) : null;

        return $new_product;
    }
}
