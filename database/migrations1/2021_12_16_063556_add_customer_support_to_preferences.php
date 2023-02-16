<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerSupportToPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            // $table->string('customer_support')->nullable();
            // $table->string('customer_support_key')->nullable();
            // $table->string('customer_support_application_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('customer_support');
            $table->dropColumn('customer_support_key');
            $table->dropColumn('customer_support_application_id');
        });
    }
}
