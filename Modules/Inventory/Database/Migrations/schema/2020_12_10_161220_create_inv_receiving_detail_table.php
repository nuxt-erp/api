<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvReceivingDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_receiving_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_id')->nullable()->constrained('inv_receiving')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('set null');
            $table->string('item_status')->nullable();
            $table->string('ref')->nullable();
            $table->double('qty_received', 10, 4)->nullable();
            $table->double('qty', 10, 4)->nullable();
            $table->timestamp('received_date')->nullable();
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
        Schema::connection('tenant')->dropIfExists('inv_receiving_details');
    }
}
