<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTeamTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_tags', function (Blueprint $table) {
            $table->foreign(['tag_id'])->references(['id'])->on('tags_for_teams')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['team_id'])->references(['id'])->on('teams')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_tags', function (Blueprint $table) {
            $table->dropForeign('team_tags_tag_id_foreign');
            $table->dropForeign('team_tags_team_id_foreign');
        });
    }
}
