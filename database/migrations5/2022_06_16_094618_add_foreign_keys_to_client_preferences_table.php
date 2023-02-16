<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->foreign(['client_id'])->references(['code'])->on('clients')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['currency_id'])->references(['id'])->on('currencies')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['language_id'])->references(['id'])->on('languages')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropForeign('client_preferences_client_id_foreign');
            $table->dropForeign('client_preferences_currency_id_foreign');
            $table->dropForeign('client_preferences_language_id_foreign');
        });
    }
}
