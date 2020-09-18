<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvStockCountTable extends Migration
{
    public function up()
    {
        Schema::connection('tenant')->create('inv_stock_counts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')->nullable()->constrained('inv_brands')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('inv_categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('count_type_id')->nullable()->constrained('parameters')->onDelete('set null');

            $table->datetime('date')->nullable();
            $table->string('name');
            $table->smallInteger('status')->default(0);
            $table->integer('target')->nullable();
            $table->bigInteger('variance_last_count_id')->nullable();
            $table->boolean('skip_today_received')->default(0);
            $table->boolean('add_discontinued')->default(0);
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
        Schema::connection('tenant')->dropIfExists('inv_stock_counts');
    }
}
