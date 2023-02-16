<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('business_type')->nullable()->comment('cab_booking');
            $table->tinyInteger('rating_check')->nullable()->default(0);
            $table->tinyInteger('enquire_mode')->nullable()->default(0);
            $table->tinyInteger('hide_nav_bar')->default(0);
            $table->tinyInteger('show_dark_mode')->default(2);
            $table->tinyInteger('show_payment_icons')->default(0);
            $table->tinyInteger('loyalty_check')->default(0)->comment('0-Active, 1-Inactive');
            $table->tinyInteger('show_icons')->default(0);
            $table->tinyInteger('show_wishlist')->default(0);
            $table->tinyInteger('show_contact_us')->default(0);
            $table->tinyInteger('age_restriction')->default(0);
            $table->string('age_restriction_title')->nullable();
            $table->tinyInteger('subscription_mode')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('cart_enable')->nullable()->default(0);
            $table->string('client_id', 10)->nullable()->index('client_preferences_client_id_foreign');
            $table->string('theme', 15)->default('light')->index()->comment('light,dark');
            $table->string('distance_unit', 10)->nullable()->index()->comment('KM, miles');
            $table->unsignedBigInteger('currency_id')->nullable()->index('client_preferences_currency_id_foreign');
            $table->unsignedBigInteger('language_id')->nullable()->index('client_preferences_language_id_foreign');
            $table->string('agent_name', 20)->nullable()->comment('name type for agent field - Driver, Service Provider etc.');
            $table->string('acknowledgement_type', 20)->default('Acknowledge')->comment('Acknowledge,Accept/Reject, None');
            $table->string('date_format', 15)->nullable();
            $table->tinyInteger('time_format')->default(1)->comment('1 for 24 format, 0 for 12 hour format');
            $table->string('map_type', 15)->default('google')->index()->comment('google,mapbox');
            $table->string('map_key_1', 50)->nullable();
            $table->string('map_key_2', 50)->nullable();
            $table->string('sms_provider', 20)->nullable();
            $table->string('sms_provider_key_1', 50)->nullable()->comment('primary key');
            $table->string('sms_provider_key_2', 50)->nullable()->comment('secrate key');
            $table->bigInteger('allow_feedback_tracking_url')->default(0)->comment('1 yes, 0 no');
            $table->string('task_type', 30)->nullable()->index();
            $table->string('order_id', 20)->nullable();
            $table->string('email_plan', 10)->default('free')->index()->comment('free,paid');
            $table->string('domain_name', 50)->nullable()->index();
            $table->string('personal_access_token_v1', 60)->nullable();
            $table->string('personal_access_token_v2', 60)->nullable();
            $table->string('fcm_server_key', 600)->nullable();
            $table->timestamps();
            $table->string('sms_provider_number', 20)->nullable();
            $table->tinyInteger('allow_all_location')->default(0)->comment('1 for all/yes, 0 for own locations/no');
            $table->tinyInteger('route_flat_input')->default(0);
            $table->tinyInteger('route_alcoholic_input')->default(0);
            $table->string('customer_support');
            $table->string('customer_support_key');
            $table->string('customer_support_application_id');
            $table->longText('sms_credentials')->nullable()->comment('sms credentials in json format');
            $table->tinyInteger('verify_phone_for_driver_registration')->default(0)->comment('1 for yes, 0 for no');
            $table->decimal('reffered_by_amount')->nullable()->default(0);
            $table->decimal('reffered_to_amount')->nullable()->default(0);
            $table->string('favicon')->nullable();
            $table->tinyInteger('is_edit_order_driver')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->string('mail_type', 255)->nullable();
            $table->string('mail_driver', 255)->nullable();
            $table->string('mail_host', 255)->nullable();
            $table->smallInteger('mail_port')->nullable();
            $table->string('mail_username', 255)->nullable();
            $table->string('mail_password', 100)->nullable();
            $table->string('mail_encryption', 255)->nullable();
            $table->string('mail_from', 255)->nullable();
            $table->integer('is_hyperlocal')->default(0);
            $table->integer('need_delivery_service')->default(0);
            $table->integer('need_dispacher_ride')->default(0);
            $table->string('delivery_service_key', 255)->nullable();
            $table->string('primary_color', 10)->nullable();
            $table->string('secondary_color', 10)->nullable();
            $table->string('site_top_header_color', 10)->nullable()->default('#4c4c4c');
            $table->string('dispatcher_key', 100)->nullable();
            $table->string('theme_admin', 255)->default('light');
            $table->integer('fb_login')->default(0);
            $table->string('fb_client_id', 100)->nullable();
            $table->string('fb_client_secret', 255)->nullable();
            $table->string('fb_client_url', 255)->nullable();
            $table->integer('twitter_login')->default(0);
            $table->string('twitter_client_id', 100)->nullable();
            $table->string('twitter_client_secret', 100)->nullable();
            $table->string('twitter_client_url', 255)->nullable();
            $table->integer('google_login')->default(0);
            $table->string('google_client_id', 100)->nullable();
            $table->string('google_client_secret', 100)->nullable();
            $table->string('google_client_url', 200)->nullable();
            $table->integer('apple_login')->default(0);
            $table->string('apple_client_id', 100)->nullable();
            $table->string('apple_client_secret', 100)->nullable();
            $table->string('apple_client_url', 200)->nullable();
            $table->string('Default_location_name', 200);
            $table->decimal('Default_latitude', 10, 0)->default(0);
            $table->decimal('Default_longitude', 10, 0)->default(0);
            $table->integer('map_provider')->nullable();
            $table->string('map_key', 255)->nullable();
            $table->string('map_secret', 255)->nullable();
            $table->integer('verify_email')->default(0);
            $table->integer('verify_phone')->default(0);
            $table->integer('web_template_id')->nullable();
            $table->integer('app_template_id')->nullable();
            $table->string('fcm_api_key', 255)->nullable();
            $table->string('fcm_auth_domain', 255)->nullable();
            $table->integer('fcm_project_id')->nullable();
            $table->string('fcm_storage_bucket', 255)->nullable();
            $table->string('fcm_messaging_sender_id', 255)->nullable();
            $table->string('fcm_app_id', 255)->nullable();
            $table->string('fcm_measurement_id', 255)->nullable();
            $table->string('distance_unit_for_time', 255)->nullable();
            $table->string('distance_to_time_multiplier', 255)->nullable();
            $table->string('delay_order', 255)->nullable();
            $table->string('product_order_form', 255)->nullable();
            $table->integer('client_code')->nullable();
            $table->tinyInteger('celebrity_check')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->string('delivery_service_key_url')->nullable();
            $table->string('delivery_service_key_code')->nullable();
            $table->string('web_color')->nullable();
            $table->tinyInteger('pharmacy_check')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('dinein_check')->nullable()->default(1)->comment('0-No, 1-Yes');
            $table->tinyInteger('takeaway_check')->nullable()->default(1)->comment('0-No, 1-Yes');
            $table->tinyInteger('delivery_check')->nullable()->default(1)->comment('0-No, 1-Yes');
            $table->string('pickup_delivery_service_key')->nullable();
            $table->string('pickup_delivery_service_key_url')->nullable();
            $table->string('pickup_delivery_service_key_code')->nullable();
            $table->string('need_dispacher_home_other_service')->nullable();
            $table->string('dispacher_home_other_service_key')->nullable();
            $table->string('dispacher_home_other_service_key_url')->nullable();
            $table->string('dispacher_home_other_service_key_code')->nullable();
            $table->string('last_mile_team')->nullable();
            $table->tinyInteger('tip_before_order')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('tip_after_order')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->boolean('off_scheduling_at_cart')->nullable()->default(false)->comment('0-No, 1-Yes');
            $table->tinyInteger('isolate_single_vendor_order')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->mediumText('android_app_link')->nullable();
            $table->mediumText('ios_link')->nullable();
            $table->tinyInteger('single_vendor')->default(0);
            $table->tinyInteger('stripe_connect')->default(0);
            $table->tinyInteger('need_laundry_service')->default(0)->index()->comment('0 - no, 1 - yes');
            $table->string('laundry_service_key')->nullable();
            $table->string('laundry_service_key_url')->nullable();
            $table->string('laundry_service_key_code')->nullable();
            $table->string('laundry_pickup_team')->nullable();
            $table->string('laundry_dropoff_team')->nullable();
            $table->string('admin_email', 100)->nullable()->comment('mainly used for orders and place in cc');
            $table->tinyInteger('gifting')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('pickup_delivery_service_area')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('shipping_mode')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('minimum_order_batch')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('is_edit_order_admin')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('is_edit_order_vendor')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('static_delivey_fee')->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('header_quick_link')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('get_estimations')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->tinyInteger('tools_mode')->nullable()->default(0)->comment('0-No, 1-Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_preferences');
    }
}
