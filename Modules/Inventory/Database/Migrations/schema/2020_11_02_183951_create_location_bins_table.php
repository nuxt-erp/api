<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('inv_location_bins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('location_id')->constrained('locations')->onDelete('set null');
            $table->string('name');
            $table->boolean('is_enabled')->default(1);
            $table->unique(['location_id', 'name']);

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
        Schema::connection('tenant')->dropIfExists('inv_location_bins');
    }
}
