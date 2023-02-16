<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id', 10)->nullable()->index('client_notifications_client_id_foreign');
            $table->string('webhook_url', 150)->nullable();
            $table->tinyInteger('request_recieved_sms')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('request_received_email')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->tinyInteger('request_recieved_webhook')->default(0)->index()->comment('1 for yes, 0 for no');
            $table->unsignedBigInteger('notification_event_id');
            $table->timestamps();
            $table->tinyInteger('recipient_request_recieved_sms')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('recipient_request_received_email')->default(0)->comment('1 for yes, 0 for no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_notifications');
    }
}
