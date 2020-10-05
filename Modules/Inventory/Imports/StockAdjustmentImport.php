<?php

namespace Modules\Inventory\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;
class StockAdjustmentImport implements ToArray, WithHeadingRow
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

            foreach ($rows as $row)
            {
                $on_hand    = 0;
                lad($row);

                $product = Product::where('sku', $row['sku'])
                ->with('location')
                ->first();

                lad($product);

                if ($product)
                {
                    $new_qty = 0;
                    if ($row['location'] != "") {
                        $location = Location::where('short_name', 'LIKE', $row['location'])->first();
                        $on_hand = Availability::where(['product_id' => $product->id, 'location_id' => $location->id])->pluck('on_hand')->first();
                    }

                    if ($row['qty'] != "") {
                        $new_qty = $on_hand + $row['qty'];
                    } else {
                        $new_qty = $row['new_qty'];
                    }

                    $array = [
                        'sku'           => $row['sku'],
                        'product_id'    => $product->id,
                        'product_name'  => $product->name,
                        'location_id'   => $location->id,
                        'location_name' => $location->short_name,
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
