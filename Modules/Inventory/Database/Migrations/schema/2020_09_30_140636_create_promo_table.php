<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_product_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('inv_products')->onDelete('cascade');
            $table->bigInteger('discount_percentage')->default(0);
            $table->bigInteger('buy_qty')->default(0);
            $table->bigInteger('get_qty')->default(0);
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
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
        Schema::connection('tenant')->dropIfExists('inv_product_promos');
    }
}
