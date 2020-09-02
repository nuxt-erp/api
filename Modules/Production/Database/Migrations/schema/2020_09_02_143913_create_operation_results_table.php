<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_operation_results', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('production_order_id');
            $table->foreign('production_order_id')->references('id')->on('prod_production_orders');

            $table->unsignedBigInteger('operation_id');
            $table->foreign('operation_id')->references('id')->on('prod_operations');

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('prod_machines');

            $table->unsignedBigInteger('first_operator_id')->nullable();
            $table->foreign('first_operator_id')->references('id')->on('employees');

            $table->unsignedBigInteger('second_operator_id')->nullable();
            $table->foreign('second_operator_id')->references('id')->on('employees');

            $table->unsignedBigInteger('reason_type_id')->nullable();
            $table->foreign('reason_type_id')->references('id')->on('parameters');

            $table->unsignedInteger('handled_qty')->default(0);
            $table->unsignedInteger('to_handle_qty');

            $table->unsignedInteger('handled_volume')->default(0);
            $table->unsignedInteger('to_handle_volume');

            $table->string('process_code')->nullable();

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
        Schema::connection('tenant')->dropIfExists('prod_operation_results');
    }
}
