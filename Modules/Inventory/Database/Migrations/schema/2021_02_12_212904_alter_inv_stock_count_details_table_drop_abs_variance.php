<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvStockCountDetailsTableDropAbsVariance extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_stock_count_details', function (Blueprint $table) {
            $table->dropColumn('abs_variance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_stock_count_details', function (Blueprint $table) {
            $table->bigInteger('abs_variance')->nullable();
        });
    }
}