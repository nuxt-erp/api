<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvStockAdjustmentsAddAuthorIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('inv_stock_adjustments', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->string('name')->nullable();
            $table->timestamp('effective_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('inv_stock_adjustments', function (Blueprint $table) {
            $table->dropColumn('author_id');
            $table->dropColumn('name');
            $table->dropColumn('effective_date');
        });
    }
}
