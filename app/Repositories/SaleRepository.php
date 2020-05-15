<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\SaleDetails;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Traits\StockTrait;
// use App\Traits\ImportShopifyOrdersTrait;

class SaleRepository extends RepositoryService
{
    use StockTrait;
    // use ImportShopifyOrdersTrait;

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', Arr::pull($searchCriteria, 'id'));
        }

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function importShopifyOrders()
    {
        // SHOPIFY SETTINGS
        $config = array(
            'ShopUrl'    => env('API_SHOPIFY_STORE_NAME') . '.myshopify.com',
            'ApiKey'     => env('API_SHOPIFY_KEY'),
            'Password'   => env('API_SHOPIFY_PASSWORD'),
            'ApiVersion' => env('API_SHOPIFY_VERSION')
        );

        $shopify = new \PHPShopify\ShopifySDK($config);

        // date_default_timezone_set('America/Toronto');
        $time_zone = '+4:00';

        // CURRENT DATE TIME
        $date = date('Y-m-d\TH:i:s');
        // -1 MINUTE BEFORE
        $minutes_before = date('Y-m-d\TH:i:s', strtotime($date. ' - 40 minutes'));
        // SHOPIFY QUERY PARAM
        $params = [
            'processed_at_min' => $minutes_before . $time_zone,
            'processed_at_max' => date('Y-m-d\TH:i:s')  . $time_zone
        ];
        // TOTAL ORDERS FOUND
        $tot        = $shopify->Order()->count($params);
        // PAGE LIMIT
        $limit      = 100;
        // CALC TOTAL PAGES NEEDED
        $totalpage  = ceil($tot/$limit);
        $orders     = [];

        // LOAD RESULTS PAGE BY PAGE
        for($i=1; $i<=$totalpage; $i++)
        {
            $params = [
                'created_at_min' => $minutes_before  . $time_zone,
                'created_at_max' => date('Y-m-d\TH:i:s')  . $time_zone,
                'limit'          => 100
            ];
            $orders[$i] = $shopify->Order->get($params);
        }
        // RETURN ARRAY WITH ALL ORDERS
        return $orders;
    }

    public function importShopify()
    {
        $orders         = $this->importShopifyOrders();
        $data           = [];
        $parse_items    = [];
        $qty_created    = 0;

        // START TRANSACTION TO SAVE SALE AND SALE DETAILS
        DB::transaction(function () use ($data, $orders, $parse_items, $qty_created)
        {
            foreach ($orders as $level0)
            {
                foreach ($level0 as $level1)
                {
                    $qty_created++;

                    // GET ORDER HEADER
                    $data["order_number"]       = $level1["name"];
                    $data["customer_id"]        = 1; //$level1["customer"]["id"];
                    $data["sales_date"]         = $level1["processed_at"];
                    $data["financial_status"]   = ($level1["financial_status"] == "pending" ? 0 : 1);
                    $data["user_id"]            = Auth::user()->id;
                    $data["company_id"]         = Auth::user()->company_id;
                    $data["subtotal"]           = $level1["subtotal_price"];
                    $data["discount"]           = $level1["total_discounts"];
                    $data["taxes"]              = $level1["total_tax"];
                    $data["shipping"]           = isset($level1["shipping_lines"][0]["price"]) ? $level1["shipping_lines"][0]["price"] : 0;
                    $data["total"]              = $level1["total_price"];
                    $data["order_status_label"] = ""; //$level1["name"];

                    // CHECK IF SALE WAS IMPORTED BEFORE
                    $sale_id = Sale::where('order_number', $level1["name"])->pluck('id')->first();

                    // ALREADY EXIST - UPDATE
                    if ($sale_id) {
                        Sale::where(['id' => $sale_id, 'company_id' => Auth::user()->company_id])->update([
                            'financial_status'  => ($level1["financial_status"] == "pending" ? 0 : 1),
                            'user_id'           => Auth::user()->id,
                            'subtotal'          => $level1["subtotal_price"],
                            'discount'          => $level1["total_discounts"],
                            'taxes'             => $level1["total_tax"],
                            'shipping'          => isset($level1["shipping_lines"][0]["price"]) ? $level1["shipping_lines"][0]["price"] : 0,
                            'total'             => $level1["total_price"],
                        ]);
                    } else {
                        parent::store($data);
                        $sale_id = $this->model->id; // GET ID FROM SALE CREATED
                    }

                    // PARSE PRODUCTS
                    foreach ($level1["line_items"] as $items)
                    {
                        $product_id = Product::where('sku', $items["sku"])->pluck('id')->first();

                        if ($product_id) {
                            array_push($parse_items,
                            [
                                'sale_id'               => $sale_id,
                                'product_id'            => $product_id,
                                'qty'                   => $items["quantity"],
                                'price'                 => $items["price"],
                                'discount_value'        => $items["total_discount"],
                                'total_item'            => ($items["quantity"] * $items["price"]),
                                'shopify_lineitem'      => $items["id"],
                                'qty_fulfilled'         => $items["fulfillable_quantity"],
                                'fulfillment_status'    => $items["fulfillment_status"]
                            ]);
                        }
                    }

                    // SET DATA VARIABLE PARSED ITEMS
                    $data["list_products"] = $parse_items;
                    // SAVE SALE PRODUCTS
                    $this->saveSaleDetails($data, $sale_id);
                }
            }
        });

        return $qty_created;
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $data["company_id"] = Auth::user()->company_id;
            parent::store($data);
            // SAVE SALE DETAILS
            $this->saveSaleDetails($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->saveSaleDetails($data, $this->model->id);
        });
    }

    private function saveSaleDetails($data, $id)
    {

        if (isset($data["list_products"]))
        {
            $object = $data["list_products"];

            // DELETE ITEMS TO INSERT THEM AGAIN
            SaleDetails::where('sale_id', $id)->delete();

            foreach ($object as $k => $v) // EACH ROW
            {
                $qty                = 0;
                $total              = 0;
                $total_item         = 0;
                $qty_fulfilled      = 0;
                $discount_value     = 0;
                $price              = 0;
                $shopify_lineitem   = "";
                $fulfillment_status = "";
                $product_id         = 0;

                foreach ($v as $key => $value) // EACH ATTRIBUTE
                {
                    if ($key == 'qty') {
                        $qty = $value;
                    }

                    if ($key == 'qty_fulfilled') {
                        $qty_fulfilled = $value;
                    }

                    if ($key == 'shopify_lineitem') {
                        $shopify_lineitem = $value;
                    }

                    if ($key == 'fulfillment_status') {
                        $fulfillment_status = $value;
                    }

                    if ($key == 'price') {
                        $price = $value;
                    }

                    if ($key == 'discount_value') {
                        $discount_value = $value;
                    }

                    if ($key == 'product_id') {
                        $product_id = $value;
                    }
                }

                if ($product_id)
                {
                    $total_item = ($price * $qty);

                    SaleDetails::updateOrCreate([
                        'sale_id'               => $id,
                        'product_id'            => $product_id],
                    [
                        'qty'                   => $qty,
                        'qty_fulfilled'         => $qty_fulfilled,
                        'shopify_lineitem'      => $shopify_lineitem,
                        'fulfillment_status'    => ($fulfillment_status == "fulfilled" ? 1 : 0),
                        'discount_value'        => $discount_value,
                        'price'                 => $price,
                        'total_item'            => $total_item,
                    ]);

                    $total += $total_item;

                    /*if ($qty == $qty_fulfilled) { // WHEN FULFILLED PRODUCT, UPDATE STOCK AVAILABILITY
                        $this->updateStock($product_id, $qty, $data["location_id"], "-"); // DECREASE STOCK
                    }*/
                }

            }
        }

        return $total;
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id)
        {
            $parseId = $id["id"];
            $getItem = Sale::where('id', $parseId)->with('details')->get();

            foreach ($getItem[0]->details as $value)
            {
                // DECREMENT STOCK
                $this->updateStock($value->product_id, $value->qty_received, $getItem[0]->location_id, "-");
            }

            parent::delete($id);

        });
    }
}
