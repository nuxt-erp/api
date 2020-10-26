<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->float('computation', 10, 4);
            $table->smallInteger('status')->default(1);
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('cascade');
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
        Schema::connection('tenant')->dropIfExists('tax_rules');
    }
}
