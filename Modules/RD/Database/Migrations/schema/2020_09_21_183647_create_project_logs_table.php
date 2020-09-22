<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rd_project_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('project_id')->constrained('rd_projects')->onDelete('cascade');

            $table->string('status')->nullable();
            $table->string('code')->nullable();
            $table->string('comment')->nullable();

            $table->date('start_at')->nullable();
            $table->date('closed_at')->nullable();
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
        Schema::connection('tenant')->dropIfExists('rd_project_logs');
    }
}
