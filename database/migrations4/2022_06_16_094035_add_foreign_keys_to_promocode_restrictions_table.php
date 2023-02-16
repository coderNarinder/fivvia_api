<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPromocodeRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promocode_restrictions', function (Blueprint $table) {
            $table->foreign(['promocode_id'])->references(['id'])->on('promocodes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promocode_restrictions', function (Blueprint $table) {
            $table->dropForeign('promocode_restrictions_promocode_id_foreign');
        });
    }
}
