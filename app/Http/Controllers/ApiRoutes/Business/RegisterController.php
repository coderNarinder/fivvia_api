<?php
namespace App\Http\Controllers\ApiRoutes\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\ApiRoutes\Business\BaseController; 
use Illuminate\Support\Str;
use Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\BusinessCategory;
use App\Models\Business\ClientBusinessType;
use App\Models\BusinessType;
use App\Models\ClientPreference;    
use App\Models\PaymentOption;
use App\Models\ClientCheckoutInformation;  
use App\Models\Language;
use App\Models\ClientCurrency;
use App\Models\SubscriptionPackages;
class RegisterController extends BaseController
{



  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function store_business_category(Request $request)
  {
      
      $category = BusinessCategory::where('id',$request->business_category);
      $user = $request->user();
      if($category->count() == 0){
        return redirect()->back()->with('messages','Business Category not found!!');
      }
      $cate = $category->first();
      $client = Client::find($user->client_id);

      if($client->business_category_id != $request->business_category || $client->business_category_id == 0){
        $client->business_category_id = $request->business_category;
      }
      $client->business_category_type = $cate->slug;
      $client->completed_steps = 1;
      $client->save();
      return response()->json([
        'status'=>1,
        'message' => 'Banner update Successfully!',
        
      ]);
  }

  
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function businessTypeStore(Request $request)
  {
  	  $user = $request->user();
      $client = Client::find($user->client_id);
      $oldBusinessTypes = ClientBusinessType::where('client_id',$user->client_id)->delete();
      $business_types = $request->business_type;
      if(is_array($business_types)){
      	 foreach ($business_types as $business_type_id) {
	          $c = new ClientBusinessType;
	          $c->client_id = $user->client_id;
	          $c->business_type_id = $business_type_id;
	          $c->status = 1;
	          $c->save();
         }
      }
      $oldBusinessTypeArray = ClientBusinessType::where('client_id',$user->client_id);
      if($oldBusinessTypeArray->count() == 0){
         return response()->json([
		        'status'=>2,
		        'message' => 'Please choose type atleast one.',
		        'data'=> $request->business_type
		      ]); 
      }
      $client->business_types = json_encode($oldBusinessTypeArray->pluck('business_type_id')->toArray());
      $client->completed_steps = 2;
      $client->save();
      return response()->json([
		        'status' => 1,
		        'message' => 'Business types saved successfully!',
	  ]); 

   }

  
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function businessTypeStoreNew(Request $request)
  {
    
     if(!empty($request->language_id)){
          $parent = 0;
          $user = $request->user();
          $business_category = $request->business_category; 
          $c = new BusinessType;
          $c->title =  $request->name;
          $c->language_id =$request->language_id;
          $c->image = !empty($request->image) ? $request->image : ''; 
          $c->business_category_id = $business_category; 
          $c->client_id = $user->client_id;
          $c->save();
          $status = ['status' => 1,'message' => 'Business type is submitted successfully, you can use it but admin will approve it'];
      }else{
          $status = ['status' => 0,'message' => 'Something wrong is going on.'];
      }
      return response()->json($status);
  }



  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

   public function checkBusinesField(Request $request)
   {

   	  $u = $request->user();
      $user = 0;
      if(!empty($request->value) && $request->type == 'email'){
        $user = Client::where('email',$request->value)->where('id','!=',$u->client_id)->count();
        
      }elseif(!empty($request->value) && $request->type == 'phone_number'){
        $user = Client::where('phone_number',$request->value)->where('id','!=',$u->client_id)->count();
       
      }elseif(!empty($request->value) && $request->type == 'company_name'){
        $user = Client::where('company_name',$request->value)->where('id','!=',$u->client_id)->count(); 
      }
      $result = $user == 0 ? 1 : 0;
      return response()->json(['status' => 1, 'type' => $request->type, 'result' => $result]);
   }


