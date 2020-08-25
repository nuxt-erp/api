<?php

namespace App\Service;

use App\Models\Config;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Province;
use App\Models\User;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Repositories\AvailabilityRepository;
use PHPShopify\ShopifySDK;

class ShopifyService
{
    private $client;
    private $limit;
    private $user;

    private $availabilityRepository;

    public function __construct()
    {
        $config = Config::first();
        if ($config) {
            $this->client = new ShopifySDK([
                'ShopUrl'    => $config->shopify_store_name,
                'ApiKey'     => $config->shopify_key,
                'Password'   => $config->shopify_password,
                //'ApiVersion' => $config->shopify_api_version
            ]);
        }

        $this->availabilityRepository = new AvailabilityRepository(new Availability());
        $this->limit = 250;
        $this->user = auth()->user() ?? User::where('name', 'admin')->first();
    }

    public function syncOrders()
    {
        //@todo change all date functions calls to use Carbon package

        // date_default_timezone_set('America/Toronto');
        $time_zone = '-4:00';
        // -2 minutes (Orders from the last 2 minutes)
        $date = date('Y-m-d\TH:i:s', strtotime('-30 minutes', strtotime(date('Y-m-d\TH:i:s'))));

        $params = [
            'updated_at_min' => $date  . $time_zone,
            'updated_at_max' => date('Y-m-d\TH:i:s')  . $time_zone,
            'status'         => 'any',
            'limit'          => $this->limit
        ];

        if ($this->client) {
            $orders = $this->client->Order->get($params);

            foreach ($orders as $order) {

                // Remove # character from the Shopify order name
                $order_number = filter_var($order["name"], FILTER_SANITIZE_NUMBER_INT);

                // Get financial status - Remove order if refunded
                if ($order["financial_status"] == "refunded") {
                    $sale_id = Sale::where('order_number', $order_number)->pluck('id')->first();
                    $this->removeSale($sale_id); // Refunded
                } else {

                    $tmp_order = !is_numeric($order_number) ? substr($order_number, 0, (strlen($order_number) - 1)) : $order_number;

                    // Get all previous orders - Could be one or more previous orders (Ex. 123A, 123B, 123C)
                    // or Search for another other with same number but with letter at the final (updated order - more recent).
                    $sales = Sale::where('order_number', 'LIKE', '%' . $tmp_order . '%')
                        ->where('order_number', '<>', $order_number) // Do not remove itself
                        ->select('id')->get();

                    // If found a letter at the end (Ex. 12345A) - It means order updated
                    foreach ($sales as $sale) {
                        if (!is_numeric($order_number) || (is_numeric($order_number) && !is_numeric($sale->order_number))) { // Found updated order. Remove the current order with no letter
                            $this->removeSale($sale->id); // Remove previous order
                        }
                    }

                    // Find or create a customer
                    $customer_id = $this->syncCustomer($order);

                    // Parse sale header
                    $data["order_number"]       = $order_number;
                    $data["customer_id"]        = $customer_id;
                    $data["sales_date"]         = date('Y-m-d H:i:s', strtotime($order["processed_at"]));
                    $data["financial_status"]   = ($order["financial_status"] == "pending" ? 0 : 1);
                    $data["fulfillment_status"] = ($order["fulfillment_status"] == "fulfilled" ? 1 : 0);
                    $data["user_id"]            = $this->user->id;
                    $data["subtotal"]           = $order["subtotal_price"];
                    $data["discount"]           = $order["total_discounts"];
                    $data["taxes"]              = $order["total_tax"];
                    $data["shipping"]           = isset($order["shipping_lines"][0]["price"]) ? $order["shipping_lines"][0]["price"] : 0;
                    $data["total"]              = $order["total_price"];
                    $data["order_status_label"] = "";

                    $sale = Sale::where('order_number', $order_number)->first();

                    if ($sale) { // Update
                        $sale->fill([
                            'financial_status'      => $data["financial_status"],
                            'fulfillment_status'    => $data["fulfillment_status"],
                            'user_id'               => $this->user->id,
                            'subtotal'              => isset($order["current_subtotal_price"]) ? $order["current_subtotal_price"] : $order["subtotal_price"],
                            'discount'              => isset($order["current_total_discounts"]) ? $order["current_total_discounts"] : $order["total_discounts"],
                            'taxes'                 => isset($order["current_total_tax"]) ? $order["current_total_tax"] : $order["total_tax"],
                            'shipping'              => isset($order["shipping_lines"][0]["price"]) ? $order["shipping_lines"][0]["price"] : 0,
                            'total'                 => isset($order["current_total_price"]) ? $order["current_total_price"] : $order["total_price"],
                        ]);
                        $sale->save();
                    } else { // New
                        $sale = Sale::create($data);
                        $sale_id = $sale->id; // Get ID
                    }

                    // Reset array
                    $parse_items = [];

                    // Search products
                    foreach ($order["line_items"] as $items) {

                        $product_id = Product::where('sku', $items["sku"])->pluck('id')->first();

                        if ($product_id) {
                            array_push(
                                $parse_items,
                                [
                                    'sale_id'               => $sale_id,
                                    'product_id'            => $product_id,
                                    'qty'                   => $items["quantity"],
                                    'price'                 => $items["price"],
                                    'discount_value'        => $items["total_discount"],
                                    'total_item'            => ($items["quantity"] * $items["price"]),
                                    'shopify_lineitem'      => $items["id"],
                                    'fulfillment_status'    => $items["fulfillment_status"]
                                ]
                            );

                            // Allocated quantity
                            $this->updateStock($product_id, 0, null, "+", "Sale", $sale_id, 0, $items["quantity"], 'Allocated quantity');
                        }
                    }

                    // Create an array with products
                    $data["list_products"] = $parse_items;

                    // Save products
                    $this->saveSaleDetails($data, $sale_id);

                    // Reset array
                    $parse_fulfillments = array();

                    // Fulfillments
                    if (!empty($order["fulfillments"])) {

                        $fulfillment_status = $order["fulfillments"][0]["status"] ?? 0;
                        $fulfillment_date   = $order["fulfillments"][0]["updated_at"] ?? null;

                        // Search location based on Shopify location_id
                        if (isset($order["fulfillments"][0]["location_id"])) {
                            $location_id = Location::where('shopify_location_id', $order["fulfillments"][0]["location_id"])->pluck('id')->first();
                        } else {
                            $location_id = null;
                        }

                        foreach ($order["fulfillments"][0]["line_items"] as $items) {

                            $product_id = Product::where('sku', $items["sku"])->pluck('id')->first();

                            if ($product_id) {
                                array_push(
                                    $parse_fulfillments,
                                    [
                                        'product_id'            => $product_id,
                                        'quantity'              => $items["quantity"],
                                        'location_id'           => $location_id,
                                        'fulfillment_status'    => $fulfillment_status,
                                        'fulfillment_date'      => $fulfillment_date
                                    ]
                                );
                            }
                        }

                        // Create an array with products
                        $data["fulfillments"] = $parse_fulfillments;

                        // Set fulfillments
                        $this->checkFulfillments($data, $sale_id);
                        $data["fulfillments"] = array();
                    }
                }
            }
        }
    }

