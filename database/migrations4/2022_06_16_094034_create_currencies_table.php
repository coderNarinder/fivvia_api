<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->index();
            $table->integer('priority')->default(0)->index();
            $table->string('iso_code', 5)->index();
            $table->string('symbol', 10);
            $table->string('subunit', 20);
            $table->integer('subunit_to_unit');
            $table->tinyInteger('symbol_first');
            $table->string('html_entity', 25);
            $table->string('decimal_mark', 10);
            $table->string('thousands_separator', 10);
            $table->smallInteger('iso_numeric')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
