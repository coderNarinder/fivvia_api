<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocation_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id', 10)->nullable()->index('allocation_rules_client_id_foreign');
            $table->tinyInteger('manual_allocation')->default(1)->index()->comment('1 yes, 0 no');
            $table->char('auto_assign_logic', 25)->index()->comment('one by one,request sent to all, request sent batch wise, one by one forced, round robin, forced to nearest');
            $table->char('request_expiry', 20)->comment('one by one or batch');
            $table->integer('number_of_retries')->default(0);
            $table->integer('task_priority')->default(0)->index();
            $table->decimal('start_radius', 10, 3)->nullable();
            $table->string('start_before_task_time', 20)->nullable();
            $table->decimal('increment_radius', 10, 3)->nullable();
            $table->decimal('maximum_radius', 10, 3)->nullable();
            $table->integer('maximum_batch_size')->nullable();
            $table->integer('maximum_batch_count')->nullable();
            $table->integer('maximum_task_per_person')->nullable()->index();
            $table->tinyInteger('self_assign')->default(0)->index()->comment('1 yes, 0 no');
            $table->timestamps();
            $table->integer('maximum_cash_at_hand_per_person')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allocation_rules');
    }
}
