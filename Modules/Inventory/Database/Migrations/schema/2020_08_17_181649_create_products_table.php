<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')->nullable()->constrained('inv_brands')->onDelete('set null');
            $table->foreignId('category_id')->constrained('inv_categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('family_id')->nullable()->constrained('inv_families')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

            $table->string('dear_id')->nullable()->unique();
            $table->string('name');
            $table->string('sku')->unique();
            $table->mediumText('description')->nullable();
            $table->float('cost', 10, 4)->nullable();
            $table->float('price', 10, 4)->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->double('length', 10, 4)->nullable();
            $table->double('width', 10, 4)->nullable();
            $table->double('height', 10, 4)->nullable();
            $table->double('weight', 10, 4)->nullable();
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
        Schema::connection('tenant')->dropIfExists('inv_products');
    }
}
