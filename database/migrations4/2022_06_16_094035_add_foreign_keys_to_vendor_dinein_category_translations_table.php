<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorDineinCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_dinein_category_translations', function (Blueprint $table) {
            $table->foreign(['category_id'])->references(['id'])->on('vendor_dinein_categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['language_id'])->references(['id'])->on('languages')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_dinein_category_translations', function (Blueprint $table) {
            $table->dropForeign('vendor_dinein_category_translations_category_id_foreign');
            $table->dropForeign('vendor_dinein_category_translations_language_id_foreign');
        });
    }
}
