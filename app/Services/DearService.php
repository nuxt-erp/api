<?php

namespace App\Services;

use App\Exceptions\DearException;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use App\Models\Config;
use Modules\Inventory\Entities\Attribute;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductAttributes;
use App\Models\Location;
use App\Models\Supplier;
use Modules\Inventory\Entities\LocationBin;

class DearService
{

    private $config;
    private $client;
    private $limit;
    private $user;

    public function __construct()
    {
        $this->config = Config::first();
        if ($this->config) {
            $this->client = new Client([
                'base_uri' => $this->config->dear_url,
            ]);
            $this->limit = 1000;
            $this->user = auth()->user() ?? User::where('name', 'admin')->first();
        }
        else{
            throw new DearException('config_not_found');
        }
    }

    public function syncProds($sku = null)
    {

        $result     = FALSE;
        $flagLoop   = FALSE;
        $total      = 0;
        $page       = 1;
        $count      = 0;

        // GET STRENGTH ATTRIBUTE ID
        $strength_id  = Attribute::where('code', 'str')
        ->pluck('id')->first();

        // GET SIZE ATTRIBUTE ID
        $size_id  = Attribute::where('code', 'size')
        ->pluck('id')->first();

        $categories_list    = [];
        $brands_list        = [];

        do {

            $filters = [
                'Page'              => $page,
                'Limit'             => !empty($sku) ? 1 : $this->limit,
                'includeDeprecated' => 0,
            ];
            if(!empty($sku)){
                $filters['Sku'] = $sku;
            }

            $dear_result = cache()->remember('products_'.implode('_', $filters), 60 * 60, function () use($filters) {
                return $this->makeRequest('product', $filters);
            });

            if ($dear_result->status && $dear_result->Total > 0) {
                $total = $dear_result->Total;
                foreach ($dear_result->Products as $prod) {
                    /*
                        formatted product example:
                        [sku] => _1_
                        [name] => Sales
                        [size] => 0
                        [strength] => 0
                        [category] => Other
                        [brand] =>
                        [description] =>
                        [barcode] =>
                        [dear] => 3c31a536-779e-4317-978d-1689dab49e0c
                    */
                    $formatted_product = $this->formatProduct($prod);

                    // CATEGORY HANDLE
                    $category = null;
                    if(!empty($formatted_product->category)){
                        if(!isset($categories_list[$formatted_product->category])){
                            $category = Category::firstOrCreate(['name' => $formatted_product->category]);
                            $categories_list[$formatted_product->category] = $category;
                        }
                        else{
                            $category = $categories_list[$formatted_product->category];
                        }
                    }


                    // BRAND HANDLE
                    $brand = null;
                    if(!empty($formatted_product->brand)){
                        if(!isset($brands_list[$formatted_product->brand])){
                            //if($config->dear_sync_existing_brands){
                            $brand = Brand::firstOrCreate(['name' => $formatted_product->brand]);
                            $brands_list[$formatted_product->brand] = $brand;
                        }
                        else{
                            $brand = $brands_list[$formatted_product->brand];
                        }
                    }

                    $values = [
                        'category_id'   => $category->id ?? null,
                        'brand_id'      => $brand->id ?? null,
                        'name'          => $formatted_product->name,
                        'description'   => $formatted_product->description,
                        'barcode'       => $formatted_product->barcode ?? null
                    ];

                    if($this->config->dear_sync_existing_products){
                        $new_prod = Product::updateOrCreate(
                            ['sku' => $formatted_product->sku],
                            $values
                        );
                    }
                    else{
                        $new_prod = Product::firstOrCreate(
                            ['sku' => $formatted_product->sku],
                            $values
                        );
                    }


                    // SAVE ATTRIBUTE BY PRODUCT
                    if(!empty($formatted_product->strength)){
                        if($this->config->dear_sync_existing_product_strengths){
                            ProductAttributes::updateOrCreate([
                                'product_id'    => $new_prod->id,
                                'attribute_id'  => $strength_id,
                            ],
                            [
                                'value'         => $formatted_product->strength
                            ]);
                        }
                        else{
                            ProductAttributes::firstOrCreate([
                                'product_id'    => $new_prod->id,
                                'attribute_id'  => $strength_id,
                            ],
                            [
                                'value'         => $formatted_product->strength
                            ]);
                        }
                    }

                    // SAVE ATTRIBUTE BY PRODUCT
                    if(!empty($formatted_product->size)){
                        if($this->config->dear_sync_existing_product_sizes){
                            ProductAttributes::updateOrCreate([
                                'product_id'    => $new_prod->id,
                                'attribute_id'  => $size_id,
                            ],
                            [
                                'value'         => $formatted_product->size
                            ]);
                        }
                        else{
                            ProductAttributes::firstOrCreate([
                                'product_id'    => $new_prod->id,
                                'attribute_id'  => $size_id,
                            ],
                            [
                                'value'         => $formatted_product->size
                            ]);
                        }
                    }


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

    public function syncSuppliers($name = NULL)
    {
        $page = 1;
        $final_item = FALSE;
        $filters = [
            'Page'              => $page,
            'Limit'             => !empty($name) ? 1 : $this->limit
        ];
        if(!empty($name)){
            $filters['Name'] = $name;
        }

        $dear_result = $this->makeRequest('supplier', $filters);

        if ($dear_result->status) {
            foreach ($dear_result->SupplierList as $item) {
                $final_item = Supplier::updateOrCreate(['name' => formatName($item->Name)]);
            }
        }

        return $final_item;
    }

    public function syncCategories($name = NULL)
    {
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
                $final_item = Category::updateOrCreate(['name' => formatName($item->Name)]);
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
        if(!empty($name)){
            $filters['Name'] = $name;
        }

        $dear_result = $this->makeRequest('ref/brand', $filters);

        if ($dear_result->status) {
            foreach ($dear_result->BrandList as $item) {
                $final_item = Brand::updateOrCreate(['name' => formatName($item->Name)]);
            }
        }

        return $final_item;
    }

    public function syncAvailability()
    {
        $flagLoop   = FALSE;
        $total      = 0;
        $page       = 1;
        $count      = 0;

        // Sync locations - it's important for availabilities
        $this->syncLocations();

        $location_list = [];
        $bin_list      = [];

        do
        {
            $filters = [
                'Page'  => $page,
                'Limit' => $this->limit
            ];

            $result = cache()->remember('availability_'.implode('_', $filters), 60 * 60, function () use($filters) {
                return $this->makeRequest('ref/productavailability', $filters);
            });

            if ($result->status){

                $total = $result->Total;
                $list  = $result->ProductAvailabilityList;
                if ($total > 0) {
                    foreach ($list as $item) {

                        $product = Product::where('sku', trim($item->SKU))->first();

                         // LOCATION HANDLE
                        $location = null;
                        if(!empty($item->Location)){
                            $location_name = formatName($item->Location);
                            if(!isset($location_list[$location_name])){
                                $location                       = Location::firstOrCreate(['name' => $location_name]);
                                $location_list[$location_name]  = $location;
                            }
                            else{
                                $location                       = $location_list[$location_name];
                            }
                        }

                        // BIN HANDLE
                        $bin = null;
                        if($location && !empty($item->Bin)){
                            $binName = formatName($item->Bin);
                            if(!isset($bin_list[$binName])){
                                $bin                = LocationBin::firstOrCreate(['location_id'   => $location->id, 'name' => $binName]);
                                $bin_list[$binName] = $location;
                            }
                            else{
                                $bin                = $bin_list[$binName];
                            }
                        }

                        if ($product)
                        {
                            if($this->config->dear_sync_existing_availabilities){
                                Availability::updateOrCreate(
                                    [
                                        'product_id'    => $product->id,
                                        'location_id'   => $location->id ?? null,
                                        'bin_id'        => $bin->id ?? null,
                                    ],
                                    [
                                        'available'     => $item->Available ?? 0,
                                        'on_hand'       => $item->OnHand ?? 0,
                                        //'allocated'     => $item->Allocated ?? 0,
                                        //'on_order'      => $item->OnOrder ?? 0,
                                    ]
                                );
                            }
                            else{
                                Availability::firstOrCreate(
                                    [
                                        'product_id'    => $product->id,
                                        'location_id'   => $location->id ?? null,
                                        'bin_id'        => $bin->id ?? null,
                                    ],
                                    [
                                        'available'     => $item->Available ?? 0,
                                        'on_hand'       => $item->OnHand ?? 0,
                                        //'allocated'     => $item->Allocated ?? 0,
                                        //'on_order'      => $item->OnOrder ?? 0,
                                    ]
                                );
                            }
                            $count++;
                        }

                    }
                }

                $flagLoop = $total > $this->limit && $page <= ($total / $this->limit);
                $page++;
            } else {
                $flagLoop = FALSE;
            }
        } while ($flagLoop);

        return $count;

    }

    public function syncLocations($name = null)
    {
        $page       = 1;
        $count      = 0;
        $result     = 0;

        $filters = [
            'Page'              => $page,
            'Limit'             => !empty($name) ? 1 : $this->limit // sync one or sync all
        ];

        if(!empty($name))
        {
            $filters['Name'] = $name;
        }

        $dear_result = cache()->remember('location_'.implode('_', $filters), 60 * 60, function () use($filters) {
            return $this->makeRequest('ref/location', $filters);
        });

        if ($dear_result->status)
        {
            foreach ($dear_result->LocationList as $item)
            {
                $location = Location::firstOrCreate(['name' => formatName($item->Name)]);
                $count++;
            }

            // if sync one item, return the item
            if(!empty($name)){
                $result = $location;
            }
            // else return the count of inserted/updated items
            else{
                $result = $count;
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
                    'api-auth-accountid' => $this->config->dear_id,
                    'api-auth-applicationkey' => $this->config->dear_key
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
        $new_product->description = $product->Description;
        $new_product->barcode = $product->Barcode;
        $new_product->dear = $product->ID;

        return $new_product;
    }

}
