<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingColumnsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('minimum_fare', 10, 2)->nullable()->default(0);
            $table->decimal('multidrop_fee', 10, 2)->nullable()->default(0);
            $table->decimal('scheduled_order_extra', 10, 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('minimum_fare');
            $table->dropColumn('multidrop_fee');
            $table->dropColumn('scheduled_order_extra');
        });
    }
}
