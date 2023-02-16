<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderProductAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product_addons', function (Blueprint $table) {
            $table->foreign(['addon_id'])->references(['id'])->on('addon_sets')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['option_id'])->references(['id'])->on('addon_options')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['order_product_id'])->references(['id'])->on('order_vendor_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product_addons', function (Blueprint $table) {
            $table->dropForeign('order_product_addons_addon_id_foreign');
            $table->dropForeign('order_product_addons_option_id_foreign');
            $table->dropForeign('order_product_addons_order_product_id_foreign');
        });
    }
}
