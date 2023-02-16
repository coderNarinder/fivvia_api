<?php
namespace App\Providers\Custom;
use Illuminate\Support\Facades\Redis; 
use App\Models\PaymentOption;
use App\Providers\Custom\ClientBasicTrait;
use DB;
use Auth;
use URL;
use Route;
use Config,Schema;

use Cache;
use Closure;
use Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use App\Models\{Client, ClientPreference, Language, ClientLanguage, Currency, ClientCurrency, Product,Country};
/**
 * 
 */
class ClientCacheClass
{
 
  use ClientBasicTrait;
	public $_domain = '';
	public $_subDomain = '';
	public $social_media_details = '';
	public $client_head = [];
	public $client_business_type ='';
	public $SocialMedias =[];
	public $client_payment_options =[];
	public $ClientPreferencs =[];
	public $favicon_url ='';
	public $client_id = 0;
	public $stripe_publishable_key = '';
  public $yoco_public_key = '';
  public $checkout_public_key = '';
  public $template_path = [];
  public $primeLang = [];
  public $primeCurcy = [];
  public $template_id = 0;

	
	function __construct()
	{
  	   $domain = url('/');
       $this->client_business_type = $business_type = env('BUSINESS_TYPE','tours');
       Redis::set($domain.'_CLIENT_APP_TYPE',$business_type, 'EX', 3600000000000);
       $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
       $this->_domain = str_replace(array('https://', '.test.com/login'), '', $domain);
       $subDomain = explode('.',$this->_domain);
       $this->_subDomain= $subDomain[0];
       $this->social_media_details = '';
       $this->client_head = $client_head = $this->createClientDataCache();
       if(!empty($this->client_head)){
           $this->createCommonCache();
       }
  }


