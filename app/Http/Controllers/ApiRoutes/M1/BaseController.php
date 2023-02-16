<?php

namespace App\Http\Controllers\ApiRoutes\M1;

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
class BaseController extends Controller
{
    public $domain ='';
    public $subDomain = '';
    public $client_head = []; 
    public $client_preferencs = []; 
    public $logo = []; 
    public $client_business_type = '';





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
       $this->createClientDataCache();


   }
 
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

public function getCommonData()
{ 
       
       $data = [];
       $data['client_head'] = $this->client_head;
       $data['client_preferencs'] = $this->client_preferencs;
       $data['logo'] = $this->logo;

       return $data;
}


#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  create client data
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

public function createClientDataCache()
{ 
       $domain = $this->domain;
       $this->client_head = \Cache::remember($domain.'_API',36000,function() use($domain){
            return Client::with('ClientPreferencs.tamplate','SocialMedias')
                   ->where(function ($q) use ($domain) {
                       $q->where('custom_domain', $domain);
                   })
                   ->first();
       });
       $this->client_business_type = $this->client_head->business_category_type;
       $this->client_id = $this->client_head->id;
           
          
       # GETTING DATA CLIENT PREFERENCES
       $client_id = $this->client_id;
      
       $client_preferences = $domain.'_API_CLIENTPREFERENCS'; 
       $this->client_preferencs = \Cache::remember($client_preferences,36000,function() use($client_id){
             return \App\Models\ClientPreference::where('client_id',$client_id)->first();
       }); 

           //  $data = Redis::get($client_preferences);
           // if(empty($data)){
           //       $data = \App\Models\ClientPreference::where('client_id',$client_id)->first();
           //        Redis::set($domain.'_ID', $client_head->id, 'EX', 3600000000); 
           // }else{
           //       $client_head = Client::with('ClientPreferencs','ClientPreferencs.tamplate')
           //       ->where(function ($q) use ($domain, $subDomain) {
           //          $q->where('custom_domain', $domain)->orWhere('sub_domain', $subDomain[0]);
           //       })
           //       ->firstOrFail();
           // }
}  


public function removeCaches($value='')
{
   $domain = $this->domain;
   

   switch ($value) {
     case 'settings':
         \Cache::forget($domain.'_API_CLIENTPREFERENCS');
       break;
     
     default:
       # code...
       break;
   }
} 



 
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  UPLOAD FILE FUNCTION
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

public function uploadImage(Request $request)
{ 
	 if ($request->hasFile('file')) {
	     $url = uploadFileWithAjax23($request->folder_path,$request->file('file'));
	     $response = [
	       'status' => 0,
	       'message' => 'upload successfully',
	        'data' => [
	           'link' => url($url)
		   ]
	     ];
     }else{
        $response = [
	       'status' => 0,
	       'message' => 'Please choose file',
	       
	     ];
     }
     return response()->json($response);
}




}
