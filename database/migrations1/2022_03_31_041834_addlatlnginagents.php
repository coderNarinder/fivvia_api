<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addlatlnginagents extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->decimal('agent_lat', 10, 8)->default(0);
			$table->decimal('agent_long', 12, 8)->default(0);
        });

        Schema::table('agents', function (Blueprint $table) {
		
			$table->index('agent_lat');
			$table->index('agent_long');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('agent_lat');
            $table->dropColumn('agent_long');
        });
    }
}
