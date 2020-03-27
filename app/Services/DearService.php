<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DearService{


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
        $this->user = auth()->user() ?? User::where('name', 'dear')->first();
    }

    public function syncProds($sku = null){

        $result = FALSE;
        $flagLoop = FALSE;
        $total = 0;
        $page = 1;
        $count = 0;

        do {

            $filters = [
                'Page'              => $page,
                'Limit'             => !empty($sku) ? 1 : $this->limit,
                'includeDeprecated' => 0,
            ];
            if(!empty($sku)){
                $filters['Sku'] = $sku;
            }

            $categories_list = [];
            $brands_list = [];

            $dear_result = $this->makeRequest('product', $filters);

            if ($dear_result->status) {
                $total = $dear_result->Total;
                foreach ($dear_result->Products as $prod) {
                    //$prod
                    $formated_product = $this->formatProduct($prod);

                    // CATEGORY HANDLE
                    if(!isset($categories_list[$formated_product->category])){
                        $category = ProductCategory::where('name', $formated_product->category)
                        ->whereNotNull('dear')
                        ->first();
                        if(!$category){
                            $category = $this->syncCategories($formated_product->category);
                        }
                        $categories_list[$formated_product->category] = $category;
                    }
                    else{
                        $category = $categories_list[$formated_product->category];
                    }

                    // BRAND HANDLE
                    if(!isset($brands_list[$formated_product->brand])){
                        $brand = Brand::where('name', $formated_product->brand)
                        ->whereNotNull('dear')
                        ->first();
                        if(!$brand){
                            $brand = $this->syncBrands($formated_product->brand);
                        }
                        $brands_list[$formated_product->brand] = $brand;
                    }
                    else{
                        $brand = $brands_list[$formated_product->brand];
                    }

                    //@todo add author to know who did this
                    $new_prod = Product::updateOrCreate(
                        ['dear' => $prod->ID],
                        [
                            'category_id'   => $category->id,
                            'brand_id'      => $brand->id ?? null,
                            'sku'           => $formated_product->sku,
                            'name'          => $formated_product->name,
                            'strength'      => $formated_product->strength,
                            'size'          => $formated_product->size,
                        ]
                    );
                    $count++;

                    if(!empty($sku)){
                        $result = $new_prod;
                    }
                    else{
                        $result = $count;
                    }
                }
                $flagLoop = $total > $this->limit && $page <= ($total / $this->limit);
                $page++;
            }else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        return $result;
    }

    public function syncCategories($name = NULL){

        $page = 1;
        $final_item = FALSE;
        $filters = [
            'Page'              => $page,
            'Limit'             => !empty($name) ? 1 : $this->limit
        ];
        if(!empty($name)){
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

    public function syncBrands($name = NULL){

        $page = 1;
        $final_item = FALSE;
        $filters = [
            'Page'              => $page,
            'Limit'             => !empty($name) ? 1 : $this->limit
        ];
        if(!empty($name)){
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

        $new_product = new \stdClass();
        $new_product->sku = trim($product->SKU);
        $new_product->name = formatName($final_name);
        $new_product->size = intval($new_size);
        $new_product->strength = intval($new_strength);
        $new_product->category = formatName($product->Category);
        $new_product->brand = formatName($product->Brand);

        return $new_product;
    }
}
