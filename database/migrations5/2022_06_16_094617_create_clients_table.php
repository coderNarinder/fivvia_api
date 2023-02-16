<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('email', 60);
            $table->string('phone_number', 24)->nullable()->index();
            $table->string('password');
            $table->unsignedBigInteger('country_id')->nullable()->index('clients_country_id_foreign');
            $table->string('timezone')->nullable();
            $table->string('custom_domain', 50)->nullable()->index();
            $table->tinyInteger('is_deleted')->default(0)->index();
            $table->tinyInteger('is_blocked')->default(0)->index();
            $table->string('database_path')->nullable();
            $table->string('database_name', 50)->nullable()->index();
            $table->string('database_username', 50)->nullable();
            $table->string('database_password', 50)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('company_name', 50)->nullable()->index();
            $table->string('company_address', 100)->nullable();
            $table->tinyInteger('status')->default(0)->index()->comment('1 for active, 0 for pending, 2 for blocked, 3 for inactive');
            $table->string('code', 10)->unique();
            $table->string('contact_email')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_phone_number')->nullable();
            $table->timestamps();
            $table->string('confirm_password')->nullable();
            $table->string('sub_domain')->nullable();
            $table->string('database_host', 500)->nullable();
            $table->string('database_port', 500)->nullable();
            $table->tinyInteger('is_superadmin')->default(1)->comment('1 for yes, 0 for no');
            $table->tinyInteger('all_team_access')->default(1)->comment('1 for all/yes, 0 for no');
            $table->string('public_login_session', 500)->nullable();
            $table->string('dial_code', 50)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('encpass', 255)->nullable();
            $table->integer('business_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