   public function checkBusinessName($value,$res)
   {
   	 
   	  if (str_contains($value, '@') !== false) { 
        $res = 1;
   	  }
   	  if (str_contains($value, '') !== false) { 
         $res = 1;
   	  }
   	  if (str_contains($value, '#') !== false) { 
         $res = 1;
   	  }
   	  return $res;
   }



  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------


  public function businessDetails(Request $request)
  {
         $user = $request->user(); 
         $countries = \App\Models\Country::where('parent',0)->where('language_id',1)->orderBy('name','ASC')->get();
         $timezones = \App\Models\Timezone::orderBy('timezone','ASC')->get();
         $languages = \App\Models\Language::where('id',1)->get();
         $currencies = \App\Models\Currency::orderBy('iso_code','asc')->get();
         $client = Client::find($user->client_id);
         $primaryCur = \App\Models\ClientCurrency::where('is_primary', 1)->where('client_id',$user->client_id); 
         $currency_id = $primaryCur->count() > 0 ? $primaryCur->first()->currency_id : 0;
         $email = Client::where('id','!=',$client->id)->where('email',$client->email)->count();
         $company_name = Client::where('id','!=',$client->id)->where('company_name',$client->company_name)->count();
         $phone_number = Client::where('id','!=',$client->id)->where('phone_number',$client->phone_number)->count(); 
         $result = [
            'completed_steps'=> 3,
            'email'=>$email,
            'currency_id'=>$currency_id,
            'company_name'=>$company_name,
            'currencies'=>$currencies,
            'phone_number'=>$phone_number, 
            'countries' => $countries,
            'timezones' => $timezones,
            'languages' => $languages,
          ];

        return response()->json($result);
  }
  


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------


