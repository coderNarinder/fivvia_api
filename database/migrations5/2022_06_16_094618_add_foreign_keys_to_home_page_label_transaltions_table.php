<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHomePageLabelTransaltionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_page_label_transaltions', function (Blueprint $table) {
            $table->foreign(['home_page_label_id'])->references(['id'])->on('home_page_labels')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('home_page_label_transaltions', function (Blueprint $table) {
            $table->dropForeign('home_page_label_transaltions_home_page_label_id_foreign');
            $table->dropForeign('home_page_label_transaltions_language_id_foreign');
        });
    }
}
