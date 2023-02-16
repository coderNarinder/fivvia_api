<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_addons', function (Blueprint $table) {
            $table->foreign(['addon_id'])->references(['id'])->on('addon_sets')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_addons', function (Blueprint $table) {
            $table->dropForeign('product_addons_addon_id_foreign');
            $table->dropForeign('product_addons_product_id_foreign');
        });
    }
}
