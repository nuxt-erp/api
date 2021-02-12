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

class StockAdjustmentImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = '';
    public $products = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows) {
            $not_found  = [];

            foreach ($rows as $row) {
                $on_hand    = 0;

                $product = Product::where('sku', $row['sku'])
                    ->with('location')
                    ->first();

                if ($product) {
                    $new_qty = 0;
                    $location = null;
                    $bin = null;

                    if ($row['location'] != "") {
                        $location = Location::where('short_name', 'ILIKE', $row['location'])->first();
                        $on_hand = Availability::where(['product_id' => $product->id, 'location_id' => $location->id])->pluck('on_hand')->first();
                    }

                    if ($row['bin'] != "") {
                        $bin = LocationBin::where('name', 'ILIKE', $row['bin'])->orWhere('barcode', 'ILIKE', $row['bin'])->first();
                    }

                    if ($row['qty'] != "") {
                        $new_qty = $on_hand + $row['qty'];
                    } else {
                        $new_qty = $row['new_qty'];
                    }

                    $array = [
                        'sku'           => $row['sku'],
                        'product_id'    => $product->id,
                        'name'          => $row['sku'] . ' - ' . $product->name,
                        'location_id'   => optional($location)->id ?? null,
                        'location_name' => optional($location)->short_name ?? null,
                        'bin_id'        => optional($bin)->id ?? null,
                        'on_hand'       => $on_hand,
                        'qty'           => $new_qty,
                        'variance'      => !empty($on_hand) ? $new_qty - $on_hand : 0
                    ];

                    array_push($this->products, $array);
                    $this->rows++;
                } else {
                    array_push($not_found, $row['sku']);
                }
            }
            $this->errors = !empty($not_found) ? 'SKU Not found: ' . implode(array_slice($not_found, 0, 10)) : '';
        });
    }
}
