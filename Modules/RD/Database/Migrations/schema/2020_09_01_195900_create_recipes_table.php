<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_recipes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')->references('id')->on('users');

            $table->unsignedBigInteger('last_updater_id')->nullable();
            $table->foreign('last_updater_id')->references('id')->on('users');

            $table->unsignedBigInteger('approver_id')->nullable();
            $table->foreign('approver_id')->references('id')->on('users');

            // Added by me (recipe type: vape, syrup etc)
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('parameters');

            // each recipe will produce a product
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('inv_products');

            $table->string('status');

            $table->string('name');

            $table->string('total')->default(0);

            // Code for recipe history
            $table->string('code')->nullable(); // e.g. sku

            // Added by me
            $table->float('cost', 10, 4)->nullable();

            $table->smallInteger('version')->default(1);

            $table->dateTime('approved_at')->nullable();

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
        Schema::connection('tenant')->dropIfExists('rd_recipes');
    }
}
