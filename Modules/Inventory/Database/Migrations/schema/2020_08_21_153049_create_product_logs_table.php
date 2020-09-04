<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_product_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('type_id')->nullable()->constrained('parameters')->onDelete('set null');

            $table->bigInteger('ref_code_id')->nullable();
            $table->index(['ref_code_id']);
            $table->double('quantity', 10, 4)->nullable();
            $table->string('description')->nullable();

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
        Schema::connection('tenant')->dropIfExists('inv_product_logs');
    }
}
