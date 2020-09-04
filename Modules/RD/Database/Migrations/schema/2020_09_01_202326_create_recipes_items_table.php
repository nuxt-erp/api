<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_recipe_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('product_id')->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('rd_recipes')->onDelete('cascade');

            $table->unsignedDecimal('quantity', 10, 4)->nullable();
            $table->unsignedDecimal('percent', 10, 4)->nullable();
            $table->float('cost', 10, 4)->nullable();

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
        Schema::connection('tenant')->dropIfExists('rd_recipe_items');
    }
}
