<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxRuleIdCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('customers', function (Blueprint $table) {
            $table->foreignId('tax_rule_id')->nullable()->constrained('tax_rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('customers', function (Blueprint $table) {
            $table->dropForeign(['tax_rule_id']);
            $table->dropColumn('tax_rule_id');
        });
    }
}
