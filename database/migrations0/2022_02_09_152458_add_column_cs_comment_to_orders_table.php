<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCsCommentToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('cs_comment')->nullable()->default(null);
            $table->integer('comment_updated_by')->nullable()->default(null);
            $table->timestamp('comment_updated_at')->nullable()->default(null); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('cs_comment');
            $table->dropColumn('comment_updated_by');
            $table->dropColumn('comment_updated_at');
        });
    }
}
