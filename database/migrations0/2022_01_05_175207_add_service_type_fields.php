<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceTypeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags_for_agents', function (Blueprint $table) {
            $table->string('slug', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('business_unit', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('size')->nullable()->comments('In ft');
            $table->integer('capacity')->nullable()->comments('In kg');
            $table->integer('user_app_sequence')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('specification')->nullable();
            $table->tinyInteger('status')->default(0)->comments('0=Inactive, 1=Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags_for_agents', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('icon');
            $table->dropColumn('business_unit');
            $table->dropColumn('description');
            $table->dropColumn('size');
            $table->dropColumn('capacity');
            $table->dropColumn('user_app_sequence');
            $table->dropColumn('city_id');
            $table->dropColumn('specification');
            $table->dropColumn('status');
        });
    }
}
