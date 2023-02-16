<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDriverGeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_geos', function (Blueprint $table) {
            $table->foreign(['driver_id'])->references(['id'])->on('agents')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['geo_id'])->references(['id'])->on('geos')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['team_id'])->references(['id'])->on('teams')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_geos', function (Blueprint $table) {
            $table->dropForeign('driver_geos_driver_id_foreign');
            $table->dropForeign('driver_geos_geo_id_foreign');
            $table->dropForeign('driver_geos_team_id_foreign');
        });
    }
}
