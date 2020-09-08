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

            $table->foreignId('production_id')->constrained('prod_productions')->onDelete('cascade');
            $table->foreignId('operation_id')->nullable()->constrained('prod_operations')->onDelete('set null');
            $table->foreignId('author_id')->constrained('public.users')->onDelete('set null');
            $table->foreignId('machine_id')->nullable()->constrained('prod_machines')->onDelete('set null');

            $table->unsignedInteger('to_handle_qty')->default(0);
            $table->decimal('to_handle_volume', 10, 4)->default(0);

            $table->unsignedInteger('handled_qty')->default(0);
            $table->decimal('handled_volume', 10, 4)->default(0);

            $table->string('process_code')->nullable();
            $table->string('comment')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

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