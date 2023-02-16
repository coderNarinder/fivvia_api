<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStylingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_styling_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('app_styling_id')->nullable()->index('app_styling_options_app_styling_id_foreign');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_selected')->default(1)->comment('1-yes, 2-no');
            $table->timestamps();
            $table->tinyInteger('template_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_styling_options');
    }
}
