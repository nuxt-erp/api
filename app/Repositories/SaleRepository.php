<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\SaleDetails;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Province;
use App\Models\Country;
use App\Models\ShopifySync;
use Illuminate\Support\Facades\DB;
use App\Traits\StockTrait;


class SaleRepository extends RepositoryService
{
    use StockTrait;
    private $shopify;
    private $config;
    private $sync_user_id;
    private $company_id;

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'order_number',
            'direction'     => 'desc'
        ];

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', Arr::pull($searchCriteria, 'id'));
        }

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    private function connectShopifyStore($data)
    {

        // SHOPIFY SETTINGS
        $this->config = array(
            'ShopUrl'    => $data->shopify_store_name . '.myshopify.com',
            'ApiKey'     => $data->shopify_api_key,
            'Password'   => $data->shopify_api_pwd,
            'ApiVersion' => $data->shopify_api_version
        );

        $this->shopify = new \PHPShopify\ShopifySDK($this->config);

    }

    public function importShopifyOrders()
    {

        // date_default_timezone_set('America/Toronto');
        $time_zone = '-4:00';

        // -2 MINUTES
        $date = date('Y-m-d\TH:i:s',strtotime('-2 minutes',strtotime(date('Y-m-d\TH:i:s'))));

        $orders     = [];

        $params = [
            'processed_at_min' => $date  . $time_zone,
            'processed_at_max' => date('Y-m-d\TH:i:s')  . $time_zone,
            'limit'            => 250
        ];

        $orders[0] = $this->shopify->Order->get($params);

        // RETURN ARRAY WITH ALL ORDERS
        return $orders;
    }

    private function checkProvince($country_id, $short, $name)
    {
        $province = Province::firstOrCreate(['country_id' => $country_id, 'short_name' => $short, 'name' => $name]);
        return $province->id;
    }

    private function checkCountry($name)
    {
        $country = Country::firstOrCreate(['name' => $name]);
        return $country->id;
    }

    private function checkCustomer($data)
    {
        $customer_id = Customer::where('customer_shopify_id', $data["customer"]["id"])->pluck('id')->first();

        // NOT FOUND. CREATE A NEW ONE
        if (!$customer_id)
        {
            $new                        = new Customer;
            $new->company_id            = $this->company_id;
            $new->customer_shopify_id   = $data["customer"]["id"];
            $new->name                  = $data["customer"]["default_address"]["first_name"] . ' ' . $data["customer"]["default_address"]["last_name"];
            $new->address1              = $data["customer"]["default_address"]["address1"];
            $new->address2              = $data["customer"]["default_address"]["address2"];
            $new->email                 = $data["customer"]["email"];
            $new->notes                 = $data["customer"]["note"];
            $new->country_id            = $this->checkCountry($data["customer"]["default_address"]["country"]);
            $new->province_id           = $this->checkProvince($new->country_id, $data["customer"]["default_address"]["province_code"], $data["customer"]["default_address"]["province"]);
            $new->city                  = $data["customer"]["default_address"]["city"];
            $new->postal_code           = $data["customer"]["default_address"]["zip"];
            $new->phone_number          = $data["customer"]["default_address"]["phone"];
            $new->save();
            $customer_id                = $new->id;
        }
        return $customer_id;
    }

    public function importShopify()
    {
        // READ ALL SHOPIFY STORES SETTINGS
        $all_stores = ShopifySync::all();
        $orders     = [];

        foreach ($all_stores as $store)
        {
            // CONECT STORE TO THE SHOPIFY
            $this->connectShopifyStore($store);
            $this->sync_user_id = $store->sync_user_id;
            $this->company_id   = $store->company_id;
            $orders             = $this->importShopifyOrders();

            // INIT VARIABLES
            $data               = [];
            $parse_items        = [];
            $qty_created        = 0;

            // START TRANSACTION TO SAVE SALE AND SALE DETAILS
            DB::transaction(function () use ($data, $orders, $parse_items, $qty_created, $store)
            {
                foreach ($orders as $level0)
                {
                    foreach ($level0 as $level1)
                    {
                        $qty_created++;

                        // CHECK IF CUSTOMER EXIST
                        $customer_id = $this->checkCustomer($level1);

                        // GET ORDER HEADER
                        $data["order_number"]       = str_replace('#', '', $level1["name"]);
                        $data["customer_id"]        = $customer_id;
                        $data["sales_date"]         = $level1["processed_at"];
                        $data["financial_status"]   = ($level1["financial_status"] == "pending" ? 0 : 1);
                        $data["user_id"]            = $this->sync_user_id;
                        $data["company_id"]         = $this->company_id;
                        $data["subtotal"]           = $level1["subtotal_price"];
                        $data["discount"]           = $level1["total_discounts"];
                        $data["taxes"]              = $level1["total_tax"];
                        $data["shipping"]           = isset($level1["shipping_lines"][0]["price"]) ? $level1["shipping_lines"][0]["price"] : 0;
                        $data["total"]              = $level1["total_price"];
                        $data["order_status_label"] = "";

                        // CHECK IF SALE WAS IMPORTED BEFORE
                        $sale_id = Sale::where('order_number', str_replace('#', '', $level1["name"]))->pluck('id')->first();

                        // ALREADY EXIST - UPDATE
                        if ($sale_id) {
                            Sale::where(['id' => $sale_id, 'company_id' => $this->company_id])->update([
                                'financial_status'  => ($level1["financial_status"] == "pending" ? 0 : 1),
                                'user_id'           => $this->sync_user_id,
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
                                    'fulfillment_status'    => $items["fulfillment_status"]
                                ]);
                            }
                        }

                        // SET DATA VARIABLE PARSED ITEMS
                        $data["list_products"] = $parse_items;
                        // SAVE SALE PRODUCTS
                        $this->saveSaleDetails($data, $sale_id);
                        $parse_items = [];
                    }
                }
            });

        }

        return true;
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

                    // HERE READ FULFILLMENT
                    // COULD BE ONE OR MANY FULFILLMENTS

                    /*if ($qty == $qty_fulfilled) { // WHEN FULFILLED PRODUCT, UPDATE STOCK AVAILABILITY
                        $this->updateStock($product_id, $qty, $data["location_id"], "-"); // DECREASE STOCK
                    }*/
                }

            }
        }

        return $total;
    }

    public function remove($id)
    {
        DB::transaction(function () use ($id)
        {
            $getItem = Sale::where('id', $id)->with('details')->get();

            foreach ($getItem[0]->details as $value)
            {
                // DECREMENT STOCK
                $this->updateStock($value->product_id, $value->qty_received, $getItem[0]->location_id, "+");
            }

            // parent::delete($id);
            Sale::where('id', $id)->delete();

        });
    }
}
