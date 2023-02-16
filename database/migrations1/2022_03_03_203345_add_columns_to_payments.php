<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('amount')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('balance_transaction')->nullable();
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('payment_option_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('transaction_id');
            $table->dropColumn('balance_transaction');
            $table->dropColumn('type');
            $table->dropColumn('date');
            $table->dropColumn('payment_option_id');
        });
    }
}
