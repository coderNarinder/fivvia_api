<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, ClientLanguage, ClientCurrency, Nomenclature, ReferAndEarn,SocialMedia, VendorRegistrationDocument, PageTranslation, BrandTranslation, VariantTranslation, ProductTranslation, Category_translation, AddonOptionTranslation, ClientSlot, DriverRegistrationDocument, VariantOptionTranslation,Tag};
use GuzzleHttp\Client as GCLIENT;
use DB;
use App\Http\Traits\ApiResponser;
class ClientPreferenceController extends BaseController{

    private $folderName = 'category/icon';
    public $client_id =0;
    public function __construct()
    {
       $this->client_id = getWebClientID();
        $code = Client::where('id',getWebClientID())->value('code');
        $this->folderName = '/'.$code.'/banner';
    }
    use ApiResponser;
    public function index(){
        $client = Auth::user();
        $mapTypes = MapProvider::where('client_id',$this->client_id)->where('status', '1')->get();
        $smsTypes = SmsProvider::where('client_id',$this->client_id)->where('status', '1')->get();
        $ClientPreference = ClientPreference::where('client_id',$this->client_id)->first();
        $preference = $ClientPreference ? $ClientPreference : new ClientPreference();
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', $this->client_id)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        $file_types = ['image/*' => 'Image', '.pdf' => 'Pdf'];
        $file_types_driver = ['image/*' => 'Image', '.pdf' => 'Pdf','.txt'=>'Text'];
        $vendor_registration_documents = VendorRegistrationDocument::with('primary')->get();
        $driver_registration_documents = DriverRegistrationDocument::with('primary')->get();
        if($preference->reffered_by_amount == null){
            $reffer_by = 0;
        }else{
            $reffer_by = $preference->reffered_by_amount;
        }
        if($preference->reffered_to_amount == null){
            $reffer_to = 0;
        }else{
            $reffer_to = $preference->reffered_to_amount;
        }

        $last_mile_teams = [];
        $laundry_teams = [];
        # if last mile on
        if(isset($preference) && $preference->need_delivery_service == '1') {
            $last_mile_teams = $this->getLastMileTeams();

        }
        # if laundry on
        if(isset($preference) && $preference->need_laundry_service == '1') {
            $laundry_teams = $this->getLaundryTeams();

        }

        $tags = Tag::with('primary')->get();
        $slots = ClientSlot::get();
        return view('backend/setting/config')->with([
            'tags' => $tags,
            'slots'=>$slots,
            'laundry_teams' => $laundry_teams,
            'last_mile_teams' => $last_mile_teams,
            'client' => $client, 'preference' => $preference, 'mapTypes'=> $mapTypes, 'smsTypes' => $smsTypes, 'client_languages' => $client_languages, 'file_types' => $file_types, 'vendor_registration_documents' => $vendor_registration_documents, 'driver_registration_documents' => $driver_registration_documents, 'reffer_by' => $reffer_by, 'reffer_to' => $reffer_to, 'file_types_driver' => $file_types_driver]);
    }

    public function getCustomizePage(ClientPreference $clientPreference){
        $curArray = [];
        $cli_langs = [];
        $reffer_by = "";
        $reffer_to = "";
        $cli_currs = [];
        $client = Auth::user();
        $social_media_details = SocialMedia::where('client_id',$this->client_id)->get();
        $webTemplates = Template::where('for', '1')->get();
        $appTemplates = Template::where('for', '2')->get();
        $languages = Language::where('id', '>', '0')->get();
        $currencies = Currency::all();
        $curtableData = array_chunk($currencies->toArray(), 2);
        $primaryCurrency = ClientCurrency::where('is_primary', 1)->where('client_id',$this->client_id)->first();
        $ClientPreference = ClientPreference::with('language', 'primarylang', 'domain', 'currency.currency', 'primary.currency')->select('client_code', 'theme_admin', 'distance_unit', 'date_format', 'time_format', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id', 'primary_color', 'secondary_color', 'reffered_by_amount', 'reffered_to_amount')
        ->where('client_id', $this->client_id)
        ->first();
        $preference = $ClientPreference ? $ClientPreference : new ClientPreference();
        $nomenclature_value = Nomenclature::first();
        // return $preference->currency;
        foreach ($preference->currency as $value) {
            $cli_currs[] = $value->currency_id;
        }
        // return $preference->language;
        foreach ($preference->language as $value) {
            $cli_langs[] = $value->language_id;
        }
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', $this->client_id)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')
                    ->get();
        return view('backend.setting.customize', compact('client','nomenclature_value','cli_langs','languages','currencies','preference','cli_currs','curtableData', 'webTemplates', 'appTemplates','primaryCurrency','social_media_details', 'client_languages'));
    }

    public function referandearnUpdate(Request $request, $code){
        $cp = new ClientPreference();
        $preference = ClientPreference::where('client_code', $this->client_id)->first();
        if($preference){
            $preference->reffered_to_amount = $request->reffered_to_amount;
            $preference->reffered_by_amount = $request->reffered_by_amount;
            $preference->save();
            return redirect()->route('configure.index')->with('success', 'Client configurations updated successfully!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientPreference  $clientPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code){


        $cp = new ClientPreference();
        $preference = ClientPreference::where('client_code', $this->client_id)->first();
        if(!$preference){
            $preference = new ClientPreference();
            $preference->client_code = $this->client_id;
        }
        $keyShouldNot = array('last_mile_team','unifonic_app_id','unifonic_account_email','unifonic_account_password','laundry_pickup_team', 'laundry_dropoff_team','laundry_service_key_url','laundry_service_key_code','laundry_service_key','laundry_submit_btn','need_dispacher_ride_submit_btn','need_dispacher_home_other_service_submit_btn','last_mile_submit_btn','dispacher_home_other_service_key_url','dispacher_home_other_service_key_code','dispacher_home_other_service_key','pickup_delivery_service_key_url','pickup_delivery_service_key_code','pickup_delivery_service_key','delivery_service_key_url','delivery_service_key_code','delivery_service_key','need_delivery_service','need_dispacher_home_other_service','need_dispacher_ride','Default_location_name', 'Default_latitude', 'Default_longitude', 'is_hyperlocal', '_token', 'social_login', 'send_to', 'languages', 'hyperlocals', 'currency_data', 'multiply_by', 'cuid', 'primary_language', 'primary_currency', 'currency_data', 'verify_config','custom_mods_config', 'distance_to_time_calc_config','delay_order','gifting','product_order_form','mtalkz_api_key','mtalkz_sender_id','mazinhost_api_key','mazinhost_sender_id','minimum_order_batch','edit_order_modes');

        foreach ($request->all() as $key => $value) {
            if(!in_array($key, $keyShouldNot)){
               $preference->{$key} = $value; 
            }
        }

        if($request->has('sms_provider'))
        {
            if($request->sms_provider == 1) //for twillio
            {
                $sms_credentials = [
                    'sms_from' => $request->sms_from,
                    'sms_key' => $request->sms_key,
                    'sms_secret' => $request->sms_secret,
                ];
            }elseif($request->sms_provider == 2) // for mTalkz
            {
                $sms_credentials = [
                    'api_key' => $request->mtalkz_api_key,
                    'sender_id' => $request->mtalkz_sender_id,
                ];
            }elseif($request->sms_provider == 3) // for mazinhost
            {
                $sms_credentials = [
                    'api_key' => $request->mazinhost_api_key,
                    'sender_id' => $request->mazinhost_sender_id,
                ];
            }elseif($request->sms_provider == 4) // for unifonic
            {
                $sms_credentials = [
                    'unifonic_app_id' => $request->unifonic_app_id,
                    'unifonic_account_email' => $request->unifonic_account_email,
                    'unifonic_account_password' => $request->unifonic_account_password,
                ];
            }
            $preference->sms_credentials = json_encode($sms_credentials);
        }

        /* Hyperlocal update */
        if($request->has('hyperlocals') && $request->hyperlocals == '1'){
            $preference->is_hyperlocal = ($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on') ? 1 : 0;


            if($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on'){
                if( (!$request->has('Default_location_name')) || ($request->Default_location_name == '')
                    || (!$request->has('Default_latitude')) || ($request->Default_latitude == '')
                    || (!$request->has('Default_longitude')) || ($request->Default_longitude == '')
                ){
                    return redirect()->route('configure.index')->with('error', 'Invalid Hyperlocal Data');
                }
                $preference->Default_location_name = $request->Default_location_name;
                $preference->Default_latitude = $request->Default_latitude;
                $preference->Default_longitude = $request->Default_longitude;
            }

        }

        // $preference->stripe_connect = ($request->has('stripe_connect') && $request->stripe_connect == 'on') ? 1 : 0;

        /* social login update */
        if($request->has('social_login') && $request->social_login == '1'){
            $preference->fb_login = ($request->has('fb_login') && $request->fb_login == 'on') ? 1 : 0;
            $preference->twitter_login = ($request->has('twitter_login') && $request->twitter_login == 'on') ? 1 : 0;
            $preference->google_login = ($request->has('google_login') && $request->google_login == 'on') ? 1 : 0;
            $preference->apple_login = ($request->has('apple_login') && $request->apple_login == 'on') ? 1 : 0;
        }
        if($request->has('verify_config') && $request->verify_config == '1'){
            $preference->verify_email = ($request->has('verify_email') && $request->verify_email == 'on') ? 1 : 0;
            $preference->verify_phone = ($request->has('verify_phone') && $request->verify_phone == 'on') ? 1 : 0;
            if((!$request->has('dinein_check') && !$request->dinein_check == 'on')
                && (!$request->has('takeaway_check') && !$request->dinein_check == 'on')
                && (!$request->has('delivery_check') && !$request->dinein_check == 'on')){
                    return redirect()->route('configure.index')->with('error', 'One Option must be acitve');
            }
            $preference->dinein_check = ($request->has('dinein_check') && $request->dinein_check == 'on') ? 1 : 0;
            $preference->takeaway_check = ($request->has('takeaway_check') && $request->takeaway_check == 'on') ? 1 : 0;
            $preference->delivery_check = ($request->has('delivery_check') && $request->delivery_check == 'on') ? 1 : 0;
        }
        if($request->has('custom_mods_config') && $request->custom_mods_config == '1'){
            $preference->enquire_mode = ($request->has('enquire_mode') && $request->enquire_mode == 'on') ? 1 : 0;
            $preference->pharmacy_check = ($request->has('pharmacy_check') && $request->pharmacy_check == 'on') ? 1 : 0;
            $preference->celebrity_check = ($request->has('celebrity_check') && $request->celebrity_check == 'on') ? 1 : 0;
            $preference->subscription_mode = ($request->has('subscription_mode') && $request->subscription_mode == 'on') ? 1 : 0;
            $preference->tip_before_order = ($request->has('tip_before_order') && $request->tip_before_order == 'on') ? 1 : 0;
            $preference->tip_after_order = ($request->has('tip_after_order') && $request->tip_after_order == 'on') ? 1 : 0;
            $preference->delay_order = ($request->has('delay_order') && $request->delay_order == 'on') ? 1 : 0;
            $preference->product_order_form = ($request->has('product_order_form') && $request->product_order_form == 'on') ? 1 : 0;
            $preference->off_scheduling_at_cart = ($request->has('off_scheduling_at_cart') && $request->off_scheduling_at_cart == 'on') ? 1 : 0;
            $preference->isolate_single_vendor_order = ($request->has('isolate_single_vendor_order') && $request->isolate_single_vendor_order == 'on') ? 1 : 0;
            $preference->gifting = ($request->has('gifting') && $request->gifting == 'on') ? 1 : 0;
            $preference->pickup_delivery_service_area = ($request->has('pickup_delivery_service_area') && $request->pickup_delivery_service_area == 'on') ? 1 : 0;
            $preference->minimum_order_batch = ($request->has('minimum_order_batch') && $request->minimum_order_batch == 'on') ? 1 : 0;
            $preference->static_delivey_fee = ($request->has('static_delivey_fee') && $request->static_delivey_fee == 'on') ? 1 : 0;
            $preference->header_quick_link = ($request->has('header_quick_link') && $request->header_quick_link == 'on') ? 1 : 0;
            $preference->get_estimations = ($request->has('get_estimations') && $request->get_estimations == 'on') ? 1 : 0;
            $preference->tools_mode = ($request->has('tools_mode') && $request->tools_mode == 'on') ? 1 : 0;
        }

        if($request->has('edit_order_modes') && $request->edit_order_modes == '1'){
         $preference->is_edit_order_admin = ($request->has('is_edit_order_admin') && $request->is_edit_order_admin == 'on') ? 1 : 0;
         $preference->is_edit_order_vendor = ($request->has('is_edit_order_vendor') && $request->is_edit_order_vendor == 'on') ? 1 : 0;
         $preference->is_edit_order_driver = ($request->has('is_edit_order_driver') && $request->is_edit_order_driver == 'on') ? 1 : 0;
        }
        if($request->has('distance_to_time_calc_config') && $request->distance_to_time_calc_config == '1'){
            $preference->distance_unit_for_time = (($request->has('distance_unit_for_time')) && ($request->distance_unit_for_time != '')) ? $request->distance_unit_for_time : 'kilometer';
            $preference->distance_to_time_multiplier = (($request->has('distance_to_time_multiplier')) && ($request->distance_to_time_multiplier != '')) ? $request->distance_to_time_multiplier : 2;
        }
        if($request->has('primary_language')){
            $deactivate_language = ClientLanguage::where('client_code',$this->client_id)->where('is_primary', 1)->first();
            if($deactivate_language){
                $deactivate_language->is_active = '0';
                $deactivate_language->is_primary = '0';
                $deactivate_language->save();
            }
            $primary_change = ClientLanguage::where('client_code', $this->client_id)
            ->where('language_id', $request->primary_language)
            ->update(['is_active' => 1, 'is_primary' => 1]);
            if(!$primary_change){
                ClientLanguage::insert([
                    'is_active'=> 1,
                    'is_primary'=> 1,
                    'client_code'=> $this->client_id,
                    'language_id'=> $request->primary_language,
                ]);
            }

            if(!empty($deactivate_language->language_id)){
                    PageTranslation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);

                    BrandTranslation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);

                    VariantTranslation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);

                    ProductTranslation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);

