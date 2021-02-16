<?php

namespace Modules\Inventory\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\LocationBin;

class StockCountImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = '';
    public $products = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows) {
            $not_found  = [];
            $on_hand    = 0;
            foreach ($rows as $row) {
                $product = Product::where('sku', 'ILIKE', strval($row['sku']))
                    ->with('brand')
                    ->with('category')
                    ->with('location')->first();

                if ($product) {
                    $on_hand = null;
                    $bin = null;

                    if ($row['warehouse'] != "") {
                        $on_hand = Availability::where(['product_id' => $product->id, 'location_id' => Location::where('short_name', 'ILIKE', $row['warehouse'])->pluck('id')->first()])->pluck('on_hand')->first();
                    }
                    if ($row['bin'] != "") {
                        $bin = LocationBin::where('name', 'ILIKE', $row['bin'])->orWhere('barcode', 'ILIKE', $row['bin'])->first();
                    }

                    $array = [
                        'brand_id'      => $product->brand_id,
                        'product_brand'    => optional($product->brand)->name,
                        'category_id'   => $product->category_id,
                        'product_category' => optional($product->category)->name,
                        'location_id'   => $product->location_id,
                        'location_name' => optional($product->location)->name,
                        'name'          => $product->name,
                        'on_hand'       => $on_hand ?? 0,
                        'product_id'    => $product->id,
                        'product_name'  => $product->getFullDescriptionAttribute(),
                        'bin_id'        => optional($bin)->id ?? null,
                        'qty'           => $row['qty'],
                        'sku'           => $row['sku'],
                        'variance'      => !empty($on_hand) ? $row['qty'] - $on_hand : 0
                    ];

                    array_push($this->products, $array);
                    $this->rows++;
                }
            }
            $this->errors = !empty($not_found) ? 'SKU Not found: ' . implode(array_slice($not_found, 0, 10)) : '';
        });
    }
}
