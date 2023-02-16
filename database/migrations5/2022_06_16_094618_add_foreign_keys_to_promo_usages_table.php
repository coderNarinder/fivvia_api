<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPromoUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_usages', function (Blueprint $table) {
            $table->foreign(['promocode_id'])->references(['id'])->on('promocodes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_usages', function (Blueprint $table) {
            $table->dropForeign('promo_usages_promocode_id_foreign');
            $table->dropForeign('promo_usages_user_id_foreign');
        });
    }
}
