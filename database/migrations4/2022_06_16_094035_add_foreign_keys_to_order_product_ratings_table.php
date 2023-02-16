<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product_ratings', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['order_vendor_product_id'])->references(['id'])->on('order_vendor_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product_ratings', function (Blueprint $table) {
            $table->dropForeign('order_product_ratings_order_id_foreign');
            $table->dropForeign('order_product_ratings_order_vendor_product_id_foreign');
        });
    }
}
