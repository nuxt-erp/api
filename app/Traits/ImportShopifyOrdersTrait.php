<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Response;

trait ImportShopifyOrdersTrait
{
    public function __construct()
    {
        $this->config = array(
            'ShopUrl'    => env('API_SHOPIFY_STORE_NAME') . '.myshopify.com',
            'ApiKey'     => env('API_SHOPIFY_KEY'),
            'Password'   => env('API_SHOPIFY_PASSWORD'),
            'ApiVersion' => env('API_SHOPIFY_VERSION')
        );

        $this->shopify = new \PHPShopify\ShopifySDK($this->config);
    }

    public function importShopifyOrders()
    {
        $date = date('Y-m-d');  // CURRENT DATE
        $params = [
            'processed_at_min' => $date,
            'processed_at_max' => date('Y-m-d', strtotime($date. ' + 1 days'))
        ];

        $tot        = $this->shopify->Order()->count($params);
        $limit      = 100;
        $totalpage  = ceil($tot/$limit);
        $orders     = [];

        for($i=1; $i<=$totalpage; $i++)
        {
            $params = [
                'created_at_min' => $date,
                'created_at_max' => date('Y-m-d', strtotime($date. ' + 1 days')),
                'limit'          => 100
            ];
            $orders[$i] = $this->shopify->Order->get($params);
        }

        return $orders;
    }

}
