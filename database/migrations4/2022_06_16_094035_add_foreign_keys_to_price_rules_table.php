<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPriceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->foreign(['driver_tag_id'])->references(['id'])->on('tags_for_agents')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['geo_id'])->references(['id'])->on('geos')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['team_id'])->references(['id'])->on('teams')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['team_tag_id'])->references(['id'])->on('tags_for_teams')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->dropForeign('price_rules_driver_tag_id_foreign');
            $table->dropForeign('price_rules_geo_id_foreign');
            $table->dropForeign('price_rules_team_id_foreign');
            $table->dropForeign('price_rules_team_tag_id_foreign');
        });
    }
}
