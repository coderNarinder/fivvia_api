<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onboard_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key_value')->nullable();
            $table->tinyInteger('enable_from')->default(1)->comment('1 : For GodPanel');
            $table->tinyInteger('on_off')->default(0)->comment('0 : For off');
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
        Schema::dropIfExists('onboard_settings');
    }
}
