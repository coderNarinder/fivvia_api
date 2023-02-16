<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnReallocateIdOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('reallocate_order_id')->nullable()->default(null);
            $table->unsignedBigInteger('reallocate_order_ring_id')->nullable()->default(null);
            $table->foreign('reallocate_order_id')->references('id')->on('reallocate_orders')->onDelete('cascade');
            $table->foreign('reallocate_order_ring_id')->references('id')->on('reallocate_order_rings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['reallocate_order_id']);
            $table->dropForeign(['reallocate_order_ring_id']);
            $table->dropColumn('reallocate_order_id');
            $table->dropColumn('reallocate_order_ring_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
