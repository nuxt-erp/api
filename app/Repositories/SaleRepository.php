<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\SaleDetails;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Province;
use App\Models\Country;
use App\Models\ShopifySync;
use Illuminate\Support\Facades\DB;
use App\Traits\StockTrait;
use Illuminate\Support\Facades\Log;


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
        // Shopify settings
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

        // -2 minutes
        $date = date('Y-m-d\TH:i:s',strtotime('-2 minutes',strtotime(date('Y-m-d\TH:i:s'))));

        $orders     = [];

        $params = [
            'updated_at_min' => $date  . $time_zone,
            'updated_at_max' => date('Y-m-d\TH:i:s')  . $time_zone,
            'status'         => 'any',
            'limit'          => 250
        ];

        $orders[0] = $this->shopify->Order->get($params);

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

        // New Customer if not found
        if (!$customer_id) {
            $new                        = new Customer;
            $new->company_id            = $this->company_id;
            $new->customer_shopify_id   = $data["customer"]["id"];
            $new->name                  = $data["customer"]["default_address"]["first_name"] . ' ' . $data["customer"]["default_address"]["last_name"];
            $new->address1              = substr($data["customer"]["default_address"]["address1"],0,100);
            $new->address2              = substr($data["customer"]["default_address"]["address2"],0,100);
            $new->email                 = substr($data["customer"]["email"],0,160);
            $new->country_id            = $this->checkCountry($data["customer"]["default_address"]["country"]);
            $new->province_id           = $this->checkProvince($new->country_id, $data["customer"]["default_address"]["province_code"], $data["customer"]["default_address"]["province"]);
            $new->city                  = $data["customer"]["default_address"]["city"];
            $new->postal_code           = $data["customer"]["default_address"]["zip"];
            $new->phone_number          = substr($data["customer"]["default_address"]["phone"],0,20);
            $new->save();
            $customer_id                = $new->id;
        }
        return $customer_id;
    }

    /*
    *
    * Every minute check for orders on Shopify with any status
    *
    */
    public function importShopify()
    {
        // Load all Shopify stores
        $all_stores = ShopifySync::all();
        $orders     = [];

        foreach ($all_stores as $store) {

            // Connect to Shopify Store
            $this->connectShopifyStore($store);
            $this->sync_user_id = $store->sync_user_id;
            $this->company_id   = $store->company_id;
            $orders             = $this->importShopifyOrders();

            // Init variables
            $data               = [];
            $parse_items        = [];
            $parse_fulfillments = [];

            DB::transaction(function () use ($data, $orders, $parse_items, $store)
            {
                foreach ($orders as $level0) {

                    foreach ($level0 as $level1) {

                        // Find or create a customer
                        $customer_id = $this->checkCustomer($level1);

                        // Parse sale header
                        $data["order_number"]       = str_replace('#', '', $level1["name"]);
                        $data["customer_id"]        = $customer_id;
                        $data["sales_date"]         = date('Y-m-d H:i:s', strtotime($level1["processed_at"]));
                        $data["financial_status"]   = ($level1["financial_status"] == "pending" ? 0 : 1);
                        $data["fulfillment_status"] = ($level1["fulfillment_status"] == "fulfilled" ? 1 : 0);
                        $data["user_id"]            = $this->sync_user_id;
                        $data["company_id"]         = $this->company_id;
                        $data["subtotal"]           = $level1["subtotal_price"];
                        $data["discount"]           = $level1["total_discounts"];
                        $data["taxes"]              = $level1["total_tax"];
                        $data["shipping"]           = isset($level1["shipping_lines"][0]["price"]) ? $level1["shipping_lines"][0]["price"] : 0;
                        $data["total"]              = $level1["total_price"];
                        $data["order_status_label"] = "";

                        // Check order whether was imported or not
                        $sale_id = Sale::where('order_number', str_replace('#', '', $level1["name"]))->pluck('id')->first();

                        if ($sale_id) { // Update
                            Sale::where(['id' => $sale_id, 'company_id' => $this->company_id])->update([
                                'financial_status'      => ($level1["financial_status"] == "pending" ? 0 : 1),
                                'fulfillment_status'    => ($level1["fulfillment_status"] == "fulfilled" ? 1 : 0),
                                'user_id'               => $this->sync_user_id,
                                'subtotal'              => isset($level1["current_subtotal_price"]) ? $level1["current_subtotal_price"] : $level1["subtotal_price"],
                                'discount'              => isset($level1["current_total_discounts"]) ? $level1["current_total_discounts"] : $level1["total_discounts"],
                                'taxes'                 => isset($level1["current_total_tax"]) ? $level1["current_total_tax"] : $level1["total_tax"],
                                'shipping'              => isset($level1["shipping_lines"][0]["price"]) ? $level1["shipping_lines"][0]["price"] : 0,
                                'total'                 => isset($level1["current_total_price"]) ? $level1["current_total_price"] : $level1["total_price"],
                            ]);
                        } else { // New
                            parent::store($data);
                            $sale_id = $this->model->id; // Get ID
                        }

                        // Reset array
                        $parse_items = array();

                        // Search products
                        foreach ($level1["line_items"] as $items) {

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

                        // Create an array with products
                        $data["list_products"] = $parse_items;

                        // Save products
                        $this->saveSaleDetails($data, $sale_id);

                        // Reset array
                        $parse_fulfillments = array();

                        // Fulfillments
                        if (isset($level1["fulfillments"]) && count($level1["fulfillments"]) >0 ) {

                            $fulfillment_status = $level1["fulfillments"][0]["status"];
                            $fulfillment_date   = $level1["fulfillments"][0]["updated_at"];

                            // Search location based on Shopify location ID
                            $location = Location::where('shopify_location_id', $level1["fulfillments"][0]["location_id"])->pluck('id')->first();

                            foreach ($level1["fulfillments"][0]["line_items"] as $items) {

                                $product_id = Product::where('sku', $items["sku"])->pluck('id')->first();

                                if ($product_id) {
                                    array_push($parse_fulfillments,
                                    [
                                        'product_id'            => $product_id,
                                        'quantity'              => $items["quantity"],
                                        'location'              => $location,
                                        'fulfillment_status'    => $fulfillment_status,
                                        'fulfillment_date'      => $fulfillment_date
                                    ]);
                                }
                            }

                            // Create an array with products
                            $data["fulfillments"] = $parse_fulfillments;

                            // Set fulfillments
                            $this->checkFulfillments($data, $sale_id);
                        }

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
            // Save all products
            $this->saveSaleDetails($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
            // Save all products
            $this->saveSaleDetails($data, $this->model->id);
        });
    }


    /**
     * Update stock and update sale details with fulfillment data
     *
     * $product_id         = Product ID
     * $quantity           = Quantity to be increase/decrease on stock
     * $location           = Stock location ID
     * $operation          = Math operation ( - ) decrease,  ( + ) increase
     * $fulfillment_status = Partial or Fulfilled
     * $sale_id            = Sale ID
     * @return void
    */
    public function setItemFulfilled($company_id, $product_id, $quantity, $location, $operation, $fulfillment_status, $fulfillment_date, $sale_id)
    {
        // Update stock on hand
        $this->updateStock($company_id, $product_id, $quantity, $location, $operation, "Sale", $sale_id);

        // Decrease allocated quantity
        $this->updateStock($company_id, $product_id, 0, $location, $operation, "Sale", $sale_id, 0, $quantity);

        // Update Sale Details
        SaleDetails::where(['product_id' => $product_id, 'sale_id' => $sale_id])->update([
            'fulfillment_status'    => ($fulfillment_status == "success" ? 1 : 0),
            'fulfillment_date'      => date('Y-m-d', strtotime($fulfillment_date)),
            'qty_fulfilled'         => $quantity,
            'location_id'           => $location // When fulfilled we can get the location ID. It will be usefull in case we remove any item allowing undo the stock updated
        ]);
    }

    private function saveSaleDetails($data, $id)
    {
        if (isset($data["list_products"])) {

            $object = $data["list_products"];

            // Delete to insert them again
            SaleDetails::where('sale_id', $id)->delete();

            // Foreach row
            foreach ($object as $k => $v) {

                $qty                = 0;
                $total_item         = 0;
                $discount_value     = 0;
                $price              = 0;
                $shopify_lineitem   = "";
                $product_id         = 0;

                // Foreach attribute
                foreach ($v as $key => $value) {

                    if ($key == 'qty') {
                        $qty = $value;
                    }

                    if ($key == 'shopify_lineitem') {
                        $shopify_lineitem = $value;
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

                if ($product_id) {

                    $total_item = ($price * $qty);

                    SaleDetails::updateOrCreate([
                        'sale_id'               => $id,
                        'product_id'            => $product_id],
                    [
                        'qty'                   => $qty,
                        'shopify_lineitem'      => $shopify_lineitem,
                        'discount_value'        => $discount_value,
                        'price'                 => $price,
                        'total_item'            => $total_item,
                    ]);
                }
            }
        }

    }


    private function checkFulfillments($data, $sale_id)
    {
        if (isset($data["fulfillments"])) {

            $object = $data["fulfillments"];

            // Foreach row
            foreach ($object as $k => $v) {

                $location           = 0;
                $quantity           = 0;
                $fulfillment_status = "";
                $fulfillment_date   = "";
                $product_id         = 0;

                // Foreach attribute
                foreach ($v as $key => $value) {

                    if ($key == 'quantity') {
                        $quantity = $value;
                    }

                    if ($key == 'fulfillment_status') {
                        $fulfillment_status = $value;
                    }

                    if ($key == 'fulfillment_date') {
                        $fulfillment_date = $value;
                    }

                    if ($key == 'product_id') {
                        $product_id = $value;
                    }

                    if ($key == 'location') {
                        $location = $value;
                    }
                }

                if ($product_id) {
                    $operation = ($fulfillment_status == "success" ? '-' : ($fulfillment_status == "cancelled" ? '+' : '-') );
                    $this->setItemFulfilled($data["company_id"], $product_id, $quantity, $location, $operation, $fulfillment_status, $fulfillment_date, $sale_id);
                }
            }
        }

    }

    public function remove($id)
    {
        DB::transaction(function () use ($id)
        {
            $getItem = Sale::where('id', $id)->with('details')->get();

            $fulfillment_status = $getItem[0]->fulfillment_status;

            foreach ($getItem[0]->details as $value) {
                // Undo stock just when fulfilled
                if ($fulfillment_status == 1) {
                    $this->updateStock($getItem[0]->company_id, $value->product_id, $value->qty_fulfilled, $value->location_id, "+", "Sale", $id);
                } else {
                    $this->updateStock($getItem[0]->company_id, $value->product_id, 0, $value->location_id, "-", "Sale", $id, 0, $value->qty);
                }
            }

            // parent::delete($id);
            Sale::where('id', $id)->delete();
        });
    }
}