                    Category_translation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);

                    AddonOptionTranslation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);

                    VariantOptionTranslation::where('client_id', $this->client_id)
                    ->where('language_id', $deactivate_language->language_id)
                    ->update(['language_id' => $request->primary_language]);
            }
            $exist_language_id = array();
            if($request->has('languages')){
                foreach ($request->languages as $lan) {
                    if ($lan != $request->primary_language) {
                        $client_language = ClientLanguage::where('client_code', $this->client_id)
                        ->where('language_id', $lan)
                        ->first();
                        if (!$client_language) {
                            $client_language = new ClientLanguage();
                            $client_language->client_code = $this->client_id;
                        }
                        $client_language->is_primary = 0;
                        $client_language->language_id = $lan;
                        $client_language->is_active = 1;
                        $client_language->save();
                        $exist_language_id[] = $client_language->language_id;
                    }
                }
            }
            $deactivateLanguages = ClientLanguage::where('client_code',$this->client_id)->whereNotIn('language_id', $exist_language_id)->where('is_primary', 0)->update(['is_active' => 0]);
        }

        if($request->has('primary_currency')){
            $oldAdditional = ClientCurrency::where('currency_id', $request->primary_currency)
                        ->where('is_primary', 0)
                        ->where('client_id', $this->client_id)
                        ->delete();
            $c_data = ['is_primary' => 1,'client_code'=> $this->client_id,'client_id' => $this->client_id,'currency_id' => $request->primary_currency, 'doller_compare' => 1];

            $primaryCur = ClientCurrency::where('is_primary', 1)->where('client_id', $this->client_id);
            if($primaryCur->count() == 1){
                $primaryCur->update($c_data);
            }else{
               ClientCurrency::insert($c_data);
            }

             

        }
        if($request->has('primary_currency') && !$request->has('currency_data')){
            $delete = ClientCurrency::where('client_id',$this->client_id)->where('is_primary', 0)->delete();
        }
        if($request->has('currency_data') && $request->has('multiply_by')){
            $cur_multi = $exist_cid = array();
            foreach ($request->currency_data as $key => $value) {
                $exist_cid[] = $value;
                $curr = ClientCurrency::where('currency_id', $value)->where('client_code',$this->client_id)->first();
                $multiplier = $request->multiply_by[$value]??1;
                    if(!$curr){
                        $cur_multi[] = [
                            'currency_id'=> $value,
                            'client_code'=> $this->client_id,
                            'client_id'=> $this->client_id,
                            'is_primary'=> 0,
                            'doller_compare'=> $multiplier
                        ];
                    }else{
                        $curr->doller_compare =  $multiplier;
                        $curr->save();

                        // $res = ClientCurrency::where('currency_id', $value)->where('client_code',$this->client_id)
                        //             ->update(['doller_compare' => $multiplier]);
                                   // pr($res);
                    }
            }
            ClientCurrency::insert($cur_multi);
            $delete = ClientCurrency::where('client_code',$this->client_id)->where('is_primary', 0)
                            ->whereNotIn('currency_id',$exist_cid)->delete();
        }

        if($request->has('admin_email')){
            $preference->admin_email = $request->admin_email ;
        }

        $preference->save();


        $preferenceset = ClientPreference::where('client_code', $this->client_id)->first();
        if(isset($request->last_mile_submit_btn) && !empty($request->last_mile_submit_btn))
        {
            if(isset($request->need_delivery_service) && !empty($request->need_delivery_service)){
                try {
                    $client = new GClient(['headers' => ['personaltoken' => $request->delivery_service_key,'shortcode' => $request->delivery_service_key_code,'content-type' => 'application/json']]);
                    $url = $request->delivery_service_key_url;
                    $res = $client->post($url.'/api/check-dispatcher-keys');
                    $response = json_decode($res->getBody(), true);
                    if($response && $response['status'] == 400){
                        return redirect()->route('configure.index')->with('error', 'Last Mile Delivery Keys incorrect !');
                    }
                }catch(\Exception $e){
                    return redirect()->route('configure.index')->with('error', 'Invalid Last Mile Delivery Dispatcher URL !');
                }
                $preferenceset->need_delivery_service = ($request->has('need_delivery_service') && $request->need_delivery_service == 'on') ? 1 : 0;
                $preferenceset->delivery_service_key_url = $request->delivery_service_key_url;
                $preferenceset->delivery_service_key_code = $request->delivery_service_key_code;
                $preferenceset->delivery_service_key = $request->delivery_service_key;
                $preferenceset->last_mile_team = $request->last_mile_team;
            }else{
                $preferenceset->need_delivery_service =  ($request->has('need_delivery_service') && $request->need_delivery_service == 'on') ? 1 : 0;
            }
        }

        if(isset($request->laundry_submit_btn) && !empty($request->laundry_submit_btn))
        {
            if(isset($request->need_laundry_service) && !empty($request->need_laundry_service)){
                try {
                    $client = new GClient(['headers' => ['personaltoken' => $request->laundry_service_key,'shortcode' => $request->laundry_service_key_code,'content-type' => 'application/json']]);
                    $url = $request->laundry_service_key_url;
                    $res = $client->post($url.'/api/check-dispatcher-keys');
                    $response = json_decode($res->getBody(), true);
                    if($response && $response['status'] == 400){
                        return redirect()->route('configure.index')->with('error', 'laundry Keys incorrect !');
                    }
                }catch(\Exception $e){
                    return redirect()->route('configure.index')->with('error', 'Invalid laundry Dispatcher URL !');
                }
                $preferenceset->need_laundry_service = ($request->has('need_laundry_service') && $request->need_laundry_service == 'on') ? 1 : 0;
                $preferenceset->laundry_service_key_url = $request->laundry_service_key_url;
                $preferenceset->laundry_service_key_code = $request->laundry_service_key_code;
                $preferenceset->laundry_service_key = $request->laundry_service_key;
                $preferenceset->laundry_pickup_team = $request->laundry_pickup_team;
                $preferenceset->laundry_dropoff_team = $request->laundry_dropoff_team;
            }else{
                $preferenceset->need_laundry_service =  ($request->has('need_laundry_service') && $request->need_laundry_service == 'on') ? 1 : 0;
            }
        }


        if (isset($request->need_dispacher_ride_submit_btn) && !empty($request->need_dispacher_ride_submit_btn)) {
            if (isset($request->need_dispacher_ride) && !empty($request->need_dispacher_ride)) {
                try {
                    $client = new GClient(['headers' => ['personaltoken' => $request->pickup_delivery_service_key,'shortcode' => $request->pickup_delivery_service_key_code,'content-type' => 'application/json']]);
                    $url = $request->pickup_delivery_service_key_url;
                    $res = $client->post($url.'/api/check-dispatcher-keys');
                    $response = json_decode($res->getBody(), true);
                    if ($response && $response['status'] == 400) {
                        return redirect()->route('configure.index')->with('error', 'Pickup & Delivery Keys incorrect !');
                    }
                } catch (\Exception $e) {
                    return redirect()->route('configure.index')->with('error', 'Invalid Pickup & Delivery Dispatcher URL !');
                }
                $preferenceset->need_dispacher_ride = ($request->has('need_dispacher_ride') && $request->need_dispacher_ride == 'on') ? 1 : 0;
                $preferenceset->pickup_delivery_service_key_url = $request->pickup_delivery_service_key_url;
                $preferenceset->pickup_delivery_service_key_code = $request->pickup_delivery_service_key_code;
                $preferenceset->pickup_delivery_service_key = $request->pickup_delivery_service_key;
            } else {
                $preferenceset->need_dispacher_ride = ($request->has('need_dispacher_ride') && $request->need_dispacher_ride == 'on') ? 1 : 0;
            }
        }

        if(isset($request->need_dispacher_home_other_service_submit_btn) && !empty($request->need_dispacher_home_other_service_submit_btn))
        {

        if(isset($request->need_dispacher_home_other_service) && !empty($request->need_dispacher_home_other_service))
        {
            try {
                $client = new GClient(['headers' => ['personaltoken' => $request->dispacher_home_other_service_key,
                                                            'shortcode' => $request->dispacher_home_other_service_key_code,
                                                            'content-type' => 'application/json']
                                                                ]);
                $url = $request->dispacher_home_other_service_key_url;
                $res = $client->post($url.'/api/check-dispatcher-keys');
                $response = json_decode($res->getBody(), true);
                if($response && $response['status'] == 400){
                    return redirect()->route('configure.index')->with('error', 'On Demand Services Keys incorrect !');
                }
            }catch(\Exception $e){
                    return redirect()->route('configure.index')->with('error', 'Invalid On Demand Services Dispatcher URL !');
            }
            $preferenceset->need_dispacher_home_other_service = ($request->has('need_dispacher_home_other_service') && $request->need_dispacher_home_other_service == 'on') ? 1 : 0;
            $preferenceset->dispacher_home_other_service_key_url = $request->dispacher_home_other_service_key_url;
            $preferenceset->dispacher_home_other_service_key_code = $request->dispacher_home_other_service_key_code;
            $preferenceset->dispacher_home_other_service_key = $request->dispacher_home_other_service_key;
        }else{
            $preferenceset->need_dispacher_home_other_service = ($request->has('need_dispacher_home_other_service') && $request->need_dispacher_home_other_service == 'on') ? 1 : 0;
        }
        }

        $preferenceset->save();


        if($request->has('send_to') && $request->send_to == 'customize'){
            return redirect()->route('configure.customize')->with('success', 'Client customizations updated successfully!');
        }
        return redirect()->route('configure.index')->with('success', 'Client configurations updated successfully!');
    }




     # get last mile teams
     public function getLastMileTeams(){
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
                if ($dispatch_domain && $dispatch_domain != false) {

                    $unique = $this->client_id;

                    $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key,
                                                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                                                        'content-type' => 'application/json']
                                                            ]);
                            $url = $dispatch_domain->delivery_service_key_url;
                            $res = $client->get($url.'/api/get-all-teams');
                            $response = json_decode($res->getBody(), true);
                            if($response && $response['message'] == 'success'){
                                return $response['teams'];
                            }

                }
            }
            catch(\Exception $e){

            }
    }
    # get laundry teams
    public function getLaundryTeams(){
        try {
            $dispatch_domain = $this->checkIfLaundryOnCommon();
                if ($dispatch_domain && $dispatch_domain != false) {

                    $unique = $this->client_id;

                    $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->laundry_service_key,
                                                        'shortcode' => $dispatch_domain->laundry_service_key_code,
                                                        'content-type' => 'application/json']
                                                            ]);
                            $url = $dispatch_domain->laundry_service_key_url;
                            $res = $client->get($url.'/api/get-all-teams');
                            $response = json_decode($res->getBody(), true);
                            if($response && $response['message'] == 'success'){
                                return $response['teams'];
                            }

                }
            }
            catch(\Exception $e){

            }
    }
    # check if last mile delivery on
    public function checkIfLastMileOn(){
        $preference = ClientPreference::where('client_id',$this->client_id)->first();
        if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    public function postUpdateDomain(Request $request, $id){
        $rules = array('custom_domain' => 'required|max:150');
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
         $client = Client::where('id', $this->client_id)->first();
         $client->custom_domain = $request->custom_domain;
         $client->save();
        $id = $this->client_id;
          # if submit custom domain by client
               $exists = Client::where('custom_domain', $request->custom_domain)
               ->where('id','!=',$this->client_id)
               ->count();
               if ($exists) {
                   return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
               } else {
                   
                       Client::where('id',$this->client_id)->update(['custom_domain' => $request->custom_domain]);
                    
               }
               return redirect()->route('configure.customize')->with('success', 'Client customize data updated successfully!');
            


        // }else{
        //     return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is already pointed. Please select a different domain']));
        // }





    }
}
