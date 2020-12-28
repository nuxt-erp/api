<?php

namespace Modules\Inventory\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\BinImportSettings;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\LocationBin;
use Modules\Inventory\Entities\Measure;
use Modules\Inventory\Entities\ProductImportSettings;

class BinsImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = [];
    public $products = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows)
        {

            $settings = BinImportSettings::all();

            $custom_names = [];
            $settings->each(function ($item, $key) use(&$custom_names){
                $custom_names[$item->entity.'_'.$item->column_name] = strtolower($item->custom_name);
            });

            lad($custom_names);
            if(count($rows) > 0){
                lad("row0", $rows[0]);
            }

            foreach ($rows as $key => $row)
            {
                $location_id    = $row[$custom_names['bin_location_id'] ?? 'location_id'] ?? null;
                $name           = $row[$custom_names['bin_name'] ?? 'name'] ?? null;
                $is_enabled     = $row[$custom_names['bin_is_enabled'] ?? 'is_enabled'] ?? 1;
                $barcode        = $row[$custom_names['bin_barcode'] ?? 'barcode'] ?? null;


                if(!empty($name) && !empty($location_id)){
                    $location = Location::where('name', 'ILIKE', $location_id)->first()->id ?? null;
                    $location_bin = LocationBin::updateOrCreate([
                        'location_id'   => $location,
                        'name'          => $name,
                    ],[
                        'barcode'       => $barcode,
                        'is_enabled'    => $is_enabled
                    ]);
                    $this->rows++;
                }
                else{
                    $this->errors[] = 'Line: '.$key.'. Message: Empty location_id, or name';
                }

            }
        });
    }
}
