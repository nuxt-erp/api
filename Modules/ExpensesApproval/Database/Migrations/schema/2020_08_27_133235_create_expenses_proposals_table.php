<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('exp_ap_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expenses_category_id')->nullable()->constrained('exp_ap_categories')->onDelete('set null');
            $table->foreignId('author_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->string('item');
            $table->text('reason');
            $table->text('supplier_link')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('hst', 10, 2)->default(0);
            $table->decimal('ship', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->foreignId('status_id')->constrained('parameters')->onDelete('restrict'); 
            $table->timestampTz('purchase_date', 0)->nullable();
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
        Schema::connection('tenant')->dropIfExists('exp_ap_proposals');
    }
}