  public function createCommonCache()
  {

       $domain = url('/');
       $business_type = env('BUSINESS_TYPE','tours');
       Redis::set($domain.'_CLIENT_APP_TYPE',$business_type, 'EX', 3600000000000);
       $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
       $this->_domain = str_replace(array('https://', '.test.com/login'), '', $domain);
       $subDomain = explode('.',$this->_domain);
       $this->_subDomain= $subDomain[0];
       $this->social_media_details = '';
       $this->client_head = $client_head = $this->createClientDataCache();

       
       $domain = $this->_domain; 
       $subDomain = $this->_subDomain;

       # store Client id in cache --------------------------------------------------------------------------------------------------

       $clientIdName = $domain.'_ID';

       $this->client_id = $client_id = \Cache::remember($clientIdName,600000000,function() use($client_head){
             if(!empty($client_head)){
                   return $client_head->id;
             }
       });

       // Redis::set($domain.'_ID', $client_head->id, 'EX', 3600000000000);



       # store Client id in cache --------------------------------------------------------------------------------------------------

       $primeLang = $domain.'primeLang';
       $this->primeLang = \Cache::remember($primeLang,600000000,function() use($client_id){
                   return  ClientLanguage::select('language_id', 'is_primary')
                               ->where('client_code',$client_id)
                               ->where('is_primary', 1)
                               ->first();
       });

       # Social media caches----------------------------------------------------------------------------------------------------------

       $_SocialMedias = $this->_domain.'_SocialMedias';
       $this->_SocialMedias = \Cache::remember($_SocialMedias,600000000,function() use($client_id){
             return $SocialMedias = \App\Models\SocialMedia::where('client_id',$client_id)->get();
       });


       # _CLIENTPREFERENCS----------------------------------------------------------------------------------------------------------
       $client_preferences = $domain.'_CLIENTPREFERENCS'; 
       $this->ClientPreferencs = \Cache::remember($client_preferences,600000000,function() use($client_id){
             return \App\Models\ClientPreference::where('client_id',$this->client_id)->first();
       });

       

       # Payment Options --------------------------------------------------------------------------------------------------------------

       $payment_option_cache_name = $this->_domain.'_getPaymentOptions';
       $this->getPaymentOptions = $payment_options = \Cache::remember($payment_option_cache_name,600000000,function() use($client_id){
                   $payment_codes = ['yoco', 'checkout'];
                   $stripe_publishable_key = $yoco_public_key = $checkout_public_key = '';
                   $data = PaymentOption::select('code','credentials')
                                        ->where('client_id',$client_id)
                                        ->whereIn('code', $payment_codes)
                                        ->where('status', 1);

                   if($data->count() > 0){
                     return $data->get();
                   }else{
                     $payment_options = PaymentOption::where('client_id',0)->get();
                     foreach($payment_options as $column => $p){
                           $payment_opts = PaymentOption::where('client_id',$client_id)->where('code',$p->code);
                           $PaymentOption = $payment_opts->count() == 1 ? $payment_opts->first() : new PaymentOption;
                           $PaymentOption->code = $p->code;
                           $PaymentOption->path = $p->path;
                           $PaymentOption->title = $p->title;
                           $PaymentOption->credentials = $p->credentials;
                           $PaymentOption->status = $p->status;
                           $PaymentOption->off_site = $p->off_site;
                           $PaymentOption->test_mode = $p->test_mode;
                           $PaymentOption->client_token_id = $client_id;
                           $PaymentOption->client_id = $client_id;
                           $PaymentOption->save();
                      }
                      return PaymentOption::select('code','credentials')
                                          ->where('client_id',$client_id)
                                          ->whereIn('code', $payment_codes)
                                          ->where('status', 1)
                                          ->get();
                   }
                   
       });

      # CREATE KEYS OF PAYMENTS-------------------------------------------------------------------------------------------------------------------------

        if($this->getPaymentOptions){
            foreach($payment_options as $option){
                $creds = json_decode($option->credentials);
                if($option->code == 'stripe'){
                    $this->stripe_publishable_key = (isset($creds->publishable_key) && (!empty($creds->publishable_key))) ? $creds->publishable_key : '';
                }
                if($option->code == 'yoco'){
                    $this->yoco_public_key = (isset($creds->public_key) && (!empty($creds->public_key))) ? $creds->public_key : '';
                }
                if($option->code == 'checkout'){
                    $this->checkout_public_key = (isset($creds->public_key) && (!empty($creds->public_key))) ? $creds->public_key : '';
                }
            }
        }

        $count = 0;
        $client_preference_detail = $this->ClientPreferencs;
        if($client_preference_detail){
            if($client_preference_detail->dinein_check == 1){$count++;}
            if($client_preference_detail->takeaway_check == 1){$count++;}
            if($client_preference_detail->delivery_check == 1){$count++;}
        }

        # CLIENT PAYMENT OPTIONS -----------------------------------------------------------------------------------------------------------------------------------

        $client_payment_options = $this->_domain.'client_payment_options';

        $this->client_payment_options = \Cache::remember($client_payment_options,600000000,function() use($client_id){
             return PaymentOption::where('status', 1)->where('client_id',$client_id)->pluck('code')->toArray();
        });

        # CLIENT PAYMENT OPTIONS -----------------------------------------------------------------------------------------------------------------------------------

        $primeCurcy = $this->_domain.'primeCurcy';

        $this->primeCurcy = \Cache::remember($primeCurcy,600000000,function() use($client_id){
             return ClientCurrency::join('currencies as cu', 'cu.id', 'client_currencies.currency_id')
                                      ->where('client_id',$client_id)
                                      ->where('client_currencies.is_primary', 1)
                                      ->first();
        });

        # TEMPLATE LAYOUT CACHE -----------------------------------------------------------------------------------------------------------------------------------0

       $template_iD = $this->_domain.'_LAYOUT_TEMPLATE_ID';
       $this->template_id = $template_id = \Cache::remember($template_iD,600000000,function() use($client_preference_detail){
           return $client_preference_detail->web_template_id;
       });

       $template_path = $this->_domain.'_LAYOUT_TEMPLATE'; 
          \Cache::forget($template_path);
       $this->template_path = \Cache::remember($template_path,600000000,function() use($template_id){
             $template = \App\Models\Template::where('id',$template_id)->firstOrFail();
              $layout = 'templates.'.$this->client_business_type.'.layouts.'.$template->temp_id.'.layout';
              $includes = 'templates.'.$this->client_business_type.'.includes.'.$template->temp_id.'.';
              $modules = 'templates.'.$this->client_business_type.'.modules.'.$template->temp_id.'.';
              $root = 'templates.'.$this->client_business_type.'.';
              $file_path = 'template-css/tours/'.$this->client_business_type.'/'.$template->temp_id.'/';
              return $Template_array = [
                 'root' => $root,
                 'layout' => $layout,
                 'includes' => $includes,
                 'modules' => $modules,
                 'file_path' => $file_path,
                 'temp_id' => $template->temp_id
              ];
       });
           
            
        
       
  }

