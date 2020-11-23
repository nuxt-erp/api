<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvStockCountFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_stock_count_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocktake_id')->nullable()->constrained('inv_stock_counts')->onDelete('set null');
            $table->string('type');
            $table->foreignId('type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('inv_stock_count_filters');
    }
}
