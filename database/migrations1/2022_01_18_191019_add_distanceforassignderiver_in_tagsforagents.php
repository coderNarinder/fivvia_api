<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanceforassignderiverInTagsforagents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags_for_agents', function (Blueprint $table) {
            $table->decimal('distance_for_assign_driver', 10, 2)->nullable(true)->default(0);
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
            $table->dropColumn('distance_for_assign_driver');
        });
    }
}
