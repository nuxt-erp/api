<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('client_id'); 
            $table->foreign('client_id')->references('id')->on('oauth_clients');

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');

            $table->unsignedBigInteger('assignee_id');
            $table->foreign('assignee_id')->references('id')->on('users');

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('parameters');

            $table->date('created_at')->nullable();

            $table->date('closed_at')->nullable();
            
            $table->string('comments');

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
        Schema::dropIfExists('projects');
    }
}