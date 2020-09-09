<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_recipe_proposals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('recipe_id')->constrained('rd_recipes')->onDelete('cascade');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('status');
            $table->string('comment')->nullable();
            $table->date('approved_at')->nullable();

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
        Schema::connection('tenant')->dropIfExists('rd_recipe_proposals');
    }
}
