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
        Schema::connection('tenant')->create('inv_family_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('family_id')->nullable()->constrained('inv_families')->onDelete('cascade');
            $table->foreignId('attribute_id')->nullable()->constrained('inv_attributes')->onDelete('cascade');
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
        Schema::connection('tenant')->dropIfExists('inv_family_attributes');
    }
}