  #----------------------------------------------------------------------------------------
  # create client cache
  #----------------------------------------------------------------------------------------

  public function cacheBasicCache()
  {
     if(!empty($this->client_head)){
           $redisData = $this->client_head;
           $callback = url('/auth/facebook/callback');
           Config::set("client_id", $redisData->id);
           Config::set("client_connected", true);
           Config::set("client_data", $redisData);

           $clientPreference = $this->ClientPreferencs;
          if($clientPreference){
            Config::set('FACEBOOK_CLIENT_ID', $clientPreference->fb_client_id);
            Config::set('FACEBOOK_CLIENT_SECRET', $clientPreference->fb_client_secret);
            Config::set('FACEBOOK_CALLBACK_URL', $callback);
          }

            $primeLang = $this->primeLang;
            if (!Session::has('customerLanguage') || empty(Session::get('customerLanguage'))){
                if($primeLang){
                  Session::put('customerLanguage', $primeLang->language_id);
                }
            }

           if(empty($primeLang)){
               Session::put('customerLanguage', 1);
           }

           if (!Session::has('applocale') || empty(Session::get('applocale'))){
                $lang_detail = Language::where('id', Session::get('customerLanguage'))->first();
                App::setLocale($lang_detail->sort_code);
                Session::put('applocale', $lang_detail->sort_code);
           }


         // Set Currency-----------------------------------------------------------------------------------------------

          $primeCurcy = $this->primeCurcy;
          if (!Session::has('customerCurrency') || empty(Session::get('customerCurrency'))){
              if($primeCurcy){
                Session::put('customerCurrency', $primeCurcy->currency_id);
                Session::put('currencySymbol', $primeCurcy->symbol);
                Session::put('currencyMultiplier', $primeCurcy->doller_compare);
              }
          }
          if (!Session::has('customerCurrency') || empty(Session::get('customerCurrency')) && empty($primeCurcy)){
              $primeCurcy = Currency::where('id', 147)->first();
              Session::put('customerCurrency', 147);
              Session::put('currencySymbol', $primeCurcy->symbol);
              Session::put('currencyMultiplier', 1);
          }

          if (!Session::has('iso_code') || empty(Session::get('iso_code'))){
              $currency_detail = Currency::where('id', Session::get('customerCurrency')) 
              ->first();
              Session::put('iso_code', $currency_detail->iso_code);
          }

         // Set Currency-----------------------------------------------------------------------------------------------

          $preferData = array();
          if(!Session::has('default_country_code') || empty(Session::get('default_country_code'))){
              $getAdminCurrentCountry = Country::where('id', '=', $redisData->country_id)->get()->first();
              if(!empty($getAdminCurrentCountry)){
                  $countryCode = $getAdminCurrentCountry->code;
                  $phoneCode = $getAdminCurrentCountry->phonecode;
              }else{
                  $countryCode = '';
                  $phoneCode = '';
              }

              Session::put('default_country_code', $countryCode);
              Session::put('default_country_phonecode', $phoneCode);
              Session::put('preferences', $this->ClientPreferencs);
          }
     } 
  }

	#----------------------------------------------------------------------------------------
	# create client cache
	#----------------------------------------------------------------------------------------

	public function clearCaches($type="all")
	{
		$domain = $this->_domain;
		$client_id = $domain.'_ID';
		$social = $domain.'_SocialMedias';
		$_SocialMedias = $domain.'_SocialMedias';
    $template_path = $this->_domain.'_LAYOUT_TEMPLATE'; 
    $template_iD = $this->_domain.'_LAYOUT_TEMPLATE_ID'; 
    $primeLang = $domain.'primeLang';
    $client_preferences = $domain.'_CLIENTPREFERENCS'; 
    $payment_option_cache_name = $this->_domain.'_getPaymentOptions';
    $client_payment_options = $this->_domain.'client_payment_options';
    $primeCurcy = $this->_domain.'primeCurcy';


	    switch ($type) {
	    	case 'all':
             \Cache::forget($domain);
             \Cache::forget($client_id);
             \Cache::forget($social);
             \Cache::forget($_SocialMedias);
             \Cache::forget($template_path);
             \Cache::forget($template_iD);
             \Cache::forget($primeLang);
             \Cache::forget($client_preferences);
             \Cache::forget($payment_option_cache_name);
             \Cache::forget($client_payment_options);
             \Cache::forget($primeCurcy );
	    		break;
	    	
	    	default:
	    		# code...
	    		break;
	    }
	}


