<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoicesVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_invoices_vendor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->index();
            $table->mediumText('slug')->nullable();
            $table->unsignedTinyInteger('payment_option_id')->nullable()->index();
            $table->unsignedTinyInteger('status_id')->default(0)->index();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('frequency');
            $table->text('transaction_reference')->nullable();
            $table->date('start_date')->nullable();
            $table->date('next_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('subscription_amount', 12)->nullable();
            $table->decimal('discount_amount', 12)->nullable();
            $table->decimal('paid_amount', 12)->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->dateTime('ended_at')->nullable();
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
        Schema::dropIfExists('subscription_invoices_vendor');
    }
}
