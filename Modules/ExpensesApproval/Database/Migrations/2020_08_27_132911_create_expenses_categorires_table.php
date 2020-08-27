<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesCategoriresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_ap_categories', function (Blueprint $table) {
            $table->id(); 
            $table->string('name')->unique();
            $table->foreignId('team_leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('director_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_finished')->default(1);
            $table->timestamp('finished_at')->nullable(); 
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
        Schema::dropIfExists('exp_ap_categories');
    }
}