  public function themeMode($mode='light',$change=0)
  {
    $domain = $this->_domain.'_them_mode';

    if($change == 1){
        \Cache::forget($domain);
         return \Cache::remember($domain,300000,function() use($mode){
           return $mode;
       });
    } 

       return \Cache::remember($domain,300000,function() use($mode){
           return $mode;
       });
  }

  #----------------------------------------------------------------------------------------
  # create client cache
  #----------------------------------------------------------------------------------------

  public function checkIfLastMileDeliveryOn()
    {
         $preference = $this->ClientPreferencs;
        if (!empty($preference) && Schema::hasColumn('client_preferences', 'need_delivery_service') && Schema::hasColumn('client_preferences', 'delivery_service_key_url')  && Schema::hasColumn('client_preferences', 'delivery_service_key_code')  ) {
            if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return true;
            else
            return false;
        }
        return false;
       
    }

 


#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

public function createClientDataCache()
{ 
       $domain = $this->_domain;
       $client_head = Redis::get($domain);
       $subDomain = $this->_subDomain;
      return \Cache::remember($domain,6000,function() use($domain,$subDomain){
            return Client::with('ClientPreferencs','ClientPreferencs.tamplate','SocialMedias')
                   ->where(function ($q) use ($domain, $subDomain) {
                       $q->where('custom_domain', $domain)
                         ->orWhere('sub_domain', $subDomain);
                   })
                   ->first();
       });
}


#-------------------------------------------------------------------------------------------------------------------------------------------------------------
# GET CATEGORY NAVBARS
#-------------------------------------------------------------------------------------------------------------------------------------------------------------

public function categoryNav()
{
        $client_id = $this->client_id;
        $this->ClientLanguage();

        $type = \Session::get('vendorType');
        $lang_id = \Session::get('customerLanguage');
        $currency_id = \Session::get('customerCurrency');
        $category_type =env('BUSINESS_TYPE','tours');
        $preferences = $this->ClientPreferencs;

        $domain = $this->_domain.'_categoryNavbar';
         \Cache::forget($domain);
       $categories = \Cache::remember($domain,600000000,function() use($client_id,$category_type,$lang_id,$currency_id,$preferences){
             $primary = \App\Models\ClientLanguage::where('client_code',$client_id)
             ->orderBy('is_primary','desc')->first();
             $lang_id = session()->get('customerLanguage');

               $categories = \App\Models\Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                                           ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name','categories.category_type')
                                           ->where('categories.category_type',$category_type)
                                           ->distinct('categories.id');
                  $status = 2;
                  if ($preferences) {
                      if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                           $vendors =  getServiceAreaVendors();
                          $categories = $categories->leftJoin('vendor_categories as vct', 'categories.id', 'vct.category_id')
                              ->where(function ($q1) use ($vendors, $status, $lang_id) {
                                  $q1->whereIn('vct.vendor_id', $vendors)
                                      ->where('vct.status', 1)
                                      ->orWhere(function ($q2) {
                                          $q2->whereIn('categories.type_id', [4,5,8]);
                                      });
                              });
                      }
                  }
             return $categories = $categories->where('categories.id', '>', '1')
                      ->whereNotNull('categories.type_id')
                      ->whereNotIn('categories.type_id', [7])
                      ->where('categories.is_visible', 1)
                      ->where('categories.status', '!=', $status)
                      ->where('cts.language_id', $lang_id)
                      ->where(function ($qrt) use($lang_id,$primary){
                          $qrt->where('cts.language_id', $lang_id);
                         // ->orWhere('cts.language_id',$primary->language_id);
                       })
                      ->whereNull('categories.vendor_id')
                      ->groupBy('categories.id')
                      //->orderBy('categories.position', 'asc')
                      //->orderBy('categories.parent_id', 'asc')
                      ->get();
 


       });


 // return $categories;

        if ($categories) {
            return $categories = $this->buildTree($categories->toArray());
        }else{

        return $categories;
        }
        // return \App\Models\Category::whereIn('id',$cate);
  }


#-------------------------------------------------------------------------------------------------------------------------------------------------------------
# GET CATEGORY NAVBARS
#-------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function buildTree($elements, $parentId = 1)
  {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children =  $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
  }



}
?>