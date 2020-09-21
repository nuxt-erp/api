<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSampleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_project_sample_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('project_sample_id')->constrained('rd_project_samples')->onDelete('cascade');
            $table->foreignId('assignee_id')->nullable()->constrained('public.users')->onDelete('set null');

            $table->string('status')->nullable();
            $table->string('feedback')->nullable();
            $table->string('comment')->nullable();

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
        Schema::connection('tenant')->dropIfExists('rd_project_sample_logs');
    }
}
