<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorOrderDispatcherStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_order_dispatcher_statuses', function (Blueprint $table) {
            $table->foreign(['dispatcher_status_option_id'], 'dispatcher_statuses_dispatcher_status_option_id_foreign')->references(['id'])->on('dispatcher_status_options')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['order_id'], 'dispatcher_statuses_order_id_foreign')->references(['id'])->on('orders')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('vendor_order_dispatcher_statuses', function (Blueprint $table) {
            $table->dropForeign('dispatcher_statuses_dispatcher_status_option_id_foreign');
            $table->dropForeign('dispatcher_statuses_order_id_foreign');
            $table->dropForeign('vendor_order_dispatcher_statuses_vendor_id_foreign');
        });
    }
}