    private function syncCustomer($order)
    {
        $customer_id = Customer::where('shopify_id', $order["customer"]["id"])->pluck('id')->first();
        if (!$customer_id) {
            $country                    = $order["customer"]["default_address"]["country"] ? $this->checkCountry($order["customer"]["default_address"]["country"]) : null;
            $province                   = $country ? $this->checkProvince($country->id, $order["customer"]["default_address"]["province_code"], $order["customer"]["default_address"]["province"]) : null;
            $name                       = $order["customer"]["default_address"]["first_name"];
            if(!empty($name) && !empty($order["customer"]["default_address"]["last_name"])){
                $name                  .=  ' ' . $order["customer"]["default_address"]["last_name"];
            }

            $new                        = Customer::create([
                'shopify_id'            => $order["customer"]["id"],
                'name'                  => $name,
                'address1'              => substr($order["customer"]["default_address"]["address1"],0,100),
                'address2'              => substr($order["customer"]["default_address"]["address2"],0,100),
                'email'                 => substr($order["customer"]["email"],0,160),
                'country_id'            => $country->id,
                'province_id'           => $province->id,
                'city'                  => $order["customer"]["default_address"]["city"],
                'postal_code'           => $order["customer"]["default_address"]["zip"],
                'phone_number'          => substr($order["customer"]["default_address"]["phone"],0,20)
            ]);
            $customer_id                = $new->id;
        }
        return $customer_id;
    }

