<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->text('access_token')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('expires_at', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('nickname', 255)->nullable();
            $table->string('provider_id', 255)->nullable();
            $table->string('provider_name', 255)->nullable();
            $table->text('refresh_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_credentials');
    }
}
