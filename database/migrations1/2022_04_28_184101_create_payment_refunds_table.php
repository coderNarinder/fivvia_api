<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('payment_option_id')->nullable();
            $table->string('refund_id')->nullable();
            $table->decimal('amount', 12, 2)->nullable()->default(0);
            $table->string('refund_status', 80)->nullable();
            $table->longText('webhook_payload')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_refunds');
    }
}
