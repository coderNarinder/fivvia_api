<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSubscriptionPlanFeaturesVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plan_features_vendor', function (Blueprint $table) {
            $table->foreign(['feature_id'])->references(['id'])->on('subscription_features_list_vendor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['subscription_plan_id'])->references(['id'])->on('subscription_plans_vendor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plan_features_vendor', function (Blueprint $table) {
            $table->dropForeign('subscription_plan_features_vendor_feature_id_foreign');
            $table->dropForeign('subscription_plan_features_vendor_subscription_plan_id_foreign');
        });
    }
}
