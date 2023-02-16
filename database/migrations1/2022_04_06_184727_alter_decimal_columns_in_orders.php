<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDecimalColumnsInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('cash_to_be_collected', 12, 2)->change();
            $table->decimal('base_distance', 12, 3)->change();
            $table->decimal('base_price', 12, 2)->change();
            $table->decimal('duration_price', 12, 2)->change();
            $table->decimal('waiting_price', 12, 2)->change();
            $table->decimal('distance_fee', 12, 2)->change();
            $table->decimal('cancel_fee', 12, 2)->change();
            $table->decimal('order_cost', 12, 2)->change();
            $table->decimal('driver_cost', 12, 2)->change();
            $table->decimal('minimum_fare', 12, 2)->change();
            $table->decimal('multidrop_fee', 12, 2)->change();
            $table->decimal('scheduled_order_extra', 12, 2)->change();
            $table->decimal('total_discount', 12, 2)->change();
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
            //
        });
    }
}
