<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCartCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('carts')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->dropForeign('cart_coupons_cart_id_foreign');
            $table->dropForeign('cart_coupons_coupon_id_foreign');
        });
    }
}
