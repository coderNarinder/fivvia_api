<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEstimateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimate_product_translations', function (Blueprint $table) {
            $table->foreign(['estimate_product_id'])->references(['id'])->on('estimate_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_product_translations', function (Blueprint $table) {
            $table->dropForeign('estimate_product_translations_estimate_product_id_foreign');
        });
    }
}
