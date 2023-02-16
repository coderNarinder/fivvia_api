<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description',570)->nullable();
            $table->string('image')->default(null)->nullable();
            $table->text('driver_ids')->default(null)->nullable();
            $table->string('send_service')->default("fcm")->comments('fcm,email,sms')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->integer('send_to_all')->default(0)->nullable();
            $table->integer('is_send')->default(0)->nullable();
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
        Schema::dropIfExists('send_notifications');
    }
}
