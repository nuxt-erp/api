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
        Schema::connection('tenant')->create('inv_families', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')->nullable()->constrained('inv_brands')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('inv_categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

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
        Schema::connection('tenant')->dropIfExists('inv_families');
    }
}
