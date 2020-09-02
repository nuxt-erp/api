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

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');

            $table->unsignedBigInteger('last_updater_id');
            $table->foreign('last_updater_id')->references('id')->on('users');

            $table->unsignedBigInteger('checker_id')->nullable();
            $table->foreign('checker_id')->references('id')->on('users');

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('parameters');

            // Added by me (recipe type: vape, syrup etc)
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('parameters');

            $table->string('name');

            // Code for recipe history
            $table->string('code');

            // Added by me
            $table->float('cost', 10, 4)->nullable();

            $table->smallInteger('version');

            $table->dateTime('created_at')->nullable();

            $table->dateTime('checked_at')->nullable();

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
