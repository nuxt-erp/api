<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_containers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('production_id')->nullable()->constrained('prod_productions')->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained('parameters')->onDelete('set null');

            $table->unsignedInteger('to_handle_qty')->nullable();
            $table->decimal('to_handle_volume', 10, 4)->nullable();

            $table->unsignedInteger('handled_qty')->nullable();
            $table->decimal('handled_volume', 10, 4)->nullable();

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
        Schema::connection('tenant')->dropIfExists('prod_machines');
    }
}
