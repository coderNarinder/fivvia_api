<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->decimal('dr', 6)->nullable();
            $table->decimal('cr', 6)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('order_id')->nullable()->index('payments_order_id_foreign');
            $table->integer('cart_id')->nullable();
            $table->unsignedBigInteger('user_subscription_invoice_id')->nullable();
            $table->unsignedBigInteger('vendor_subscription_invoice_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
