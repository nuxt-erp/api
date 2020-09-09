<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('exp_ap_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('team_leader_approval')->default(0);
            $table->boolean('director_approval')->default(0);
            $table->decimal('start_value', 10, 2)->default(0);
            $table->decimal('end_value', 10, 2)->nullable();
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
        Schema::connection('tenant')->dropIfExists('exp_ap_rules');
    }
}
