<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToStocktakeDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('stocktake_details', function (Blueprint $table) {
            $table->integer('abs_variance')->nullable();
        });
    }

    public function down()
    {
        Schema::table('stocktake_details', function (Blueprint $table) {
            $table->dropColumn(['abs_variance']);
        });
    }
}
