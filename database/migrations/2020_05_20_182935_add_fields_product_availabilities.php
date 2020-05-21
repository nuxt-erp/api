<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsProductAvailabilities extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_availabilities', function (Blueprint $table) {
            $table->double('on_order')->nullable();
            $table->double('allocated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_availabilities', function (Blueprint $table) {
            $table->dropColumn(['on_order']);
            $table->dropColumn(['allocated']);
        });
    }
}
