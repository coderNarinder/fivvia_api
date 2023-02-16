<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->index();
            $table->string('email', 60)->nullable()->index();
            $table->longText('description')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('facebook_auth_id')->nullable()->index();
            $table->string('twitter_auth_id')->nullable()->index();
            $table->string('google_auth_id')->nullable()->index();
            $table->string('apple_auth_id')->nullable()->index();
            $table->string('image')->nullable();
            $table->string('email_token', 20)->nullable();
            $table->timestamp('email_token_valid_till')->nullable();
            $table->string('phone_token', 20)->nullable();
            $table->timestamp('phone_token_valid_till')->nullable();
            $table->tinyInteger('is_email_verified')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_phone_verified')->default(0)->comment('1 for yes, 0 for no');
            $table->string('code')->nullable();
            $table->tinyInteger('is_superadmin')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_admin')->default(0)->comment('1 for yes, 0 for no');
            $table->string('title')->nullable();
            $table->unsignedBigInteger('timezone_id')->nullable()->index('users_timezone_id_foreign');
            $table->string('timezone')->nullable();
            $table->string('phone_number', 25)->nullable();
            $table->string('dial_code')->nullable();
            $table->string('import_user_id')->nullable();
            $table->integer('status')->default(0);
            $table->integer('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
