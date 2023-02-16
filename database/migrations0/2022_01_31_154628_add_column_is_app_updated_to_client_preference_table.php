<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsAppUpdatedToClientPreferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->integer('is_app_update')->default(0)->nullable();
            $table->string('android_version',25)->default("1.0")->nullable();
            $table->string('android_build_number',25)->default("9")->nullable();
            $table->string('ios_version',25)->default("1.0")->nullable();
            $table->string('ios_build_number',25)->default("2")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('is_app_update');
            $table->dropColumn('android_version');
            $table->dropColumn('android_build_number');
            $table->dropColumn('ios_version');
            $table->dropColumn('ios_build_number');
        });
    }
}
