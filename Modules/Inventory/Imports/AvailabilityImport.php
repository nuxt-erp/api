<?php

namespace Modules\Inventory\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\AvailabilityImportSettings;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\LocationBin;
use Modules\Inventory\Entities\Product;

class AvailabilityImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = [];
    public $products = [];
    
    public function array(array $rows)
    {
        DB::transaction(function () use ($rows)
        {

            $settings = AvailabilityImportSettings::all();

            $custom_names = [];
            $settings->each(function ($item, $key) use(&$custom_names){
                $custom_names[$item->entity.'_'.$item->column_name] = strtolower($item->custom_name);
            });

            if(count($rows) > 0){
                lad("row0", $rows[0]);
            }

            foreach ($rows as $key => $row)
            {
                $sku                    = $row[$custom_names['product_sku'] ?? 'sku'] ?? null;
                $product_name           = $row[$custom_names['product_name'] ?? 'name'] ?? null;
                $availability_quantity  = $row[$custom_names['availability_quantity'] ?? 'quantity'] ?? null;
                $bin_barcode            = $row[$custom_names['bin_barcode'] ?? 'barcode'] ?? null;
                $location_name          = $row[$custom_names['location_name'] ?? 'location_name'] ?? null;

                $bin                    = null;
                $location               = null;

                if(!empty($location_name)) {
                    $location = Location::where('name', 'ILIKE', $location_name)->first();
                    lad($location->id);

                }
                if(!empty($bin_barcode)) {
                    $bin = LocationBin::where('barcode', 'ILIKE', $bin_barcode)->first();
                }

                if(!empty($sku)){
                    $product = Product::where('sku', 'ILIKE', $sku)->first();
                    if(!empty($product)){
                        Availability::updateOrCreate(
                            ['product_id'       => $product->id],
                            [
                             'available'          => $availability_quantity ?? 0,
                             'bin_id'           => optional($bin)->id ?? null,
                             'location_id'      => optional($location)->id ?? null,
                        ]);
                    }
                    $this->rows++;
                } else if(!empty($product_name)) {
                    $product = Product::where('name', 'ILIKE', $product_name)->first();
                    if(!empty($product)){
                        Availability::updateOrCreate(
                            ['product_id'       => $product->id],
                            [
                             'available'          => $availability_quantity ?? 0,
                             'bin_id'           => optional($bin)->id ?? null,
                             'location_id'      => optional($location)->id ?? null,
                        ]);
                    }
                    $this->rows++;
                }
                else{
                    $this->errors[] = 'Line: '.$key.'. Message: Empty SKU';
                }
            }
        });
    }
}
