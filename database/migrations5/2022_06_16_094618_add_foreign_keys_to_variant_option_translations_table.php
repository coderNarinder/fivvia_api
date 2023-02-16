<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVariantOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variant_option_translations', function (Blueprint $table) {
            $table->foreign(['variant_option_id'])->references(['id'])->on('variant_options')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variant_option_translations', function (Blueprint $table) {
            $table->dropForeign('variant_option_translations_variant_option_id_foreign');
        });
    }
}
