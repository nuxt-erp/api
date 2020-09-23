<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->string('currency');
            $table->decimal('last_price', 10, 4)->default(0);
            $table->timestampTz('last_supplied', 0)->nullable();


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
        Schema::connection('tenant')->dropIfExists('inv_suppliers');
    }
}