    private function checkProvince($country_id, $code, $name)
    {
        $province = Province::firstOrCreate(
            ['country_id' => $country_id, 'code' => $code],
            ['name' => $name]
        );
        return $province->id;
    }

    private function checkCountry($name)
    {
        $country = Country::firstOrCreate(['name' => $name]);
        return $country->id;
    }

    public function updateStock($product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = "")
    {
        // UPDATE STOCK AVAILABILITY
        $this->availabilityRepository->updateStock($product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty, $allocated_qty, $description);
    }

    private function checkFulfillments($data, $sale_id)
    {
        if (isset($data["fulfillments"])) {

            $object = $data["fulfillments"];

            // Foreach row
            foreach ($object as $v) {

                $product_id         = null;
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

                    if ($key == 'location_id') {
                        $location_id = $value;
                    }
                }

                if ($product_id != 0) {
                    $operation = ($fulfillment_status == "success" ? '-' : ($fulfillment_status == "cancelled" ? '+' : '-') );
                    $this->setItemFulfilled($product_id, $quantity, $location_id, $operation, $fulfillment_status, $fulfillment_date, $sale_id);
                }
            }
        }

    }

    /**
     * Update stock and update sale details with fulfillment data
     *
     * $product_id         = Product ID
     * $quantity           = Quantity to be increase/decrease on stock
     * $location_id        = Stock location_id
     * $operation          = Math operation ( - ) decrease,  ( + ) increase
     * $fulfillment_status = Partial or Fulfilled
     * $sale_id            = Sale ID
     * @return void
    */
    public function setItemFulfilled($product_id, $quantity, $location_id, $operation, $fulfillment_status, $fulfillment_date, $sale_id)
    {
        // Update stock on hand
        $this->updateStock($product_id, $quantity, $location_id, $operation, "Sale", $sale_id, 0, 0, ($operation == "+" ? 'Item cancelled fulfillment' : 'Item fulfilled'));

        // Update allocated quantity
        $this->updateStock($product_id, 0, $location_id, $operation, "Sale", $sale_id, 0, $quantity, ($operation == "+" ? 'Item cancelled returning allocated quantity' : 'Allocated quantity'));

        // Update Sale Details
        SaleDetails::where(['product_id' => $product_id, 'sale_id' => $sale_id])->update([
            'fulfillment_status'    => ($fulfillment_status == "success" ? 1 : 0),
            'fulfillment_date'      => date('Y-m-d H:s:i', strtotime($fulfillment_date)),
            'qty_fulfilled'         => $quantity,
            'location_id'           => $location_id // When fulfilled we can get the location_id. It will be useful in case we remove any item allowing undo the stock updated
        ]);
    }

    public function removeSale($id)
    {
        $sale = Sale::find($id)->with('details')->get();
        if ($sale) {
            $fulfillment_status = $sale->fulfillment_status;
            if (isset($sale->details)) {
                foreach ($sale->details as $detail) {
                    // Undo stock on hand just when fulfilled
                    if ($fulfillment_status == 1) {
                        $this->updateStock($detail->product_id, $detail->qty_fulfilled, $detail->location_id, "+", "Sale", $id, 0, 0, "Returning stock - item deleted");
                    } else { // Update allocated qty
                        $this->updateStock($detail->product_id, 0, $detail->location_id, "-", "Sale", $id, 0, $detail->qty, "Remove allocated qtd - item deleted");
                    }
                }
            }
        }
        $sale->delete();
    }
}
