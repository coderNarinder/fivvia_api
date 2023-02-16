<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable()->index('tasks_order_id_foreign');
            $table->unsignedBigInteger('dependent_task_id')->nullable()->index('tasks_dependent_task_id_foreign');
            $table->unsignedBigInteger('task_type_id')->nullable()->index('tasks_task_type_id_foreign');
            $table->unsignedBigInteger('location_id')->nullable()->index('tasks_location_id_foreign');
            $table->string('appointment_duration', 15)->nullable();
            $table->unsignedBigInteger('pricing_rule_id')->nullable()->index('tasks_pricing_rule_id_foreign');
            $table->integer('distance')->nullable()->index();
            $table->dateTime('assigned_time')->nullable();
            $table->dateTime('accepted_time')->nullable();
            $table->dateTime('declined_time')->nullable();
            $table->dateTime('started_time')->nullable();
            $table->dateTime('reached_time')->nullable();
            $table->dateTime('failed_time')->nullable();
            $table->dateTime('cancelled_time')->nullable();
            $table->tinyInteger('cancelled_by_admin_id')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->dateTime('Completed_time')->nullable()->index();
            $table->string('task_status', 20)->default('0')->index()->comment('pending, delivered, on the way, ready for delivery, ready for departure');
            $table->string('allocation_type', 20)->index();
            $table->timestamps();
            $table->string('proof_image')->nullable();
            $table->string('proof_signature')->nullable();
            $table->text('note')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('task_order')->default(0);
            $table->integer('quantity')->nullable();
            $table->tinyInteger('alcoholic_item')->default(0);
            $table->string('proof_face')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
