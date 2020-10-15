<?php

namespace Modules\RD\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\RD\Entities\Phase;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for the recipe page
        Category::updateOrCreate(['name' => 'Carrier']);
        Category::updateOrCreate(['name' => 'Raw Material']);
        Category::updateOrCreate(['name' => 'Flavor']);
        Category::updateOrCreate(['name' => 'Flavor Key']);
        Category::updateOrCreate(['name' => 'Solution Material']);
        Category::updateOrCreate(['name' => 'Water']);
    }
}
