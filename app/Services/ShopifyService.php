<?php

namespace App\Services;

use App\Models\Config;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Parameter;
use App\Models\Province;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Sales\Entities\Sale;
use Modules\Sales\Entities\SaleDetails;
use PHPShopify\ShopifySDK;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    // Sync order with Shopify
    public function syncOrders()
    {

        //@todo change all date functions calls to use Carbon package

        //date_default_timezone_set('America/Toronto');

        //$time_zone = '-5:00';
        // -2 minutes (Orders from the last 2 minutes)
        //$date = date('Y-m-d\TH:i:s', strtotime('-2 minutes', strtotime(date('Y-m-d\TH:i:s'))));

        $date = Carbon::now()->format('Y-m-d\TH:i:s\Z');
        $date_min = Carbon::now()->addMinutes(-2)->format('Y-m-d\TH:i:s\Z');

        $params = [
            'updated_at_min' => $date_min,
            'updated_at_max' => $date,
            'status'         => 'any',
            'limit'          => $this->limit
        ];

        $cont = 0;

        if ($this->client) {
            $orders = $this->client->Order->get($params);

            DB::transaction(function () use ($orders, $cont)
            {
                $fulfillment_status_list= [];
                $financial_status_list  = [];
                $allow_stock_move = true;

                foreach ($orders as $order) {

                    $cont++;

                    // Remove # character from the Shopify order name
                    $order_number = filter_var($order['name'], FILTER_SANITIZE_NUMBER_INT);

                    // Get financial status - Remove order if refunded - or cancelled
                    if ($order['financial_status'] == 'refunded' || ($order['cancelled_at'] != '' || $order['cancel_reason'] != '')) {
                        $sale_id = Sale::where('order_number', $order_number)->pluck('id')->first();
                        $this->removeSale($sale_id); // Refunded
                    } else {

                        $tmp_order = !is_numeric($order_number) ? substr($order_number, 0, (strlen($order_number) - 1)) : $order_number;

                        // Get all previous orders - Could be one or more previous orders (Ex. 123A, 123B, 123C)
                        // or Search for another other with same number but with letter at the final (updated order - more recent).
                        $sales = Sale::where('order_number', 'ILIKE', '%' . $tmp_order . '%')
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

                        if(!empty($order['financial_status']) && !isset($financial_status_list[$order['financial_status']])){
                            $financial_status = Parameter::firstOrCreate(
                                ['name' => 'sales_financial_status', 'value' => $order['financial_status']]
                            );
                            $financial_status_list[$order['financial_status']] = $financial_status->id;
                        }

                        if(!empty($order['fulfillment_status']) && !isset($fulfillment_status_list[$order['fulfillment_status']])){
                            $fulfillment_status = Parameter::firstOrCreate(
                                ['name' => 'sales_fulfillment_status', 'value' => $order['fulfillment_status']]
                            );
                            $fulfillment_status_list[$order['fulfillment_status']] = $fulfillment_status->id;
                        }

                        // Parse sale header
                        $data['order_number']           = $order_number;
                        $data['customer_id']            = $customer_id;
                        $data['sales_date']             = date('Y-m-d H:i:s', strtotime($order['processed_at']));
                        $data['financial_status_id']    = $financial_status_list[$order['financial_status']] ?? null;
                        $data['financial_status_name']  = $order['financial_status'] ?? '';
                        $data['fulfillment_status_id']  = $fulfillment_status_list[$order['fulfillment_status']] ?? null;
                        $data['fulfillment_status_name']= $order['fulfillment_status'] ?? '';
                        //$data['author_id']              = $this->user->id;
                        $data['subtotal']               = $order['subtotal_price'];
                        $data['discount']               = $order['total_discounts'];
                        $data['taxes']                  = $order['total_tax'];
                        $data['shipping']               = isset($order['shipping_lines'][0]['price']) ? $order['shipping_lines'][0]['price'] : 0;
                        $data['total']                  = $order['total_price'];

                        $sale = Sale::where('order_number', $order_number)->first();

                        if ($sale) { // Update
                            $allow_stock_move = ($sale->fulfillment_status_id == null || ($sale->fulfillment_status_id != $data['fulfillment_status_id'])); // Allow movement stock when updating existent order just if the fulfillment status had changed
                            $sale->fill([
                                'financial_status_id'   => $data['financial_status_id'],
                                'fulfillment_status_id' => $data['fulfillment_status_id'],
                                //'author_id'             => $this->user->id,
                                'subtotal'              => isset($order['current_subtotal_price']) ? $order['current_subtotal_price'] : $order['subtotal_price'],
                                'discount'              => isset($order['current_total_discounts']) ? $order['current_total_discounts'] : $order['total_discounts'],
                                'taxes'                 => isset($order['current_total_tax']) ? $order['current_total_tax'] : $order['total_tax'],
                                'shipping'              => isset($order['shipping_lines'][0]['price']) ? $order['shipping_lines'][0]['price'] : 0,
                                'total'                 => isset($order['current_total_price']) ? $order['current_total_price'] : $order['total_price'],
                            ]);
                            $sale->save();
                        } else { // New
                            $sale = Sale::create($data);
                        }

                        // Reset array
                        $parse_items = [];

                        // Search products
                        foreach ($order['line_items'] as $items) {

                            $product_id = Product::where('sku', $items['sku'])->pluck('id')->first();

                            if(!empty($items['fulfillment_status']) && !isset($fulfillment_status_list[$items['fulfillment_status']])){
                                $fulfillment_status = Parameter::firstOrCreate(
                                    ['name' => 'sales_fulfillment_status', 'value' => $items['fulfillment_status']]
                                );
                                $fulfillment_status_list[$items['fulfillment_status']] = $fulfillment_status->id;
                            }

                            // If not found, create a new product
                            if ($product_id == null) {

                                $variant = $this->client->ProductVariant($items["variant_id"])->get(); // Get more details from prod variant
                                $prod = $this->client->Product($items["product_id"])->get(); // Shopify Product

                                $variant2 = "";
                                if (isset($variant['option2'])) {
                                    $variant2 = $variant['option2'];
                                }

                                // Product name
                                $name = $items['title'] . ' - ' . (isset($variant['option1']) ? $variant['option1'] : "") . ($variant2!="" ? (' - ' . $variant2) : '');

                                // Category
                                $category = Category::firstOrCreate(['name' => trim(strtoupper($prod["product_type"]))], ['is_enabled' => 1]);

                                // Brand
                                $brand = Brand::firstOrCreate(['name' => trim(strtoupper($prod["vendor"]))], ['is_enabled' => 1]);

                                $product_id = Product::where('sku', $items['sku'])->pluck('id')->first();

                                if ($product_id == null) {
                                    // New product
                                    $new_prod = new Product;
                                    $new_prod->sku = $items["sku"];
                                    $new_prod->name = $name;
                                    $new_prod->category_id = $category->id;
                                    $new_prod->brand_id = $brand->id;
                                    $new_prod->save();

                                    $product_id = $new_prod->id;
                                }

                            }

                            array_push(
                                $parse_items,
                                [
                                    'sale_id'               => $sale->id,
                                    'product_id'            => $product_id,
                                    'qty'                   => $items['quantity'],
                                    'price'                 => $items['price'],
                                    'discount_value'        => $items['total_discount'],
                                    'total_item'            => ($items['quantity'] * $items['price']),
                                    'shopify_id'            => $items['id'],
                                    'fulfillment_status_id' => $fulfillment_status_list[$items['fulfillment_status']] ?? null
                                ]
                            );

                        }

                        // Save products
                        $this->saveSaleDetails($parse_items, $sale->id);

                        // Reset array
                        $parse_fulfillments = array();

                        // Fulfillments
                        if (isset($order['fulfillments'][0])) {

                            if ($allow_stock_move == true) { // Do not update again stock when update sale

                                $fulfillment_date   = $order['fulfillments'][0]['updated_at'] ?? null;

                                if(!empty($order['fulfillments'][0]['status']) && !isset($fulfillment_status_list[$order['fulfillments'][0]['status']])){
                                    $fulfillment_status = Parameter::firstOrCreate(
                                        ['name' => 'sales_fulfillment_status', 'value' => $order['fulfillments'][0]['status']]
                                    );
                                    $fulfillment_status_list[$order['fulfillments'][0]['status']] = $fulfillment_status->id;
                                }

                                // Search location based on Shopify location_id
                                $location_id = null;

                                // Check fulfillment event
                                $events = $this->client->Order($order["id"])->Fulfillment($order['fulfillments'][0]['id'])->Event->get();

                                if ($events) {
                                    $parse_location = explode(" ", $events[0]["message"]);
                                    if (isset($parse_location[0])) {
                                        $location_id = Location::where('name', 'like', '%' . $parse_location[0] . '%')->pluck('id')->first();
                                    }
                                } else {

                                    if (isset($order['fulfillments'][0]['location_id'])) {
                                        $location_id = Location::where('shopify_id', $order['fulfillments'][0]['location_id'])->pluck('id')->first();
                                    }
                                }

                                foreach ($order['fulfillments'][0]['line_items'] as $items) {

                                    $product_id = Product::where('sku', $items['sku'])->pluck('id')->first();

                                    if ($product_id) {
                                        array_push(
                                            $parse_fulfillments,
                                            [
                                                'product_id'                => $product_id,
                                                'quantity'                  => $items['quantity'],
                                                'location_id'               => $location_id,
                                                'fulfillment_status_id'     => $fulfillment_status_list[$order['fulfillments'][0]['status']] ?? null,
                                                'fulfillment_status_name'   => $order['fulfillments'][0]['status'] ?? '',
                                                'fulfillment_date'          => $fulfillment_date
                                            ]
                                        );
                                    }
                                }

                                // Set fulfillments
                                $this->checkFulfillments($parse_fulfillments, $sale->id);
                                $data['fulfillments'] = array();
                            }
                        }
                    }
                }
            });
        }

    }

    private function syncCustomer($order)
    {
        $customer_id = Customer::where(['shopify_id' => $order['customer']['id'], 'email' => substr($order['customer']['email'],0,160)])->pluck('id')->first();

        if (!$customer_id) {
            $country                    = $order['customer']['default_address']['country'] ? $this->checkCountry($order['customer']['default_address']['country']) : null;
            $province                   = $country ? $this->checkProvince($country->id, $order['customer']['default_address']['province_code'], $order['customer']['default_address']['province']) : null;
            $name                       = $order['customer']['default_address']['first_name'];

            if(!empty($name) && !empty($order['customer']['default_address']['last_name'])){
                $name                  .=  ' ' . $order['customer']['default_address']['last_name'];
            }

            $new                        = Customer::create([
                'shopify_id'            => $order['customer']['id'],
                'name'                  => $name,
                'address1'              => substr($order['customer']['default_address']['address1'],0,100),
                'address2'              => substr($order['customer']['default_address']['address2'],0,100),
                'email'                 => substr($order['customer']['email'],0,160),
                'country_id'            => !empty($country) ? $country->id : null,
                'province_id'           => !empty($province) ? $province->id : null,
                'city'                  => $order['customer']['default_address']['city'],
                'postal_code'           => $order['customer']['default_address']['zip'],
                'phone_number'          => substr($order['customer']['default_address']['phone'],0,20)
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
        return $province;
    }

    private function checkCountry($name)
    {
        $country = Country::firstOrCreate(['name' => $name]);
        return $country;
    }

    public function updateStock($product_id, $qty, $location_id, $bin_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = '')
    {
        $this->availabilityRepository->updateStock($product_id, $qty, $location_id, $bin_id, $operator, $type, $ref_code, $on_order_qty, $allocated_qty, $description);
    }

    private function checkFulfillments($data, $sale_id)
    {
        if (isset($data)) {

            // Foreach row
            foreach ($data as $v) {


                $quantity               = 0;
                $fulfillment_status_id  = null;
                $fulfillment_status_name= '';
                $fulfillment_date       = '';
                $product_id             = 0;

                // Foreach attribute
                foreach ($v as $key => $value) {

                    if ($key == 'quantity') {
                        $quantity = $value;
                    }

                    if ($key == 'fulfillment_status_id') {
                        $fulfillment_status_id = $value;
                    }

                    if ($key == 'fulfillment_status_name') {
                        $fulfillment_status_name = $value;
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
                    $operation = ($fulfillment_status_name == 'success' ? '-' : ($fulfillment_status_name == 'cancelled' ? '+' : '-') );
                    //     setItemFulfilled($product_id, $quantity, $location_id, $operation, $fulfillment_status_id, $fulfillment_date, $sale_id)
                    $this->setItemFulfilled($product_id, $quantity, $location_id, $operation, $fulfillment_status_id, $fulfillment_date, $sale_id);
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
    public function setItemFulfilled($product_id, $quantity, $location_id, $operation, $fulfillment_status_id, $fulfillment_date, $sale_id)
    {
        // Update stock on hand
        $this->updateStock($product_id, $quantity, $location_id, null, $operation, 'Sale', $sale_id, 0, 0, ($operation == '+' ? 'Item cancelled fulfillment' : 'Item fulfilled'));

        // Update allocated quantity
        $this->updateStock($product_id, 0, $location_id, null, $operation, 'Sale', $sale_id, 0, $quantity, ($operation == '+' ? 'Item cancelled returning allocated quantity' : 'Decreased allocated quantity'));

        // Update Sale Details
        SaleDetails::where(['product_id' => $product_id, 'sale_id' => $sale_id])->update([
            'fulfillment_status_id' => $fulfillment_status_id,
            'fulfillment_date'      => date('Y-m-d H:s:i', strtotime($fulfillment_date)),
            'qty_fulfilled'         => $quantity,
            'location_id'           => $location_id // When fulfilled we can get the location_id. It will be useful in case we remove any item allowing undo the stock updated
        ]);
    }

    //@todo should use sync from laravel relation
    public function saveSaleDetails($data, $id)
    {
        if (!empty($data)) {

            // Delete to insert them again
            //SaleDetails::where('sale_id', $id)->delete();

            // Foreach row
            foreach ($data as $item) {
                if (!empty($item['product_id'])) {

                    $prod = SaleDetails::where(['sale_id' => $id, 'product_id' => $item['product_id']])->first();

                    if ($prod) { // Update

                        // If updating, we need to know if we are adding more quantity or removing quantity from the original order
                        if ($prod->qty != $item['qty']) {
                            $this->updateStock($item['product_id'], 0, null, null, ($prod->qty > $item['qty'] ? '-' : '+'), 'Sale', $id, 0, abs($item['qty'] - $prod->qty), 'Change Allocated quantity');
                        }

                        $prod->qty              = $item['qty'] ?? 0;
                        $prod->shopify_id       = $item['shopify_id'] ?? null;
                        $prod->discount_value   = $item['discount_value'] ?? 0;
                        $prod->price            = $item['price'] ?? 0;
                        $prod->total_item       = (($item['price'] ?? 0) * ($item['qty'] ?? 0));
                        $prod->tax_rule_id      = $item['tax_rule_id'] ?? null;
                        $prod->save();

                    } else { // Create

                        SaleDetails::create([
                            'sale_id'               => $id,
                            'product_id'            => $item['product_id'],
                            'qty'                   => $item['qty'] ?? 0,
                            'shopify_id'            => $item['shopify_id'] ?? null,
                            'discount_value'        => $item['discount_value'] ?? 0,
                            'price'                 => $item['price'] ?? 0,
                            'total_item'            => (($item['price'] ?? 0) * ($item['qty'] ?? 0)),
                            'tax_rule_id'           => $item['tax_rule_id'] ?? null,
                        ]);

                        $this->updateStock($item['product_id'], 0, null, null, '+', 'Sale', $id, 0, $item['qty'], 'Allocated quantity');

                    }

                }
            }
        }

    }

    public function removeSale($id)
    {
        $sale = Sale::find($id);
        if ($sale) {
            if (count($sale->details) > 0) {
                foreach ($sale->details as $detail) {
                    // Undo stock on hand just when fulfilled
                    if (isset($sale->fulfillment_status->name) && $sale->fulfillment_status->name == 'fulfilled') {
                        $this->updateStock($detail->product_id, $detail->qty_fulfilled, $detail->location_id, null, '+', 'Sale', $id, 0, 0, 'Returning stock - item deleted');
                    } else { // Update allocated qty
                        $this->updateStock($detail->product_id, 0, $detail->location_id, null, '-', 'Sale', $id, 0, $detail->qty, 'Remove allocated qtd - item deleted');
                    }
                }
            }
            $sale->delete();
        }
    }
}
