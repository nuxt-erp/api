<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDoubleFieldsToDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exp_ap_proposals', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->change();
            $table->decimal('hst', 10, 2)->default(0)->change();
            $table->decimal('ship', 10, 2)->default(0)->change();
            $table->decimal('total_cost', 10, 2)->default(0)->change();
        });

        Schema::table('exp_ap_rules', function (Blueprint $table) {
            $table->decimal('start_value', 10, 2)->default(0)->change();
            $table->decimal('end_value', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exp_ap_proposals', function (Blueprint $table) {
            $table->double('subtotal', 10, 2)->default(0)->change();
            $table->double('hst', 10, 2)->default(0)->change();
            $table->double('ship', 10, 2)->default(0)->change();
            $table->double('total_cost', 10, 2)->default(0)->change();
        });

        Schema::table('exp_ap_rules', function (Blueprint $table) {
            $table->double('start_value', 10, 2)->default(0)->change();
            $table->double('end_value', 10, 2)->nullable()->change();
        });
    }
}