  public function businessDetailStore(Request $request)
  {
        //  $v = \Validator::make($request->all(),[
	       //   'business_type' => 'required',
	       //   'company_name' => 'required',
	       //   'longitude' => 'required',
	       //   'latitude' => 'required',
	       //   'phone_number' => 'required',
	       //   'email' => 'required',
	       //   'country' => 'required',
	       //   'primary_language' => 'required',
	       //   'phone_number' => 'required',
	         
	       //   'company_address' => 'required',
	       //   'primary_currency' => 'required',
        // ]);

      $user = $request->user();
      
      $client = Client::find($user->client_id);
      $client->business_id = 'FB'.$client->id;
      $client->company_name = $request->company_name;
      $client->email = $request->email;
      $client->phone_number = $request->phone_number;
      $client->country_id = $request->country;
      $client->timezone = $request->timezone;
      $client->company_address = $request->company_address;
      $client->longitude = $request->longitude;
      $client->latitude = $request->latitude;
      $logo = $request->logo;
      $client->logo = $logo;
      $client->completed_steps = 3;
      $client->save();

      $ClientPreference = ClientPreference::where('client_id',$client->id);
      $business_type = ClientBusinessType::where('client_id',$client->id)->first();
      $p = $ClientPreference->count() > 0 ? $ClientPreference->first() : new ClientPreference;
      $p->client_id = $client->id;
      $p->client_code = $client->id; 
      $p->language_id  = $request->primary_language;
      $p->business_type = $business_type->businessType->slug;
      $p->save();


              $oldAdditional = ClientCurrency::where('currency_id', $request->primary_currency)->where('is_primary', 0)->where('client_id', $client->id)->delete();
              $c_data = ['is_primary' => 1,'client_code'=>$client->id,'client_id' => $client->id,'currency_id' => $request->primary_currency, 'doller_compare' => 1];
              $primaryCur = ClientCurrency::where('is_primary', 1)->where('client_id', $client->id);
            if($primaryCur->count() == 1){
                $primaryCur->update($c_data);
            }else{
               ClientCurrency::insert($c_data);
            }

       
      

      return response()->json(['status'=> 1, 'message' => 'Business details added sucessfully!']);

  }
  



public function packages(Request $request)
{
	 $packages = SubscriptionPackages::where('duration',$request->duration)->get();

	 $result = (ARRAY) getPackageFeaturesAll($request->duration);

	 $result = [ 
            'packages'=>$packages,
            'features'=> $result
     ];
     return response()->json($result);
}



public function savePackage(Request $request)
{
	    $user = $request->user();
	    $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',Auth::user()->client->id);
	    $client = Client::find($user->client_id);
	    $client->completed_steps = 4;
	    $client->members = $request->members;
	    $client->package_id = $request->package_id;
	    $client->save();
	    $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
	    $checkout->trial_status = 1;
	    $checkout->save();
	    return response()->json(['status'=> 1, 'message' => 'Business details added sucessfully!']);
}


public function getPaymentOptions(Request $request)
{
         $user = $request->user();
         $client = Client::find($user->client_id);
	        $paymentOptions = \App\Models\CountryMetaData::where('country_id',$client->country_id)->where('type','payments');
          $options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
          $payment_options = \App\Models\PaymentOption::whereIn('code',$options)->where('client_id',0)->get();
          $selected_options = \App\Models\PaymentOption::where('client_id',$client->id)->pluck('code')->toArray();
         $result = [ 
	        'options'=>$options,
	        'payment_options'=> $payment_options,
	        'selected_options'=>$selected_options
	     ];
	     return response()->json($result);
}



  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function paymentOptionSaved(Request $request)
  {
  	    $user = $request->user();
        $client = Client::findOrFail($user->client_id);
        $payment_opt = !empty($request->payment_options) ? (array)$request->payment_options : [];
        $payment_options = \App\Models\PaymentOption::where('client_id',0)->get();
        foreach($payment_options as $column => $p){
            if(in_array($p->code, $payment_opt)){
                 $payment_opts = PaymentOption::where('client_id',$client->id)->where('code',$p->code);
                 if($payment_opts->count() == 0){
                   $PaymentOption = new PaymentOption;
                   $PaymentOption->code = $p->code;
                   $PaymentOption->path = $p->path;
                   $PaymentOption->title = $p->title;
                   $PaymentOption->credentials = $p->credentials;
                   $PaymentOption->status = $p->status;
                   $PaymentOption->off_site = $p->off_site;
                   $PaymentOption->test_mode = $p->test_mode;
                   $PaymentOption->client_token_id = $client->code;
                   $PaymentOption->client_id = $client->id;
                   $PaymentOption->save();
                 }
            }else{
                   PaymentOption::where('client_id',$client->id)->where('code',$p->code)->delete();
            }
        } 

        $counts = \App\Models\PaymentOption::where('client_id',$client->id)->count();
        if($counts > 0){
             $client->completed_steps = 5;
             $client->save(); 
             return response()->json([
                'status'=> 1,
                'message' => 'Please payment options have been assigned sucessfully!'
            ]);
        }else{
             return response()->json([
                'status'=> 0,
                'message' => 'Please choose payment options'
            ]);
        }

  }





  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function logisticsSaved(Request $request)
  {
  	     $user = $request->user();
        $client = Client::find($user->client_id);
        $shipping_options = !empty($request->logistics) ? (array)$request->logistics : [];
        foreach($shipping_options as $type){
             $payment_opts = \App\Models\ShippingOption::where('client_id',$client->id)->where('code',$type);
             if($payment_opts->count() == 0){
                 $s = new \App\Models\ShippingOption;
                 $s->code = $type;
                 $s->client_id = $client->id;
                 $s->save();
             }
        } 
        \App\Models\ShippingOption::where('client_id',$client->id)->whereNotIn('code',$shipping_options)->delete();
        $counts = \App\Models\ShippingOption::where('client_id',$client->id)->count();
        if($counts > 0){
             $client->completed_steps = 6;
             $client->save(); 
             return response()->json([
                'status'=> 1, 
                'message' => 'Please shipping options have been assigned sucessfully!'
            ]);
        }else{
             return response()->json([
                'status'=> 0, 
                'message' => 'Please choose shipping options'
            ]);
        }

  }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function logistics(Request $request)
  {
        
           $user = $request->user();

        $client = Client::findOrFail($user->client_id);
        $business_types = json_decode($client->business_types);
        $logistics = \App\Models\BusinessType::whereIn('id',$business_types)->where('logistics',1)->count();
       

         if($logistics == 0){
           $client->completed_steps = 6;
           $client->save();
            
         } 
                  $paymentOptions = \App\Models\CountryMetaData::where('country_id',$client->country_id)
                                                               ->where('type','shipping_options');
                  $options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
                  $shipping_options = \App\Models\ShippingType::whereIn('id',$options)->get();
                  $selected = \App\Models\ShippingOption::where('client_id',$user->client_id)->pluck('code')->toArray();
                  $res = [ 
                    'logistics' => $shipping_options,
                    'hasIt' => $logistics,
                    'selected'=> $selected
                  ];
         
        return response()->json($res);
  }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------
 
