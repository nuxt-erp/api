<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExpensesProposalsAddSubcategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('exp_ap_proposals', function (Blueprint $table) {
            $table->foreignId('subcategory_id')->nullable()->constrained('exp_ap_subcategories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('exp_ap_proposals', function (Blueprint $table) {
            $table->dropColumn('subcategory_id');
        });
    }
}
