<?php

namespace Modules\Inventory\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;

class TransferImport implements ToArray, WithHeadingRow
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
                    ->with('location')
                    ->first();;

                if ($product) {
                    $qty = 0;
                    $qty_sent = 0;
                    $on_hand = 0;

                    if ($row['location_from'] != "") {
                        $location_from = Location::where('short_name', 'ILIKE', $row['location_from'])->first();
                        $on_hand = Availability::where(['product_id' => $product->id, 'location_id' => $location_from->id])->pluck('on_hand')->first();
                    }
                    if ($row['location_to'] != "") {
                        $location_to = Location::where('short_name', 'ILIKE', $row['location_to'])->first();
                    }

                    lad($location_from);
                    lad($location_to);

                    if ($row['qty'] != "") {
                        $qty = $row['qty'];
                    }
                    if ($row['qty_sent'] != "") {
                        $qty_sent = $row['qty_sent'];
                    }

                    $array = [
                        'name'                  => $product->name,
                        'product_id'            => $product->id,
                        'display_name'          => $product->getDetailsAttributeValue(),
                        'location_from_id'      => optional($location_from)->id ?? null,
                        'location_from_name'    => optional($location_from)->short_name ?? null,
                        'location_to_id'        => optional($location_to)->id ?? null,
                        'location_to_name'      => optional($location_to)->short_name ?? null,
                        'qty'                   => $qty,
                        'on_hand'               => $on_hand,
                        'qty_sent'              => $qty_sent,
                        'qty_received'          => !empty($row['qty_received']) ? $row['qty_received'] : 0,
                        'sku'                   => $row['sku'],
                        'variance'              => !empty($on_hand) ? $qty - $on_hand : 0,
                        'can_be_deleted'        => 1
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
