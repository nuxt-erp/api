<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRuleComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('tax_rule_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_rule_id')->nullable()->constrained('tax_rules')->onDelete('cascade');
            $table->string('component_name');
            $table->float('rate', 10, 4);
            $table->unsignedSmallInteger('compound')->default(0);
            $table->unsignedSmallInteger('seq')->default(1);
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
        Schema::connection('tenant')->dropIfExists('tax_rule_components');
    }
}
