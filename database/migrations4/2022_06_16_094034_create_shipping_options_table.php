<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('path')->nullable();
            $table->string('title');
            $table->string('credentials')->nullable()->comment('credentials in json format');
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active, 2 delete');
            $table->unsignedTinyInteger('test_mode')->default(0)->comment('0 = false, 1 = true');
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
        Schema::dropIfExists('shipping_options');
    }
}
