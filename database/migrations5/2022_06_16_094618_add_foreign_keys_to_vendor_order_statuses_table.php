<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_order_statuses', function (Blueprint $table) {
            $table->foreign(['order_id'], 'order_statuses_order_id_foreign')->references(['id'])->on('orders')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['order_status_option_id'], 'order_statuses_order_status_option_id_foreign')->references(['id'])->on('order_status_options')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['vendor_id'])->references(['id'])->on('vendors')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_order_statuses', function (Blueprint $table) {
            $table->dropForeign('order_statuses_order_id_foreign');
            $table->dropForeign('order_statuses_order_status_option_id_foreign');
            $table->dropForeign('vendor_order_statuses_vendor_id_foreign');
        });
    }
}
