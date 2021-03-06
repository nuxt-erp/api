<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_availabilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->nullable()->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

            $table->double('available', 10, 4)->default(0);
            $table->double('on_hand', 10, 4)->default(0);
            $table->double('on_order', 10, 4)->default(0);
            $table->double('allocated', 10, 4)->default(0);

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
        Schema::connection('tenant')->dropIfExists('inv_availabilities');
    }
}
