<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomerIdCommentStatusProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->table('rd_projects', function (Blueprint $table) {
            $table->string('comment')->nullable(true)->change();
            $table->date('start_at')->nullable(false)->change();
            $table->foreignId('customer_id')->nullable(false)->change();

        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rd_projects', function (Blueprint $table) {
            $table->string('comment')->nullable(false)->change();
            $table->date('start_at')->nullable(true)->change();
            $table->foreignId('customer_id')->nullable(true)->change();
        });
    }
}
