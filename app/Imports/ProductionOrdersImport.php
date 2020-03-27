<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Flavor;
use App\Models\Import;
use App\Models\ProductionOrder;
use App\Models\Product;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use App\Services\DearService;

class ProductionOrdersImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = '';

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows) {
            $user   = auth()->user();
            $import = Import::create([
                'name'      => Import::XLS_INSERT_MO,
                'author_id' => $user->id,
                'rows'      => 0,
                'status'    => ''
            ]);
            $not_found = [];

            foreach ($rows as $row) {

                $row['quantity']    = formatInt($row, 'qty');
                $row['sku']         = formatString($row, 'sku');
                $row['brand']       = formatName($row, 'brand');
                $row['flavor']      = formatName($row, 'flavor');
                $row['strength']    = formatInt($row, 'strength');
                $row['size']        = formatInt($row, 'size');

                $product            = Product::where('sku', $row['sku'])->first();

                if(!$product){
                    $api = resolve('Dear\API');
                    $product = $api->syncProds($row['sku']);
                }

                if($product){
                    // if the info in the xls is different we need to update the product
                    if(!empty($row['brand'])){
                        if(!$product->brand || $product->brand->name != $row['brand']){
                            $brand  = Brand::firstOrCreate(['name' => $row['brand']]);
                            $product->brand_id = $brand->id;
                        }
                    }
                    if(!empty($row['flavor'])){
                        if(!$product->flavor || $product->flavor->name != $row['flavor']){
                            $flavor  = Flavor::firstOrCreate(['name' => $row['flavor']]);
                            $product->flavor_id = $flavor->id;
                        }
                    }
                    if($row['strength'] > 0){
                        if($row['strength'] != $product->strength){
                            $product->strength = $row['strength'];
                        }
                    }
                    if($row['size'] > 0){
                        if($row['size'] != $product->size){
                            $product->size = $row['size'];
                        }
                    }
                    $product->save();
                }
                else{
                    $not_found[] = $row['sku'];
                }

                ProductionOrder::create(
                    [
                        'author_id'     => $user->id,
                        'import_id'     => $import->id,
                        'sku'           => $row['sku'],
                        'product_id'    => $product->id ?? null,
                        'requested_qty' => $row['quantity'],
                        'status_id'     => ProductionOrder::getFirstStatus()->id ?? 'new'
                    ]
                );


                $this->rows++;
            }
            $this->errors = !empty($not_found) ? 'SKU Not found: ' . implode(array_slice($not_found, 0, 10)) : '';
            $import->rows   = $this->rows;
            $import->status = $this->errors;
            $import->save();
        });
    }
}
