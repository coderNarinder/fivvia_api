<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebStylingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_styling_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('web_styling_id')->nullable()->index('web_styling_options_web_styling_id_foreign');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_selected')->default(1)->comment('1-yes, 2-no');
            $table->tinyInteger('template_id')->nullable();
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
        Schema::dropIfExists('web_styling_options');
    }
}
