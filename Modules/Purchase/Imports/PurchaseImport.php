<?php

namespace Modules\Purchase\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\LocationBin;
use Modules\Inventory\Entities\ProductSuppliers;

class PurchaseImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = '';
    public $products = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {
                lad($row);
                $product = null;
                if(!empty($row['sku'])) {
                    $product = Product::where('sku', 'ILIKE', strval($row['sku']))->first();
                    lad('!empty($row[sku])');
                    lad($product);

                } else if (!empty($row['name'] && !empty($row['supplier_sku']))) {
                    $product_supplier = ProductSuppliers::where('product_sku', 'ILIKE', strval($row['supplier_sku']))->whereHas('supplier', function ($query) use($row) {
                        $query->where('suppliers.name', 'ILIKE', strval($row['name']));
                    })->first();
                    lad('!empty($row[name] && !empty($row[supplier sku])');
                    $product = Product::find($product_supplier->product_id);
                    lad($product_supplier->product_id);
                    lad($product);

                }

                if ($product) {
                    $location = null;
                    $bin = null;

                    if (!empty($row['warehouse'])) {
                        lad('warehouse');

                        $location = Location::where('name', 'ILIKE', $row['warehouse'])->first();
                    }

                    if (!empty($row['bin'])) {
                        lad('bin');

                        $bin = LocationBin::where('name', 'ILIKE', $row['bin'])->orWhere('barcode', 'ILIKE', $row['bin'])->first();

                    }
                    // bin_id: null
                    // bin_name: null
                    // can_be_deleted: true
                    // discounts: null
                    // estimated_date: null
                    // id: 42
                    // item_status: 0
                    // location_id: null
                    // location_name: null
                    // name: "1-OCTEN-3-OL"
                    // price: null
                    // product_full_name: "RM00003 - 1-OCTEN-3-OL"
                    // product_id: 38
                    // product_name: "1-OCTEN-3-OL"
                    // purchase_id: 5
                    // qty: 1
                    // qty_received: 2
                    // received_date: "2021-02-10 00:00:00"
                    // ref: null
                    // sub_total: null
                    // tax_rule_id: null
                    // taxes: null
                    // total: null

                    $array = [
                        'product_id'       => $product->id,
                        'product_full_name'     => optional($product)->getFullDescriptionAttribute() ?? optional($product)->name,
                        'can_be_deleted'   => true,
                        'discounts'        => $row['discount'],
                        'estimated_date'   => null,
                        'price'            => $row['price'],
                        'bin_id'           => optional($bin)->id ?? null,
                        'bin_name'         => optional($bin)->name ?? null,
                        'location_id'      => optional($location)->id ?? null,
                        'location_name'    => optional($location)->name ?? null,
                        'qty'              => $row['qty'],
                        'qty_received'     => 0,
                        'total'            => 0,
                        'tax_value'        => 0,
                        'received_date'    => null,
                        'taxes'            => null,
                        'total'            => null,
                    ];

                    array_push($this->products, $array);
                    $this->rows++;
                }
            }
            $this->errors = !empty($not_found) ? 'SKU Not found: ' . implode(array_slice($not_found, 0, 10)) : '';
        });
    }
}
