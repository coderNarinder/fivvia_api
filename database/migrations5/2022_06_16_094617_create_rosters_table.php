<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->dateTime('notification_time')->nullable();
            $table->string('type', 20);
            $table->timestamps();
            $table->string('client_code', 10)->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_token')->nullable();
            $table->unsignedBigInteger('detail_id')->nullable();
            $table->integer('status')->default(0);
            $table->string('request_type')->default('Pickup Task');
            $table->decimal('cash_to_be_collected', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rosters');
    }
}
