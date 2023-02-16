<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAddonSetTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addon_set_translations', function (Blueprint $table) {
            $table->foreign(['addon_id'])->references(['id'])->on('addon_sets')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addon_set_translations', function (Blueprint $table) {
            $table->dropForeign('addon_set_translations_addon_id_foreign');
        });
    }
}
