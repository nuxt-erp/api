<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarnsferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_transfer_details', function (Blueprint $table) {
            $table->id();           
            $table->foreignId('transfer_id')->nullable()->constrained('inv_transfers')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('set null');
            $table->float('qty',10,4)->nullable();
            $table->float('qty_received',10,4)->nullable();
            $table->float('qty_sent',10,4)->nullable();
            $table->float('variance',10,4)->nullable();               
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
        Schema::connection('tenant')->dropIfExists('inv_transfer_details');
    }
}
