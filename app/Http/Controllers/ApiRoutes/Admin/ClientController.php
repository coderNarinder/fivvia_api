<?php

namespace App\Http\Controllers\ApiRoutes\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\ApiRoutes\Business\BaseController; 
use Illuminate\Support\Str;
use Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\BusinessType;
use App\Models\BusinessCategory;
use App\Models\ClientPreference;
use App\Models\ClientCurrency;
use App\Models\PaymentOption;
class ClientController extends BaseController
{

 
    public function index(Request $request){
            $clients = Client::with('businessCategory','businessTypes','businessTypes.businessType')
                             ->whereHas('businessCategory')
                             ->whereHas('businessTypes', function($r){
                             	 $r->whereHas('businessType');
                             }) 
                             
                             ->where('is_deleted',0)
                             ->where(function($t) use($request){
                             	if(!empty($request->category_id)){
                             		$t->where('business_category_id',$request->category_id);
                             	}
                             	if(!empty($request->business_type_id)){
                             		$t->where('business_category_id',$request->business_type_id);
                             	}

                             	if($request->status == 10){
                                    $t->where('completed_steps','<',12)
                                    ->where('completed_steps','>',7);
                             	}else{
                             		 $t ->where('completed_steps','>',11)->where('status',$request->status)->whereHas('businessCategory')->whereHas('businessTypes');
                             	}

                             	
                             })
                             ->orderBy('created_at', 'DESC')->get();


            $approved_count = Client::with('businessCategory','businessTypes','businessTypes.businessType')
                             ->whereHas('businessCategory')
                             ->whereHas('businessTypes')
                             ->where('is_deleted', 0)
                             ->where('status',1)
                             ->where('completed_steps','>',11)
                             ->orderBy('created_at', 'DESC')
                             ->count();

             $incomplete_account = Client::with('businessCategory','businessTypes','businessTypes.businessType')
                             ->whereHas('businessCategory')
                             ->whereHas('businessTypes') 
                             ->where('is_deleted', 0)
                             ->where('completed_steps','<',12)
                             ->where('completed_steps','>',7)
                             ->orderBy('created_at', 'DESC')
                             ->count();

            $blocked_count = Client::with('businessCategory','businessTypes','businessTypes.businessType')
                             ->whereHas('businessCategory')
                             ->whereHas('businessTypes')
                             ->where('is_deleted', 0)
                             ->where('status',2)
                             ->where('completed_steps','>',11)
                             ->orderBy('created_at', 'DESC')
                             ->count();
            $pending_count = Client::with('businessCategory','businessTypes','businessTypes.businessType')
                             ->whereHas('businessCategory')
                             ->whereHas('businessTypes')
                             ->where('is_deleted', 0)
                             ->where('status',0)
                             ->where('completed_steps','>',11)
                             ->orderBy('created_at', 'DESC')
                             ->count();



            $response = [
		        'status' => 0,
		        'message' => 'clients loaded successfully',
		        'data' => [
		           'listing' => $clients,
		           'approved_count'=> $approved_count,
		           'blocked_count'=> $blocked_count,
		           'pending_count'=> $pending_count,
		           'incomplete_account'=> $incomplete_account,
		           
		           
			    ]
		    ];
	        return response()->json($response);
    }

    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function clientDetails(Request $request,$id){
            $clients = Client::where('id',$id)->orderBy('created_at', 'DESC')->first();
            $business_category_id = $clients->business_category_id > 0 ?  $clients->business_category_id : 0;

             $business_types = \App\Models\BusinessType::where('parent',0) 
                                                       ->where(function($t) use($business_category_id){
                                                       	    $t->where('business_category_id',$business_category_id);
                                                       })
                                                       ->orderBy('title','ASC')
                                                       ->get();
            $business_categories = \App\Models\BusinessCategory::where('parent',0)->orderBy('sorting','ASC')->get();
            $timezones = \App\Models\Timezone::orderBy('timezone','ASC')->get();
            $languages = \App\Models\Language::where('id',1)->get();
            $currencies = \App\Models\Currency::orderBy('iso_code','asc')->get();
            
            $ClientPreference = \App\Models\ClientPreference::where('client_id',$clients->id);
            $primary_language = $ClientPreference->count() > 0  ? $ClientPreference->first()->language_id : 0;


			$primaryCur = \App\Models\ClientCurrency::where('is_primary', 1)->where('client_id',$id); 
			$currency_id = $primaryCur->count() > 0 ? $primaryCur->first()->currency_id : 0;

			$paymentOptions = \App\Models\CountryMetaData::where('country_id',$clients->country_id)->where('type','payments');
			$options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
			$payment_options = \App\Models\PaymentOption::whereIn('code',$options)->where('client_id',0)->get();
			$selected_options = \App\Models\PaymentOption::where('client_id',$clients->id)->pluck('code')->toArray();


			$shippingOptions = \App\Models\CountryMetaData::where('country_id',$clients->country_id)->where('type','shipping_options');
            $options = $shippingOptions->count() > 0 ? json_decode($shippingOptions->first()->meta_values) : [];
            $shipping_options = \App\Models\ShippingType::whereIn('id',$options)->get();
            $selected_shipping = \App\Models\ShippingOption::where('client_id',$id)->pluck('code')->toArray();
  

            $response = [
		        'status' => 0,
		        'message' => 'clients loaded successfully',
		        'data' => [
		           'listing' => $clients,
		           'business_types' => $business_types,
		           'business_categories' => $business_categories,
		           'timezones'=> $timezones,
		           'languages'=> $languages,
		           'currencies'=> $currencies,
		           'primary_currency'=> $currency_id,
		           'primary_language'=> $primary_language,
		           'payment_options'=> $payment_options,
		           'selected_options'=> $selected_options,
		           'shipping_options'=> $shipping_options,
		           'selected_shipping'=> $selected_shipping,
		           'templates' => $clients->package->getTemplates(),
		           'web_template_id'=> $ClientPreference->first()->web_template_id
			    ]
		    ];
	        return response()->json($response);
    }

    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function updateClient(Request $request,$id){
            
           $emailCount = Client::where('email',$request->email)->where('id','!=',$id)->count();
           $phoneCount = Client::where('phone_number',$request->phone_number)->where('id','!=',$id)->count();
           $companyCount = Client::where('company_name',$request->company_name)->where('id','!=',$id)->count();
 

            if(empty($request->category_id)){
            	$response = ['status' => 0, 'message' => 'Please choose business caegory first!'];
            }elseif(empty($request->business_types)){
            	$response = ['status' => 0, 'message' => 'Please choose business types first!'];
            }elseif($emailCount > 0){
            	$response = ['status' => 0, 'message' => 'Email already exists'];
            }elseif($phoneCount > 0){
            	$response = ['status' => 0, 'message' => 'Phone number already exists'];
            }elseif($companyCount > 0){
            	$response = ['status' => 0, 'message' => 'Company name already exists'];
            }elseif(empty($request->payment_options)){
            	$response = ['status' => 0, 'message' => 'Please choose payments options!'];
            }elseif(empty($request->logistics)){
            	$response = ['status' => 0, 'message' => 'Please choose logistics first!'];
            }elseif(empty($request->template_id)){
            	$response = ['status' => 0, 'message' => 'Please choose template!'];
            }else{


				$category = \App\Models\BusinessCategory::where('id',$request->category_id);
				$cate = $category->first();

				$client = Client::find($id); 
				if($client->completed_steps > 11){
					$client->status = $request->status;
				}
				$client->custom_domain = $request->custom_domain;
				$client->company_name = $request->company_name;
				$client->email = $request->email;
				$client->phone_number = $request->phone_number;
				$client->country_id = $request->country_id;
				$client->timezone = $request->timezone;
				$client->company_address = $request->company_address;
				$client->longitude = $request->longitude;
				$client->latitude = $request->latitude;
                $client->business_category_id =$request->category_id;
			    $client->business_category_type = $cate->slug;
 
				$oldBusinessTypes = \App\Models\Business\ClientBusinessType::where('client_id',$id)->delete();
				$business_types = $request->business_types;
				$client->business_types = json_encode($request->business_types);
				if(is_array($business_types)){
					foreach ($business_types as $business_type_id) {
						$c = new \App\Models\Business\ClientBusinessType;
						$c->client_id = $id;
						$c->business_type_id = $business_type_id;
						$c->status = 1;
						$c->save();
					}
				} 
				#
				$ClientPreference = ClientPreference::where('client_id',$id);
				$c = $ClientPreference->count() > 0 ? $ClientPreference->first() : new ClientPreference;
				$c->client_id = $client->id;
				$c->web_template_id = $request->template_id;
				$c->language_id  = $request->primary_language;
				$c->save();
				$client->save();


				$oldAdditional = ClientCurrency::where('currency_id', $request->primary_currency)->where('is_primary', 0)->where('client_id', $client->id)->delete();
				$c_data = ['is_primary' => 1,'client_code'=>$client->id,'client_id' => $client->id,'currency_id' => $request->primary_currency, 'doller_compare' => 1];
				$primaryCur = ClientCurrency::where('is_primary', 1)->where('client_id', $client->id);
				if($primaryCur->count() == 1){
				   $primaryCur->update($c_data);
				}else{
				   ClientCurrency::insert($c_data);
				}

				#----------------------------------------------------------------------------------------------------------------------

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

                #----------------------------------------------------------------------------------------------------------------------
                
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

                #----------------------------------------------------------------------------------------------------------------------


				$response = [
				    'status' => 1,
				    'message' => 'updated successfully',
				];
            }
           
            
	        return response()->json($response);
    }


    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function businessTypes(Request $request){
            
           $business_types = \App\Models\BusinessType::where('parent',0) 
                                                       ->where('business_category_id',$request->category_id)
                                                       ->orderBy('title','ASC')
                                                       ->get();
            $response = [
		        'status' => 0,
		        'message' => 'business types loadeds successfully',
		        'data' => [
		           'listing' => $business_types
			    ]
		    ];
	        return response()->json($response);
    }


    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function businessCategory(Request $request){
            
           $business_types = \App\Models\BusinessCategory::where('parent',0)->orderBy('sorting','ASC')->get();
            $response = [
		        'status' => 0,
		        'message' => 'business categories loaded successfully',
		        'data' => [
		           'listing' => $business_types
			    ]
		    ];
	        return response()->json($response);
    }



}