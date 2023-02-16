<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAppStylingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_styling_options', function (Blueprint $table) {
            $table->foreign(['app_styling_id'])->references(['id'])->on('app_stylings')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_styling_options', function (Blueprint $table) {
            $table->dropForeign('app_styling_options_app_styling_id_foreign');
        });
    }
}
