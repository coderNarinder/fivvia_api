<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('city', 255)->nullable();
            $table->decimal('actual_time', 12, 2)->nullable()->change();
            $table->decimal('actual_distance', 12, 2)->nullable()->change();
            $table->decimal('pickup_latitude', 12, 8)->nullable();
            $table->decimal('pickup_longitude', 12, 8)->nullable();
            $table->string('pickup_address', 255)->nullable();
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
            $table->dropColumn('city');
            $table->dropColumn('pickup_latitude');
            $table->dropColumn('pickup_longitude');
            $table->dropColumn('pickup_address');
        });
    }
}
