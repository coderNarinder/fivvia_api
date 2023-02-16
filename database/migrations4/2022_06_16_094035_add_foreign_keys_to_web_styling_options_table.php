<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWebStylingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_styling_options', function (Blueprint $table) {
            $table->foreign(['web_styling_id'])->references(['id'])->on('web_stylings')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_styling_options', function (Blueprint $table) {
            $table->dropForeign('web_styling_options_web_styling_id_foreign');
        });
    }
}
