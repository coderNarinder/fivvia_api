<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClientCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_currencies', function (Blueprint $table) {
            $table->foreign(['client_code'])->references(['code'])->on('clients')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['currency_id'])->references(['id'])->on('currencies')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_currencies', function (Blueprint $table) {
            $table->dropForeign('client_currencies_client_code_foreign');
            $table->dropForeign('client_currencies_currency_id_foreign');
        });
    }
}
