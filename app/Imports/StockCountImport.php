<?php

namespace App\Imports;

use App\Models\Import;
use App\Models\StockTakeDetail;
use App\Models\Product;
use App\Models\Location;
use App\Models\ProductAvailability;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use App\Services\DearService;

class StockCountImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = '';
    public $products = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows)
        {
            $not_found  = [];
            $on_hand    = 0;

            foreach ($rows as $row)
            {
                $product = Product::where('sku', $row['sku'])
                ->with('brand')
                ->with('category')
                ->with('location')
                ->first();

                if ($product)
                {
                    if ($row['warehouse'] != "") {
                        $on_hand = ProductAvailability::where(['product_id' => $product->id, 'location_id' => Location::where('short_name', $row['warehouse'])->pluck('id')->first()])->pluck('on_hand')->first();
                    }

                    $array = [
                        'brand_id'      => $product->brand_id,
                        'brand_name'    => optional($product->brand)->name,
                        'category_id'   => $product->category_id,
                        'category_name' => optional($product->category)->name,
                        'location_id'   => $product->location_id,
                        'location_name' => optional($product->location)->name,
                        'name'          => $product->name,
                        'on_hand'       => $on_hand,
                        'product_id'    => $product->id,
                        'qty'           => $row['qty'],
                        'sku'           => $row['sku'],
                        'variance'      => $on_hand - $row['qty']
                    ];

                    array_push($this->products, $array);
                    $this->rows++;
                }
            }
            $this->errors = !empty($not_found) ? 'SKU Not found: ' . implode(array_slice($not_found, 0, 10)) : '';
        });
    }
}
