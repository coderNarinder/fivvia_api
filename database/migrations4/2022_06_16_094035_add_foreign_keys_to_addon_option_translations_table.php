<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAddonOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addon_option_translations', function (Blueprint $table) {
            $table->foreign(['addon_opt_id'])->references(['id'])->on('addon_options')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addon_option_translations', function (Blueprint $table) {
            $table->dropForeign('addon_option_translations_addon_opt_id_foreign');
        });
    }
}
