<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('agent_id')->index('agent_logs_agent_id_foreign');
            $table->bigInteger('current_task_id')->nullable();
            $table->decimal('lat', 10, 8)->default(0)->index();
            $table->decimal('long', 12, 8)->default(0)->index();
            $table->smallInteger('battery_level')->default(0);
            $table->string('os_version', 20)->nullable();
            $table->string('app_version', 10)->nullable();
            $table->string('current_speed', 20)->nullable();
            $table->tinyInteger('on_route')->default(1)->index()->comment('for task -> 1 for Yes,n for 0');
            $table->timestamps();
            $table->string('device_type')->nullable();
            $table->decimal('heading_angle', 12, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_logs');
    }
}
