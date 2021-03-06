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

            $table->foreignId('author_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->foreignId('last_updater_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->foreignId('approver_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('inv_categories')->onDelete('set null');

            // Added by me (recipe type: vape, syrup etc)
            $table->foreignId('type_id')->nullable()->constrained('parameters')->onDelete('set null');

            // each recipe will produce a product
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('set null');

            $table->string('status');

            $table->string('name');

            $table->unsignedDecimal('total', 10, 4)->nullable();

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
