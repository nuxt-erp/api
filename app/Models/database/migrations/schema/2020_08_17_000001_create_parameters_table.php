<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value');
            $table->unsignedSmallInteger('order')->default(1);
            $table->string('description')->nullable();
            $table->boolean('is_internal')->default(0);
            $table->boolean('is_default')->default(0);
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
        Schema::connection('tenant')->dropIfExists('parameters');
    }
}
