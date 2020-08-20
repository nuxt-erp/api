<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_families', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')->constrained('inv_brands')->nullable()->onDelete('set null');
            $table->foreignId('category_id')->constrained('inv_categories')->nullable()->onDelete('set null');
            $table->foreignId('supplier_id')->constrained('suppliers')->nullable()->onDelete('set null');
            $table->foreignId('location_id')->constrained('locations')->nullable()->onDelete('set null');

            $table->string('dear_id')->nullable()->unique();
            $table->string('name')->unique();
            $table->mediumText('description')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->timestamp('launch_at')->nullable();
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
        Schema::dropIfExists('inv_families');
    }
}
