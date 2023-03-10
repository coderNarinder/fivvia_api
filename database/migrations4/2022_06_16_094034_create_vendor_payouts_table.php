<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('payout_option_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 12)->default(0);
            $table->unsignedBigInteger('currency')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-pending, 1-paid, 2-failed');
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
        Schema::dropIfExists('vendor_payouts');
    }
}
