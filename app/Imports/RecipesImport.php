<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Flavor;
use App\Models\Parameter;
use App\Models\Recipe;
use App\Models\User;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;

class RecipesImport implements ToArray, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    use Importable;

    public $rows = 0;

    public function array(array $rows)
    {
        foreach ($rows as $row) {

            $row['brand']               = formatName($row, 'brand');
            $row['flavor']              = formatName($row, 'flavor');
            $row['variation']           = formatName($row, 'variation');

            // $row['category']            = formatName($row, 'category');
            // $row['bottle_material']     = formatName($row, 'bottle_material');
            // $row['bottle_color']        = formatName($row, 'bottle_color');
            // $row['cap_color']           = formatName($row, 'cap_color');
            // $row['bottles_per_tray']    = formatInt($row, 'bottles_per_tray');

            $row['strength']            = formatInt($row, 'mg');
            $row['size']                = formatInt($row, 'ml');

            $brand = Brand::firstOrCreate(['name' => $row['brand']]);

            $flavor = Flavor::firstOrCreate(['name' => $row['flavor']]);

            $variation_id = null;
            if (!empty($row['variation'])) {
                $variation = Parameter::firstOrCreate(
                    [
                        'parameter_name'    => 'variation',
                        'parameter_value'   => $row['variation']
                    ]
                );
                $variation_id = $variation->id;
            }

            $user = User::where('name', 'dear')->first();

            Recipe::updateOrCreate(
                ['sku' => $row['sku'],
                [
                    'author_id'         => $user->id,
                    'last_updater_id'   => $user->id,
                    'name'              => $brand->name.' '.$flavor->name .(!empty($row['variation']) ? ' '.$row['variation'] : ''),
                    'brand_id'          => $brand->id,
                    'flavor_id'         => $flavor->id,
                    'variant_id'        => $variation_id,
                    'strength'          => $row['strength'],
                    'size'              => $row['size'],
                    'status'            => Recipe::NEW_RECIPE
                ]
            );
            $this->rows++;
        }

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }


}
