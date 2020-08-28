<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sal_sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('financial_status_id')->nullable()->constrained('parameters')->onDelete('set null');
            $table->foreignId('fulfillment_status_id')->nullable()->constrained('parameters')->onDelete('set null');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('order_number');
            $table->double('discount', 10, 4)->nullable();
            $table->double('taxes', 10, 4)->nullable();
            $table->double('shipping', 10, 4)->nullable();
            $table->double('subtotal', 10, 4)->nullable();
            $table->double('total', 10, 4)->nullable();

            $table->timestamp('fulfillment_date')->nullable();
            $table->timestamp('sales_date')->nullable();
            $table->timestamp('payment_date')->nullable();

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
        Schema::dropIfExists('sal_sales');
    }
}
