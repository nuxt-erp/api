<?php

namespace Modules\Inventory\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Measure;
use Modules\Inventory\Entities\ProductImportSettings;

class ProductsImport implements ToArray, WithHeadingRow
{

    use Importable;

    public $rows = 0;
    public $errors = [];
    public $products = [];

    public function array(array $rows)
    {
        DB::transaction(function () use ($rows)
        {

            $settings = ProductImportSettings::all();

            $custom_names = [];
            $settings->each(function ($item, $key) use(&$custom_names){
                $custom_names[$item->entity.'_'.$item->column_name] = strtolower($item->custom_name);
            });


            foreach ($rows as $key => $row)
            {
                $sku            = $row[$custom_names['product_sku'] ?? 'sku'] ?? null;
                $product_name   = $row[$custom_names['product_name'] ?? 'name'] ?? null;
                $category_name  = $row[$custom_names['category_name'] ?? 'category'] ?? null;
                $measure_name   = $row[$custom_names['measure_name'] ?? 'measure'] ?? null;
                $barcode        = $row[$custom_names['product_barcode'] ?? 'barcode'] ?? null;
                $description    = $row[$custom_names['product_description'] ?? 'description'] ?? null;

                if(!empty($sku) && !empty($product_name)){

                    $category = null;
                    if(!empty($category_name)){
                        $category = Category::firstOrCreate([
                            'name'  => $category_name
                        ]);
                    }

                    $measure = null;
                    if(!empty($measure_name)){
                        $measure = Measure::firstOrCreate([
                            'name'  => $measure_name
                        ]);
                    }

                    $product = Product::updateOrCreate([
                        'sku'   => $sku,
                    ],[
                        'name'          => $product_name,
                        'barcode'       => $barcode,
                        'category_id'   => $category->id ?? null,
                        'measure_id'    => $measure->id ?? null,
                        'description'   => $description
                    ]);
                    $this->rows++;
                }
                else{
                    $this->errors[] = 'Line: '.$key.'. Message: Empty SKU';
                }

            }
        });
    }
}
