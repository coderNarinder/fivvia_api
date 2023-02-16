<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id', 10)->nullable()->index('client_payments_client_id_foreign');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('date')->index();
            $table->decimal('amount', 10)->default(0)->index();
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
        Schema::dropIfExists('client_payments');
    }
}
