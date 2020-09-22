<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvStockCountDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_stock_count_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stocktake_id')->nullable()->constrained('inv_stock_counts')->onDelete('set null');
            $table->foreignId('product_id')->constrained('inv_products')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

            $table->bigInteger('qty')->nullable();
            $table->bigInteger('stock_on_hand')->nullable();
            $table->bigInteger('variance')->nullable();
            $table->bigInteger('abs_variance')->nullable();
            $table->string('notes');

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
        Schema::connection('tenant')->dropIfExists('inv_stock_count_details');
    }
}
