<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sal_sale_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')->constrained('sal_sales')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('fulfillment_status_id')->nullable()->constrained('parameters')->onDelete('set null');

            $table->string('shopify_id')->nullable();
            $table->double('qty', 10, 4)->nullable();
            $table->double('price', 10, 4)->nullable();
            $table->double('discount_value', 10, 4)->nullable();
            $table->double('discount_percent', 10, 4)->nullable();
            $table->double('total_item', 10, 4)->nullable();
            $table->double('qty_fulfilled', 10, 4)->nullable();

            $table->timestamp('fulfillment_date')->nullable();

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
        Schema::dropIfExists('sal_sale_details');
    }
}
