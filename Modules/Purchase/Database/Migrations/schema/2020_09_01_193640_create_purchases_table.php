<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('pur_purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('author_id')->nullable()->constrained('public.users')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

            $table->smallInteger('status')->default(0);
            $table->string('ref_code')->nullable();
            $table->string('invoice_number')->nullable();
            $table->text('notes')->nullable();
            $table->double('discount', 10, 4)->nullable();
            $table->double('taxes', 10, 4)->nullable();
            $table->double('shipping', 10, 4)->nullable();
            $table->double('subtotal', 10, 4)->nullable();
            $table->double('total', 10, 4)->nullable();
            $table->timestamp('purchase_date')->nullable();

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
        Schema::connection('tenant')->dropIfExists('pur_purchases');
    }
}
