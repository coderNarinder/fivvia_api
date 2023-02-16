<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_currencies', function (Blueprint $table) {
            $table->string('client_code', 10)->nullable()->index('client_currencies_client_code_foreign');
            $table->unsignedBigInteger('currency_id')->nullable()->index('client_currencies_currency_id_foreign');
            $table->tinyInteger('is_primary')->default(0)->comment('1 for yes, 0 for no');
            $table->decimal('doller_compare', 14, 8)->nullable();
            $table->timestamps();
            $table->bigIncrements('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_currencies');
    }
}
