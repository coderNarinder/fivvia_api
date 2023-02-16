<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign(['dependent_task_id'])->references(['id'])->on('tasks')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['pricing_rule_id'])->references(['id'])->on('price_rules')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['task_type_id'])->references(['id'])->on('task_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_dependent_task_id_foreign');
            $table->dropForeign('tasks_order_id_foreign');
            $table->dropForeign('tasks_pricing_rule_id_foreign');
            $table->dropForeign('tasks_task_type_id_foreign');
        });
    }
}
