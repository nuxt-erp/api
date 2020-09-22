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

            $table->foreignId('machine_id')->nullable()->constrained('prod_machines')->onDelete('set null');
            $table->foreignId('phase_id')->nullable()->constrained('prod_phases')->onDelete('set null');
            $table->foreignId('previous_phase_id')->nullable()->constrained('prod_phases')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('product_id')->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('public.users')->onDelete('set null');
            $table->foreignId('last_updater_id')->nullable()->constrained('public.users')->onDelete('set null');

            // can be related to a project/sales order/something else
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->string('relation_type')->nullable();
            $table->index(['relation_id', 'relation_type']);

            // can be an employee/user/something else
            $table->unsignedBigInteger('requester_id')->nullable();
            $table->string('requester_type')->nullable();
            $table->index(['requester_id', 'requester_type']);

            $table->string('status');
            $table->string('code');
            $table->string('reference_code')->nullable();
            $table->unsignedInteger('sequence')->default(1);
            $table->tinyInteger('scheduled')->default(0);

            $table->unsignedInteger('requested_qty')->nullable();
            $table->decimal('requested_volume', 10, 4)->nullable();

            $table->unsignedInteger('scheduled_qty')->nullable();
            $table->decimal('scheduled_volume', 10, 4)->nullable();

            $table->unsignedInteger('finished_qty')->nullable();
            $table->decimal('finished_volume', 10, 4)->nullable();

            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();

            $table->date('expected_start_date')->nullable();
            $table->date('expected_finish_date')->nullable();

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