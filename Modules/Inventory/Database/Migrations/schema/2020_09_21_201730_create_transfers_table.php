<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('carrier_id')->nullable();
            $table->integer('shipment_type_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->integer('location_from_id')->nullable();
            $table->integer('location_to_id')->nullable();
            $table->integer('package_type_id')->nullable();
            $table->float('total_qty')->nullable();            
            $table->boolean('is_enable')->default(1);
            $table->date('pu_date')->nullable();
            $table->date('eta')->nullable();

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
        Schema::connection('tenant')->dropIfExists('inv_transfers');
    }
}
