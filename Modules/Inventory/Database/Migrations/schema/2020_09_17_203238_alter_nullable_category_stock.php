<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullableCategoryStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_stock_counts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->onDelete('set null')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_stock_counts', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('inv_categories')->onDelete('set null')->change();
        });
    }
}
