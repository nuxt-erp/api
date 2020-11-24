<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCartonBarcodeQtyToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_products', function (Blueprint $table) {
            $table->string('carton_barcode')->nullable()->unique();
            $table->string('carton_qty')->nullable();
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
            $table->dropColumn('carton_barcode');
            $table->dropColumn('carton_qty');
        });
    }
}
