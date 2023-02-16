<?php

namespace App\Providers;

use DB;
use Auth;
use URL;
use Route;
use Config,Schema;
use App\Models\Page;
use App\Models\Client;
use App\Models\Admin;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Models\{ClientPreference, PaymentOption};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder; 
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client as HttpClient;
use App\Providers\Custom\ClientCacheClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request){

       
       $user = \App\Models\User::find(1);
  

        if ($request->ajax() || $request->wantsJson()) {
            
        }else{


        //Redis::flushDB();
        if(config('app.env') != 'local') {
            \URL::forceScheme('https');
        }
        Builder::defaultStringLength(191);
        Paginator::useBootstrap();
        $domain = url('/');
        $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
      
// dd(1);
        $currentURL = url()->current();
        $current_domain_url = getDomainName();
    
       if(config('app.backend') != $current_domain_url && config('app.business') != $current_domain_url && config('app.API_DOMAIN') != $current_domain_url){
         $this->checkClientDomain($request);
       }
     }
    }


  

  public function checkClientDomain($request)
  {
           // dd(getDomainName());
      
        //Redis::flushDB();
        // $domain = url('/');
        // $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
        // $subDomain = explode('.', $domain);
        // $social_media_details = '';
        //    $client_head = Redis::get($domain);
        //    if(empty($client_head)){
        //          $client_head = Client::with('ClientPreferencs','ClientPreferencs.tamplate')->where(function ($q) use ($domain, $subDomain) {
        //            $q->where('custom_domain', $domain)->orWhere('sub_domain', $subDomain[0]);
        //          })->firstOrFail();
        //           Redis::set($domain.'_ID', $client_head->id, 'EX', 3600000000);
        //           Redis::set($domain, json_encode($client_head), 'EX', 36000);
        //    }else{
        //          $client_head = Client::with('ClientPreferencs','ClientPreferencs.tamplate')
        //          ->where(function ($q) use ($domain, $subDomain) {
        //             $q->where('custom_domain', $domain)->orWhere('sub_domain', $subDomain[0]);
        //          })
        //          ->firstOrFail();
        //    }

             
 

          // Redis::flushDB();
            
     
          // if(!empty($client_head->ClientPreferencs)){
          //   $client_preference_detail = $client_head->ClientPreferencs;
          //   $favicon_url = $client_preference_detail->favicon['image_path'];
          //   $template_id = Redis::get($domain.'_LAYOUT_TEMPLATE_ID');
          //   $template_path = Redis::get($domain.'_LAYOUT_TEMPLATE');
            
          //   $ClientPreferencs = Redis::get($domain.'_CLIENTPREFERENCS');
          //   if(empty($ClientPreferencs)){
          //      Redis::set($domain.'_CLIENTPREFERENCS', json_encode($client_preference_detail), 'EX', 3600000000);
          //   }
          //   if(empty($template_path)){
          //       $template = \App\Models\Template::where('id',$client_preference_detail->web_template_id)
          //       ->firstOrFail();
          //       $layout = 'templates.layouts.'.$template->temp_id.'.layout';
          //       $includes = 'templates.includes.'.$template->temp_id.'.';
          //       $modules = 'templates.modules.'.$template->temp_id.'.';
          //       $Template_array = [
          //          'layout' => $layout,
          //          'includes' => $includes,
          //          'modules' => $modules,
          //          'temp_id' => $template->temp_id
          //       ];
          //       Redis::set($domain.'_LAYOUT_TEMPLATE', json_encode($Template_array), 'EX', 3600000000);
                
          //   }
             
             


          // }else{
          //     $client_preference_detail = new ClientPreference;
          //     $client_preference_detail->business_type = 'food_grocery_ecommerce';
          //     $client_preference_detail->is_hyperlocal = 0;
          //     $client_preference_detail->client_id = $client_head->id;
          //     $client_preference_detail->client_code = $client_head->id;
          //     $client_preference_detail->mail_driver = env('MAIL_DRIVER');
          //     $client_preference_detail->mail_host = env('MAIL_HOST');
          //     $client_preference_detail->mail_port = env('MAIL_PORT');
          //     $client_preference_detail->mail_from = env('MAIL_FROM_ADDRESS');
          //     $client_preference_detail->mail_from = env('MAIL_FROM_NAME');
          //     $client_preference_detail->mail_encryption = env('MAIL_ENCRYPTION');
          //     $client_preference_detail->mail_username = env('MAIL_USERNAME');
          //     $client_preference_detail->mail_password = env('MAIL_PASSWORD');
          //     $client_preference_detail->save();

          //      Redis::set($domain.'_CLIENTPREFERENCS', json_encode($client_preference_detail), 'EX', 3600000000);

          // }



 
        // $payment_codes = ['yoco', 'checkout'];
        // $stripe_publishable_key = $yoco_public_key = $checkout_public_key = '';

        // if(!empty($client_head->getPaymentOptions) && $client_head->getPaymentOptions->count() > 0){
        //     $payment_options = $client_head->getPaymentOptions;

        
        // }else{
        //     $payment_options = PaymentOption::where('client_id',0)->get();
        //     foreach($payment_options as $column => $p){
        //          $payment_opts = PaymentOption::where('client_id',$client_head->id)->where('code',$p->code);
        //          $PaymentOption = $payment_opts->count() == 1 ? $payment_opts->first() : new PaymentOption;
        //          $PaymentOption->code = $p->code;
        //          $PaymentOption->path = $p->path;
        //          $PaymentOption->title = $p->title;
        //          $PaymentOption->credentials = $p->credentials;
        //          $PaymentOption->status = $p->status;
        //          $PaymentOption->off_site = $p->off_site;
        //          $PaymentOption->test_mode = $p->test_mode;
        //          $PaymentOption->client_token_id = $client_head->code;
        //          $PaymentOption->client_id = $client_head->id;
        //          $PaymentOption->save();
        //     }
        //     $payment_options = PaymentOption::select('code','credentials')
        //                                 ->where('client_id',$client_head->id)
        //                                 ->whereIn('code', $payment_codes)
        //                                 ->where('status', 1)
        //                                 ->get();
        // }
        //                                 //dd($payment_options);
        
        // if($payment_options){
        //     foreach($payment_options as $option){
        //         $creds = json_decode($option->credentials);
        //         if($option->code == 'stripe'){
        //             $stripe_publishable_key = (isset($creds->publishable_key) && (!empty($creds->publishable_key))) ? $creds->publishable_key : '';
        //         }
        //         if($option->code == 'yoco'){
        //             $yoco_public_key = (isset($creds->public_key) && (!empty($creds->public_key))) ? $creds->public_key : '';
        //         }
        //         if($option->code == 'checkout'){
        //             $checkout_public_key = (isset($creds->public_key) && (!empty($creds->public_key))) ? $creds->public_key : '';
        //         }
        //     }
        // }

         $count = 0;
         $obj = new ClientCacheClass();
         //  $obj->clearCaches('all');
         // dd($obj->template_path);
         $client_head = $obj->client_head;
         if(empty($client_head)){
             $obj->clearCaches();
             $obj = new ClientCacheClass();
         }

         if($client_head->business_category_type != 'tours-&-activities'){
            // abort(404);
         }
       

         

         $social_media_details = !empty($obj->_SocialMedias) ? $obj->_SocialMedias : [];
         $favicon_url = asset('assets/images/favicon.png');
         $client_preference_detail = $obj->ClientPreferencs;
         if($client_preference_detail){
            if($client_preference_detail->dinein_check == 1){$count++;}
            if($client_preference_detail->takeaway_check == 1){$count++;}
            if($client_preference_detail->delivery_check == 1){$count++;}
         }
       // dd($obj->getPages());

        $last_mile_common_set = $obj->checkIfLastMileDeliveryOn();
        $client_payment_options = $obj->client_payment_options; 

        // if(!empty($client_head)){
        //   $client_head->logo = (array)$client_head->logo;
        // }

         
         $navCategories = $obj->categoryNav();
       // dd($navCategories);
        view()->share('themeMode', $obj->themeMode());
        view()->share('last_mile_common_set', $last_mile_common_set);
        view()->share('favicon', $favicon_url);
        view()->share('client_head', $client_head);
        view()->share('pages', $obj->getPages());
        view()->share('mod_count', $count);
        view()->share('navCategories', $navCategories);
        view()->share('social_media_details', $social_media_details);
        view()->share('stripe_publishable_key', $obj->stripe_publishable_key);
        view()->share('yoco_public_key', $obj->yoco_public_key);
        view()->share('checkout_public_key', $obj->checkout_public_key);
        view()->share('client_preference_detail', $client_preference_detail);
        view()->share('client_payment_options', $obj->client_payment_options);
 
  }










    public function connectDynamicDb($request)
    {
           // Redis::flushDB();
        if (strpos(URL::current(), '/api/') !== false) {
        } else {
            $domain = $request->getHost();
            $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
            $subDomain = explode('.', $domain);
            $existRedis = Redis::get($domain);
            if ($domain != env('Main_Domain')) {
          // return $client = \App\Models\Client::first();
                
          //return env('Main_Domain').DB::connection()->getDatabaseName();

             

                if (!$existRedis) {
                    $client = Client::select('name', 'email', 'phone_number', 'is_deleted', 'is_blocked', 'logo', 'company_name', 'company_address', 'status', 'code', 'database_name', 'database_host', 'database_port', 'database_username', 'database_password', 'custom_domain', 'sub_domain')
                        ->where(function ($q) use ($domain, $subDomain) {
                                  $q->where('custom_domain', $domain)
                                ->orWhere('sub_domain', $subDomain[0]);
                        })
                        ->first();


                    if ($client) {
                        Redis::set($domain, json_encode($client->toArray()), 'EX', 36000);
                        $existRedis = Redis::get($domain);
                    }
                }

                $callback = '';
                $dbname = DB::connection()->getDatabaseName();
                $redisData = json_decode($existRedis);
               
                if ($redisData) {
                      
                    if ($domain != env('Main_Domain')) {
                        if ($redisData && $dbname != 'db_'.$redisData->database_name) {
                            $database_name = 'db_'.$redisData->database_name;
                            $database_host = !empty($redisData->database_host) ? $redisData->database_host : env('DB_HOST', 'dev.mysql.fivvia.com');
                            $database_port = !empty($redisData->database_port) ? $redisData->database_port : env('DB_PORT', '3306');
                            $database_username = !empty($redisData->database_username) ? $redisData->database_username : env('DB_USERNAME', 'ecomm_root');
                            $database_password = !empty($redisData->database_password) ? $redisData->database_password : env('DB_PASSWORD', 'Ecomm!12zyed!!21!!');
                            $default = [
                                'driver' => env('DB_CONNECTION', 'mysql'),
                                'host' => $database_host,
                                'port' => $database_port,
                                'database' => $database_name,
                                'username' => $database_username,
                                'password' => $database_password,
                                'charset' => 'utf8mb4',
                                'collation' => 'utf8mb4_unicode_ci',
                                'prefix' => '',
                                'prefix_indexes' => true,
                                'strict' => false,
                                'engine' => null
                            ];
                            Config::set("database.connections.$database_name", $default);
                            Config::set("client_id", 1);
                            Config::set("client_connected", true);
                            DB::setDefaultConnection($database_name);
                            DB::purge($database_name);
                            $dbname = DB::connection()->getDatabaseName();
                            
                        }
                    }
                }

               
            }   
        }
    }

    public function checkIfLastMileDeliveryOn($client_head)
    {
        $preference = ClientPreference::where(['client_id' => $client_head->id])->first();
        if (isset($preference) && Schema::hasColumn('client_preferences', 'need_delivery_service') && Schema::hasColumn('client_preferences', 'delivery_service_key_url')  && Schema::hasColumn('client_preferences', 'delivery_service_key_code')  ) {
            if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
            else
            return false;
        }
        return false;
       
    }
}
