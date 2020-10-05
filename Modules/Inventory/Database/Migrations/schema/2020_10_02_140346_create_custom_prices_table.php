<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_prod_custom_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('currency');
            $table->decimal('custom_price', 10, 4)->default(0);
            $table->boolean('is_enabled')->default(1);
            $table->timestamp('disabled_at')->nullable();
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
        Schema::connection('tenant')->dropIfExists('inv_prod_custom_prices');
    }
}
