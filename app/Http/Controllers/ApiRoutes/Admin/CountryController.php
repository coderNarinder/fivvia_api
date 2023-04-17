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
use App\Models\Country;
use App\Models\ClientPreference;
use App\Models\ClientCurrency;
use App\Models\PaymentOption;
use App\Models\CountryMetaData;
class CountryController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20;
    	    $language_id = !empty($request->language_id) ? $request->language_id : 1;
            
            $business_types = $cate = Country::where('parent',0)
										              ->where('language_id',$language_id)
										              ->orderBy('name','ASC');
            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'business categories loaded successfully',
		        'data' => [
		           'listing' => $business_types->skip($skip)->limit($limit)->get()
			    ]
		    ];
	        return response()->json($response);
    }


 
    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function edit(Request $request,$id){ 
    	    $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $category = Country::where('id',$id)->first();
            $cate = Country::where('parent',$id)->where('language_id',$language_id);

            if($category->language_id != $language_id){
                if( $cate->count() == 0){
						$c = new Country;
						$c->parent = $category->id;
						$c->name = $category->name;
						$c->language_id = $language_id;
						$c->code = $category->code; 
						$c->nicename = $category->nicename; 
						$c->iso3 = $category->iso3; 
						$c->numcode = $category->numcode; 
						$c->phonecode = $category->phonecode; 
						$c->latitude = $category->latitude; 
						$c->longitude = $category->longitude; 
						$c->tax = $category->tax; 
		                $c->save();
		                $category = $c;
            	}else{
            		    $category = $cate->first();
            	}
            }



            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'Business categories loaded successfully',
		        'data' => [
		           'listing' => $category
			    ]
		    ];
	        return response()->json($response);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function store(Request $request){
            $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $business_types = Country::where('name',$request->name)->where('language_id',$language_id)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add country name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This country is already exists!'];
		    }else{

                  $c = new Country;
	              $c->name = $request->name;
	              $c->language_id = $language_id;
	              $c->code = $request->code; 
	              $c->nicename = $request->nicename; 
	              $c->iso3 = $request->iso3; 
	              $c->numcode = $request->numcode; 
	              $c->phonecode = $request->phonecode; 
	              $c->latitude = $request->latitude; 
	              $c->longitude = $request->longitude; 
	              $c->tax = $request->tax;
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This country saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $business_types = Country::where('name',$request->name)->where(function($t) use($id,$language_id){

            	        if($language_id == 1){
            	        	$t->where('id','!=',$id);
            	        }else{
            	        	$t->where('parent','!=',$id);
            	        }

            })->where('language_id',$language_id)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add country name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This country is already exists!'];
		    }else{

		    	    $category = Country::where('id',$id)->first();
		            $cate = Country::where('parent',$id)->where('language_id',$language_id);

		            if($category->language_id != $language_id){

		            	if( $cate->count() == 0){
			                  $c = new Country;
				              $c->parent = $category->id;
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->code = $request->code; 
				              $c->nicename = $request->nicename; 
				              $c->iso3 = $request->iso3; 
				              $c->numcode = $request->numcode; 
				              $c->phonecode = $request->phonecode; 
				              $c->latitude = $request->latitude; 
				              $c->longitude = $request->longitude; 
				              $c->tax = $request->tax;
				              $c->status = $request->status; 
				              $c->save();
				              
		            	}else{
		            		  $c = $cate->first();
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->code = $request->code; 
				              $c->nicename = $request->nicename; 
				              $c->iso3 = $request->iso3; 
				              $c->numcode = $request->numcode; 
				              $c->phonecode = $request->phonecode; 
				              $c->latitude = $request->latitude; 
				              $c->longitude = $request->longitude; 
				              $c->tax = $request->tax;
				              $c->status = $request->status; 
				              $c->save();
		            	}
		            }else{
		            	  $category->name = $request->name;
			              $category->language_id = $language_id;
		            } 

					 
					$category->code = $request->code; 
					$category->nicename = $request->nicename; 
					$category->iso3 = $request->iso3; 
					$category->numcode = $request->numcode; 
					$category->phonecode = $request->phonecode; 
					$category->latitude = $request->latitude; 
					$category->longitude = $request->longitude; 
					$category->tax = $request->tax; 
					$category->status = $request->status; 
					$category->save();
					$cates = Country::where('parent',$id)->get();

                     foreach ($cates as $key => $ct) {
                     	    $ct->code = $request->code; 
							$ct->nicename = $request->nicename; 
							$ct->iso3 = $request->iso3; 
							$ct->numcode = $request->numcode; 
							$ct->phonecode = $request->phonecode; 
							$ct->latitude = $request->latitude; 
							$ct->longitude = $request->longitude; 
							$ct->tax = $request->tax; 
							$ct->status = $request->status; 
			                $ct->save();
                     }

                    $status = ['status' => 1, 'message' => 'This country updated successfully!'];
		    }
		     return response()->json($status);
    }






    public function getSettings(Request $request,$id)
    { 

			$payment = \App\Models\CountryMetaData::where('type','payments')->where('country_id',$id);
			$payments = $payment->count() > 0 ? json_decode($payment->first()->meta_values) : [];

			$shipping_option = \App\Models\CountryMetaData::where('type','shipping_options')->where('country_id',$id);
			$shipping_options = $shipping_option->count() > 0 ? json_decode($shipping_option->first()->meta_values) : [];

			$language = \App\Models\CountryMetaData::where('type','languages')->where('country_id',$id);
			$languages = $language->count() > 0 ? json_decode($language->first()->meta_values) : [];
			 
            $category = Country::where('id',$id)->first();

		      $language_data = \App\Models\Language::orderBy('id','ASC')->get(); 
		      $shipping_types = \App\Models\ShippingType::orderBy('name','ASC')->get(); 
		      $payment_options = \App\Models\PaymentOption::where('client_id',0)->orderBy('title','ASC')->get(); 

            $response = [
		        'status' => 1,
		        'message' => 'Business categories loaded successfully',
		        'data' => [
		           'listing' => $category,
		           'shipping_options' => $shipping_options,
		           'languages' => $languages,
		           'payments' => $payments,
		           'language_data' => $language_data,
		           'shipping_types' => $shipping_types,
		           'payment_options' => $payment_options,
			    ]
		    ];
	        return response()->json($response);
    
    }



    public function storeSettings(Request $request,$id)
    {
    	 $types =[];
         $arr = ['_token'];
          CountryMetaData::where('country_id',$id)->delete();
          if(!empty($request->payments)){
              $c = new CountryMetaData;
		      $c->type = 'payments';
		      $c->country_id = $id;
		      $c->meta_values = json_encode($request->payments);
		      $c->save(); 
          }

          if(!empty($request->shipping)){
              $c = new CountryMetaData;
		      $c->type = 'shipping_options';
		      $c->country_id = $id;
		      $c->meta_values = json_encode($request->shipping);
		      $c->save(); 
          }

          if(!empty($request->languages)){
              $c = new CountryMetaData;
		      $c->type = 'languages';
		      $c->country_id = $id;
		      $c->meta_values = json_encode($request->languages);
		      $c->save(); 
          }
	      
	     
            $response = [
		        'status' => 1,
		        'message' => 'Country settings saved successfully'
		    ];
	        return response()->json($response);

     
    }





}