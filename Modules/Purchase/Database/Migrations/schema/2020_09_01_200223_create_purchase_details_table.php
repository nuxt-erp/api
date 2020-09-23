<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('pur_purchase_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id')->nullable()->constrained('pur_purchases')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('set null');

            $table->smallInteger('item_status')->default(0);
            $table->string('ref')->nullable();
            $table->double('qty_received', 10, 4)->nullable();
            $table->double('qty', 10, 4)->nullable();
            $table->double('price', 10, 4)->nullable();
            $table->double('discounts', 10, 4)->nullable();
            $table->double('sub_total', 10, 4)->nullable();
            $table->double('taxes', 10, 4)->nullable();
            $table->double('total', 10, 4)->nullable();
            $table->timestamp('estimated_date')->nullable();
            $table->timestamp('received_date')->nullable();

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
        Schema::connection('tenant')->dropIfExists('pur_purchase_details');
    }
}
