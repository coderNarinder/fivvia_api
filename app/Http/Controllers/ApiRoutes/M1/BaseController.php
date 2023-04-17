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
       // $this->removeCaches('settings');
       $this->client_head = \Cache::remember($domain.'_API',36000,function() use($domain){
            return Client::with('ClientPreferencs.tamplate','SocialMedias')
                   ->where(function ($q) use ($domain) {
                       $q->where('custom_domain', $domain);
                   })
                   ->first();
       });
       if(!empty($this->client_head)){
         $this->client_business_type = $this->client_head->business_category_type;
         $this->client_id = $this->client_head->id;
         $this->businessID = $this->client_head->business_id;    
       }
          
       # GETTING DATA CLIENT PREFERENCES
       $client_id = $this->client_id;

       if(!empty($client_id)){
            $client_preferences = $domain.'_API_CLIENTPREFERENCS'; 
             $this->client_preferencs = \Cache::remember($client_preferences,36000,function() use($client_id){
                   return \App\Models\ClientPreference::with('language','language.language','currency','currency.currency','primarylang','primarylang.language','primary','primary.currency')
                   ->where('client_id',$client_id)->first();
             });


             $ClientLanguages = $domain.'_API_LANGUAGES'; 
             $this->languages = \Cache::remember($ClientLanguages,3,function() use($client_id){
                   return \App\Models\ClientLanguage::with('language')->where('client_code',$client_id)->get();
             }); 
        
       }else{
        
        return response()->json(['status' => 0,'message' => 'Unauthorized access']);
       }


      

         

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
     case 'client':
        \Cache::forget($domain.'_API');
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



#---------------------------------------------------------------------------------------------------------------
#
#---------------------------------------------------------------------------------------------------------------
public function clientCategories(Request $request)
{
       $domain = $this->domain;
       $client_id = $this->client_id;
       $client_business_type = $this->client_business_type;
       $categories = \Cache::remember($domain.'_API_CATEGORIES_'.$this->client_business_type,3600,function() use($client_id,$client_business_type){
             return $categories = \App\Models\Category::select('id','icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id',
              'can_add_products', 'parent_id','category_type')
                        ->with('translation_one')
                        ->where('category_type',$client_business_type)
                        ->orderBy('position', 'asc')
                        ->orderBy('id', 'asc')
                        ->orderBy('parent_id', 'asc')
                        ->get();
       });
       
        $data = [
          'message'=> 'Client Category successfully!',
          'status'=> 1,
          'data' => [
             'listing' => $categories,
          ]
        ];
       

     return response()->json($data);
}




#---------------------------------------------------------------------------------------------------------------
#
#---------------------------------------------------------------------------------------------------------------
public function clientCategory(Request $request)
{
       $domain = $this->domain;
       $client_id = $this->client_id;
       $client_business_type = $this->client_business_type;
       $categories = \Cache::remember($domain.'_API_CATEGORIES_TREES_'.$this->client_business_type,3,function() use($client_id,$client_business_type){
             return $categories = \App\Models\Category::with('childs','childs.translation_one')
                        ->select('id','icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id','can_add_products', 'parent_id','category_type')
                        ->with('translation_one')
                        ->where('category_type',$client_business_type)
                        ->orderBy('position', 'asc')
                        ->where('parent_id', 1)
                        ->get();
       });
       
        $data = [
          'message'=> 'Client Category successfully!',
          'status'=> 1,
          'data' => [
             'listing' => $categories,
          ]
        ];
       

     return response()->json($data);
}

#---------------------------------------------------------------------------------------------------------------
#
#---------------------------------------------------------------------------------------------------------------
public function clientVendors(Request $request)
{
       $domain = $this->domain;
       $client_id = $this->client_id;
       $client_business_type = $this->client_business_type;
       $categories = \Cache::remember($domain.'_API_VENDORS_'.$this->client_business_type,3600,function() use($client_id,$client_business_type){
             return $categories =  $vendors = \App\Models\Vendor::select('id', 'name')->where('client_id',$client_id)->where('status',1)->get();
       });
       
        $data = [
          'message'=> 'Vendors loaded successfully!',
          'status'=> 1,
          'data' => [
             'listing' => $categories,
          ]
        ];
     return response()->json($data);
}


#---------------------------------------------------------------------------------------------------------------
# GET BUSINESS TYPES
#---------------------------------------------------------------------------------------------------------------

public function businessTypes(Request $request)
{
            $domain = $this->domain;
            $client_id = $this->client_id;
            $client_business_type = $this->client_business_type;
            $client = $this->client_head;
            $business_types = !empty($client->business_types) ? json_decode($client->business_types) : [];
            $categories = \Cache::remember($domain.'_API_BUSINESS_TYPE_'.$this->client_id,36,function() use($business_types){
                 return $business_types = \App\Models\BusinessType::where('parent',0)
                                                       ->whereIn('id',$business_types)
                                                       ->orderBy('title','ASC')
                                                       ->get();
            });
           
            $data = [
                'message'=> 'Business Types loaded successfully!',
                'status'=> 1,
                'data' => [
                   'listing' => $categories,
                ]
            ];
     return response()->json($data);


}

}
