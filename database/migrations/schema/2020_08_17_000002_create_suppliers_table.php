<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_type_id')->nullable()->constrained('parameters')->onDelete('set null');

            $table->string('name')->unique();
            $table->integer('lead_time')->nullable();
            $table->integer('ordering_cycle')->nullable();
            $table->timestamp('last_order_at')->nullable();
            $table->boolean('is_enabled')->default(1);
            $table->timestamp('disabled_at')->nullable();

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
        Schema::connection('tenant')->dropIfExists('suppliers');
    }
}
