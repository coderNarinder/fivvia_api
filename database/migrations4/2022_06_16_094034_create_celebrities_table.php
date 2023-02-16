<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCelebritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celebrities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->mediumText('slug')->nullable();
            $table->string('email', 60)->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('phone_number', 24)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 - pending, 1 - active, 2 - inactive, 3 - deleted');
            $table->timestamps();
            $table->unsignedBigInteger('country_id')->nullable()->index('celebrities_country_id_foreign');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celebrities');
    }
}
