<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_phases', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->foreignId('operation_id')->constrained('prod_operations')->onDelete('cascade');

            $table->string('name')->unique();
            $table->boolean('will_start_counter')->nullable();
            $table->boolean('will_end_counter')->nullable();

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
        Schema::connection('tenant')->dropIfExists('prod_phases');
    }
}
