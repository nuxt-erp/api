<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueBarcodeToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_products', function (Blueprint $table) {
            $table->dropUnique('inv_products_barcode_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_products', function (Blueprint $table) {

        });
    }
}
