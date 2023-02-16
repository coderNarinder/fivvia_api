<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSubscriptionPlanFeaturesUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plan_features_user', function (Blueprint $table) {
            $table->foreign(['feature_id'])->references(['id'])->on('subscription_features_list_user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['subscription_plan_id'])->references(['id'])->on('subscription_plans_user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plan_features_user', function (Blueprint $table) {
            $table->dropForeign('subscription_plan_features_user_feature_id_foreign');
            $table->dropForeign('subscription_plan_features_user_subscription_plan_id_foreign');
        });
    }
}
