<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_categories', function (Blueprint $table) {
            $table->id();

            $table->string('dear_id')->nullable()->unique();
            $table->string('name')->unique();
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
        Schema::dropIfExists('inv_categories');
    }
}
