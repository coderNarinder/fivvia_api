<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEstimateAddonSetTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimate_addon_set_translations', function (Blueprint $table) {
            $table->foreign(['estimate_addon_id'])->references(['id'])->on('estimate_addon_sets')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_addon_set_translations', function (Blueprint $table) {
            $table->dropForeign('estimate_addon_set_translations_estimate_addon_id_foreign');
        });
    }
}
