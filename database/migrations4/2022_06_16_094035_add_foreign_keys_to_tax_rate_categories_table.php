<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTaxRateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_rate_categories', function (Blueprint $table) {
            $table->foreign(['tax_cate_id'])->references(['id'])->on('tax_categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['tax_rate_id'])->references(['id'])->on('tax_rates')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_rate_categories', function (Blueprint $table) {
            $table->dropForeign('tax_rate_categories_tax_cate_id_foreign');
            $table->dropForeign('tax_rate_categories_tax_rate_id_foreign');
        });
    }
}
