<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('email', 60)->index();
            $table->string('phone_number', 24)->nullable()->index();
            $table->string('password');
            $table->tinyInteger('can_create_task')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('can_edit_task_created')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('can_edit_all')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('can_manage_unassigned_tasks')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('can_edit_auto_allocation')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->unsignedBigInteger('client_id')->index('managers_client_id_foreign');
            $table->string('profile_picture');
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
        Schema::dropIfExists('managers');
    }
}
