<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAgentPayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_payouts', function (Blueprint $table) {
            $table->string('slug', 255)->nullable();
            $table->unsignedBigInteger('agent_cashout_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('failed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_payouts', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('agent_cashout_id');
            $table->dropColumn('updated_by_id');
            $table->dropColumn('approved_at');
            $table->dropColumn('failed_at');
        });
    }
}
