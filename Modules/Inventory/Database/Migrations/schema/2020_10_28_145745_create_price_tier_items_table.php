<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceTierItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_price_tier_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('price_tier_id')->constrained('inv_price_tiers')->onDelete('cascade');
            $table->double('custom_price', 10, 4)->nullable();

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
        Schema::connection('tenant')->dropIfExists('inv_price_tier_items');
    }
}