    public function getClientDomians(Request $request)
    {
    	$user = $request->user();
        $c = Client::find($user->client_id);
        $domain = !empty($c->custom_domain) ? $c->custom_domain : $this->createDomain($c);
        
        $arr = [
           'domain_type'=>$c->domain_type,
           'domain'=>$domain,
           'subdomain'=>$domain,
        ];
         return response()->json($arr);
    }
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------
 
    public function createDomain($c)
    {
        if(!empty($c->company_name)){
            $domainArray = explode(' ', $c->company_name);
            $domain = implode('-', $domainArray);
            return 'https://'.strtolower($domain).'.fivvia.com';
         }
    }
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------
 
    public function saveDomains(Request $request)
    {
             $user = $request->user();
             $domain1 = $request->domain;
             $c = Client::find($user->client_id);
            


	        if($request->domain_type == 0){
	        	     $domain_url = str_replace('https://','',$domain1);
                 $domain_url = str_replace('http://','',$domain_url);
                 $domain =  Client::where('custom_domain',$domain_url)
                              ->where('id','!=',$c->id)
                              ->count();
	            if(!empty($request->domain)){
                    if($domain == 0){
	                    $c->custom_domain = $domain_url;
	                    $c->sub_domain = $domain_url;
	                    $c->completed_steps = 7;
	                    $c->save();
	                    $status = ['message'=>'Domain is assigned successfully!','status' => 1];
	                }else{ 
	                    $status = ['message'=>'The url is not available.','status' => 0];
	                }
	            }else{ 
	               $status = ['message'=>'Pease add domain first.','status' => 0];
	            }
	        }elseif($request->domain_type == 1){
	        	 $subdomain_url = str_replace('https://','',$request->subdomain);
                 $subdomain_url = str_replace('http://','',$subdomain_url);                 
                 $sub_domain = Client::where('custom_domain',$subdomain_url)
                             ->where('id','!=',$c->id)
                              ->count();
	            if(!empty($request->subdomain)){
	                 if($sub_domain == 0){
	                    $c->custom_domain = $subdomain_url;
	                    $c->sub_domain = $subdomain_url;
	                    $c->completed_steps = 7;
	                    $c->save();
	                    $status = ['message'=>'Domain is assigned successfully!','status' => 1];
	                
	                }else{
	                    $status = ['message'=>'The url is not available.','status' => 0];
	                }
	            }else{
	                    $status = ['message'=>'Pease add domain first.','status' => 0];
	            }
	        }  
	        return response()->json($status);
    }






  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function template(Request $request)
  { 
  	    $user = $request->user();
        $client = Client::findOrFail($user->client_id);
        $status = [ 'completed_steps'=> 8,'templates' => $client->package->getTemplates()];
        return response()->json($status);
  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function templateSave(Request $request)
  { 
  	    $user = $request->user();
        $client = Client::findOrFail($user->client_id);
        $ClientPreference = ClientPreference::where('client_id',$client->id);
        if(!empty($request->template)){
          $c = $ClientPreference->count() > 0 ? $ClientPreference->first() : new ClientPreference;
          $c->client_id = $client->id;
          $c->web_template_id = $request->template;
          $c->save();
          $client->completed_steps = 8;
          $client->save();
          $status = ['message'=>'Template saved successfully.','status' => 1]; 
        }else{
        	 $status = ['message'=>'Please choose any template.','status' => 0]; 
        }

        return response()->json($status);

  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function checkout(Request $request)
  { 
        $user = $request->user();
        $client = Client::findOrFail($user->client_id);
         

        $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);

        $package = $client->package;
        $members = $client->members;
        $domain_rate = 0;
        $rate = $package->price + ($members * $package->extra_per_member_rate);

        

        $country = $client->country;
        $tax = $country->tax > 0 ? (($rate / 100) * $country->tax) : 0;
        $pvtReg =$ClientCheckoutInformation->count() > 0 && $ClientCheckoutInformation->first()->pvtReg > 0 ? $ClientCheckoutInformation->first()->pvtReg : 0; 
        $total = $tax + $rate + $pvtReg;

        // domain rate + package price
        $subtotal = $rate + $domain_rate;

            $extra_members = [];
         if($client->members > 0){
            $extra_members = [
              'members' => $client->members,
              'price' => ($package->extra_per_member_rate * $client->members)
             ];
          }
                                                                         
        $packages = \App\Models\SubscriptionPackages::whereIn('id',$client->package->packagePlan())->orderBy('price','ASC')->get();

        $package_details = [
           'title' => $package->title,
           'price' => $package->price,
           'duration' => $package->duration,
           'default_member' => $package->default_member,
           'extra_per_member_rate' => $package->extra_per_member_rate,
           'extra_members' => $extra_members
        ];

          $domain_details = [
            'domain' => $client->custom_domain,
            'price'=> 'free'
          ];
          $trial_end_date = date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))));
          $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;

          $checkout->package_details = json_encode($package_details);
          $checkout->domain_details = json_encode($domain_details);
          $checkout->client_id = $client->id;
          $checkout->subtotal = $subtotal;
          $checkout->tax = $tax;
          $checkout->total = $total;
          $checkout->trial_end_date = $trial_end_date;
          $checkout->save();


          $res = [
              'completed_steps'=> 8,
              'client' => $client,
              'package' => $package,
              'packages' => $packages,
              'rate' => $rate,
              'tax' => $tax,
              'members' => $members,
              'total' => $total,
              'country' => $country,
              'checkout' => $checkout,
          ];
          return response()->json($res);
  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function checkoutDuration(Request $request)
  {    

            $user = $request->user();
            $client = \App\Models\Client::find($user->client_id); 
            $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);
            $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
    switch ($request->type) {
      case 'checkoutDuration':
            $client->package_id = $request->package_id;
            $client->save();
            return response()->json(['status' => 1, 'message' => 'Plan duration changed successfully!']);
        break;
      case 'complete-plan-step':
              $checkout->check_step = 2;
              $checkout->save();
           
            $client->completed_steps = $request->completed_steps;
            $client->save();
            return response()->json(['status' => 1, 'message' => 'step completed successfully!']);
        break;
       case 'complete-contacts-step':
           
            $checkout->name = $request->name;
            $checkout->email = $request->email;
            $checkout->phone = $request->phone;
            $checkout->address = $request->address;
            $checkout->check_step = 3;
            $checkout->save();
            $client->completed_steps = $request->completed_steps;
            $client->save();
            return response()->json(['status' => 1, 'message' => 'step completed successfully!']);
        break;
      case 'complete-privacy-step':
           
           
          
            $checkout->pvtReg = !empty($request->pvtReg) ? 30 : 0;
            $checkout->check_step = 4;
            $checkout->save();
            $client->completed_steps = $request->completed_steps;
            $client->save();
            return response()->json(['status' => 1, 'message' => 'step completed successfully!']);
        break;
      case 'complete-payment-step':
           
           
          
             $trial_end_date = date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))));
             $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);
             $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
             $checkout->paid_status = 1;
             $checkout->trial_end_date = $trial_end_date;
             $checkout->save();
             $client->completed_steps = $request->completed_steps;
             $client->save();
             return response()->json(['status' => 1, 'message' => 'step completed successfully!']);
        break;
      
      default:
        # code...
        break;
    }



         
  }

}