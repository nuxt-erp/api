<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_machines', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('flow_id')->nullable()->constrained('prod_flows')->onDelete('set null');

            $table->string('name', 50)->unique();
            $table->unsignedBigInteger('capacity')->nullable();
            $table->unsignedInteger('working_hours')->nullable();
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
