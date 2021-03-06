<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('exp_ap_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expenses_proposal_id')->constrained('exp_ap_proposals')->onDelete('cascade');
            $table->string('file_name')->unique();
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
        Schema::connection('tenant')->dropIfExists('exp_ap_attachments');
    }
}
