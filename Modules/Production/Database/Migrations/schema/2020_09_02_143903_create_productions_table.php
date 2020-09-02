<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('prod_productions', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('prod_machines');

            $table->unsignedBigInteger('phase_id')->nullable();
            $table->foreign('phase_id')->references('id')->on('prod_phases');

            $table->unsignedBigInteger('previous_phase_id')->nullable();
            $table->foreign('previous_phase_id')->references('id')->on('prod_phases');

            $table->unsignedBigInteger('relation_id')->nullable();
            $table->index(['relation_id', 'relation_type']);

            $table->string('relation_type')->nullable();

            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('inv_products');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('parameters');

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');

            $table->unsignedBigInteger('requester_id')->nullable();
            $table->foreign('requester_id')->references('id')->on('employees');

            $table->unsignedBigInteger('last_updater_id')->nullable();
            $table->foreign('last_updater_id')->references('id')->on('users');

            $table->unsignedBigInteger('finished_size_id')->nullable();
            $table->foreign('finished_size_id')->references('id')->on('parameters');

            $table->string('sku');

            $table->string('code');

            $table->string('digit');

            $table->string('reference_code')->nullable();

            $table->unsignedInteger('volume')->nullable();

            $table->unsignedInteger('finished_qty');

            $table->unsignedInteger('requested_qty');

            $table->unsignedInteger('scheduled_qty')->nullable();

            $table->unsignedInteger('sequence')->nullable();

            $table->tinyInteger('scheduled');

            $table->date('finished_at')->nullable();

            $table->date('expected_start_date')->nullable();

            $table->date('planned_date')->nullable();

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
        Schema::connection('tenant')->dropIfExists('prod_productions');
    }
}
