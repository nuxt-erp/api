<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductFamily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_families', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique();
            $table->float('price', 10, 4)->nullable();
            $table->double('length', 10, 4)->nullable();
            $table->double('width', 10, 4)->nullable();
            $table->double('height', 10, 4)->nullable();
            $table->double('weight', 10, 4)->nullable();
            $table->double('carton_length', 10, 4)->nullable();
            $table->double('carton_width', 10, 4)->nullable();
            $table->double('carton_height', 10, 4)->nullable();
            $table->double('carton_weight', 10, 4)->nullable();
            $table->string('measure_id')->nullable();
            $table->string('stock_locator')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_families', function (Blueprint $table) {
            $table->dropColumn('barcode');
            $table->dropColumn('price');
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('weight');
            $table->dropColumn('carton_length');
            $table->dropColumn('carton_width');
            $table->dropColumn('carton_height');
            $table->dropColumn('carton_weight');
            $table->dropColumn('measure_id');
            $table->dropColumn('stock_locator');

        });
    }
}