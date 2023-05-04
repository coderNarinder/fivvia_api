<?php

namespace App\Http\Controllers\ApiRoutes\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Models\Business\ClientBusinessType;
use App\Models\BusinessCategory;
class BaseController extends Controller
{
    public $domain ='';
    public $subDomain = '';
    public $client_head = []; 
    public $client_preferencs = []; 
    public $logo = []; 
    public $client_business_type = '';
    public $businessID = '';
    public $languages = [];
    public $WEB_LANG = 0;
    public $WEB_CURRENCY = 0;





#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

   public function __construct(Request $request)
   {
       $headers = $request->header();
       $domain = $headers['origin']; 
       $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
       $domain = str_replace(array('https://', '.test.com/login'), '', $domain);
       $this->domain = $domain[0]; 
   }
 
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

   public function getclient($id)
   {
       return Client::with('ClientPreferencs.tamplate','SocialMedias')
                   ->where('id', $id)
                   ->first();
   }


#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

   public function preference($client_id)
   {
       return \App\Models\ClientPreference::with('language','language.language','currency','currency.currency','primarylang','primarylang.language','primary','primary.currency')
                   ->where('client_id',$client_id)->first();
   }




#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

   public function businessCategories()
   {
       return BusinessCategory::where('parent',0)->where('status',1)
       ->orderBy('sorting','ASC')->get();
   }


  
}
