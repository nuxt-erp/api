<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;

class ShopifyController extends ControllerService
{
    private $config;
    private $shopify;
    private $shopify_collection;

    public function __construct()
    {
        $this->config = array(
            'ShopUrl'   => env('API_SHOPIFY_STORE_NAME') . '.myshopify.com',
            'ApiKey'    => env('API_SHOPIFY_KEY'),
            'Password'  => env('API_SHOPIFY_PASSWORD'),
            'ApiVersion' => '2019-10'
        );

        $this->shopify = new \PHPShopify\ShopifySDK($this->config);
    }


    /*
    * https://help.shopify.com/en/api/reference/orders/order?api[version]=2019-10#index-2019-10
    */
    public function getShopifyOrder(array $params = ['fulfillment_status' => 'partial']) {

        $this->shopify_collection = $this->shopify->Order->get($params);

        if ($this->shopify_collection) {
            return $this->getFilteredItems();
        } else {
            return null;
        }

    }


    private function checkItem($sku) {

        //ONLY CATEGORIES 9,10 (E-Liquid)
        $get = Product::where('sku', $sku)
        ->whereIn('category_id', [9,10])
        ->first();

        return $get;

    }

    protected function getFilteredItems() {

        $return_order   = [];
        $return_items   = [];
        $return_summary = [];

        foreach ($this->shopify_collection as $order) { //READ ALL ORDERS

            foreach ($order["line_items"] as $key => $value) { //READ ORDER ITEMS

                if($value['fulfillment_status']!="fulfilled") { //ONLY PARTIAL FULFILLED

                    if ($this->checkItem($value['sku'])) { //CHECK IF PRODUCT CATEGORY IS LIQUID

                        array_push($return_items,
                        [
                            'order_name' => $order['name'],
                            'item' => $value
                        ]);

                    }

                }

            }

        }


        array_push($return_summary,
        [
           'orders' => $return_items,
           'total' => count($return_items)
        ]);

        return ($return_summary);

    }

}
