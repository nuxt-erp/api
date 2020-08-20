<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_family_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('family_id')->constrained('inv_families')->nullable()->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('inv_attributes')->nullable()->onDelete('cascade');
            $table->string('value');

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
        Schema::dropIfExists('inv_family_attributes');
    }
}
