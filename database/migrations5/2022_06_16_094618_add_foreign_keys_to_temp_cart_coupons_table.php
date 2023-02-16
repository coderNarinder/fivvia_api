<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTempCartCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_cart_coupons', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('temp_carts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['coupon_id'])->references(['id'])->on('promocodes')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_cart_coupons', function (Blueprint $table) {
            $table->dropForeign('temp_cart_coupons_cart_id_foreign');
            $table->dropForeign('temp_cart_coupons_coupon_id_foreign');
        });
    }
}
