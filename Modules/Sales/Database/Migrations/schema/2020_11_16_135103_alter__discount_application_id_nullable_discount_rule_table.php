<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDiscountApplicationIdNullableDiscountRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('sal_discount_rules', function (Blueprint $table) {
            $table->foreignId('discount_application_id')->nullable(true)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('sal_discount_rules', function (Blueprint $table) {
            $table->foreignId('discount_application_id')->nullable(false)->change();
        });
}
}
