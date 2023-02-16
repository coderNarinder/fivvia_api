<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTempCartAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_cart_addons', function (Blueprint $table) {
            $table->foreign(['addon_id'])->references(['id'])->on('addon_sets')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['cart_product_id'])->references(['id'])->on('temp_cart_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['option_id'])->references(['id'])->on('addon_options')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_cart_addons', function (Blueprint $table) {
            $table->dropForeign('temp_cart_addons_addon_id_foreign');
            $table->dropForeign('temp_cart_addons_cart_product_id_foreign');
            $table->dropForeign('temp_cart_addons_option_id_foreign');
        });
    }
}
