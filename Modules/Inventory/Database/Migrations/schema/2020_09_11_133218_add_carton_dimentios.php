<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCartonDimentios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_products', function (Blueprint $table) {
            $table->double('carton_length', 10, 4)->nullable();
            $table->double('carton_width', 10, 4)->nullable();
            $table->double('carton_height', 10, 4)->nullable();
            $table->double('carton_weight', 10, 4)->nullable();
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
            $table->dropColumn('carton_length');
            $table->dropColumn('carton_width');
            $table->dropColumn('carton_height');
            $table->dropColumn('carton_weight');

        });
    }
}