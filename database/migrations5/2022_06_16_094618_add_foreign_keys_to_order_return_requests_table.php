<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['order_vendor_product_id'])->references(['id'])->on('order_vendor_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['return_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->dropForeign('order_return_requests_order_id_foreign');
            $table->dropForeign('order_return_requests_order_vendor_product_id_foreign');
            $table->dropForeign('order_return_requests_return_by_foreign');
        });
    }
}
