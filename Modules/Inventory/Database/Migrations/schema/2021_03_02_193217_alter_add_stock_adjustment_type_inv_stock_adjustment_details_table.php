<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddStockAdjustmentTypeInvStockAdjustmentDetailsTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_stock_adjustments_details', function (Blueprint $table) {
            $table->string('adjustment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_stock_adjustments_details', function (Blueprint $table) {
            $table->dropColumn('adjustment_type');
        });
    }
}