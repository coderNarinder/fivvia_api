<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAgentsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents_tags', function (Blueprint $table) {
            $table->foreign(['agent_id'])->references(['id'])->on('agents')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['tag_id'])->references(['id'])->on('tags_for_agents')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents_tags', function (Blueprint $table) {
            $table->dropForeign('agents_tags_agent_id_foreign');
            $table->dropForeign('agents_tags_tag_id_foreign');
        });
    }
}
