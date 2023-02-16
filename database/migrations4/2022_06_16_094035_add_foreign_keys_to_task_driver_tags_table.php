<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTaskDriverTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_driver_tags', function (Blueprint $table) {
            $table->foreign(['tag_id'])->references(['id'])->on('tags_for_agents')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['task_id'])->references(['id'])->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_driver_tags', function (Blueprint $table) {
            $table->dropForeign('task_driver_tags_tag_id_foreign');
            $table->dropForeign('task_driver_tags_task_id_foreign');
        });
    }
}
