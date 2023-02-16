<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdatedByIdToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('agents', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('geos', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('price_rules', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('tags_for_agents', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('allocation_rules', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('payment_options', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('payout_options', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
        Schema::table('client_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by_id")->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('geos', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('price_rules', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('tags_for_agents', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('allocation_rules', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('payment_options', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('payout_options', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
        Schema::table('client_notifications', function (Blueprint $table) {
            $table->dropColumn('updated_by_id');
        });
    }
}
