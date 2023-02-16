<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUserLoyaltyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_loyalty_points', function (Blueprint $table) {
            $table->foreign(['loyalty_card_id'])->references(['id'])->on('loyalty_cards')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_loyalty_points', function (Blueprint $table) {
            $table->dropForeign('user_loyalty_points_loyalty_card_id_foreign');
            $table->dropForeign('user_loyalty_points_user_id_foreign');
        });
    }
}
