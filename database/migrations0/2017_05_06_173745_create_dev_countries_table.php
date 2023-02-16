<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dev_countries')) {
            Schema::create('dev_countries', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->index();
                $table->string('code');
                $table->string('name');
                $table->integer('phonecode');
                $table->integer('status')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dev_countries');
    }
}
