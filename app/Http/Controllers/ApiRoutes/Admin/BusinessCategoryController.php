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
class BusinessCategoryController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20;
    	    $language_id = !empty($request->language_id) ? $request->language_id : 1;
            
            $business_types = $cate = BusinessCategory::where('parent',0)
										              ->where('language_id',$language_id)
										              ->orderBy('sorting','ASC');
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

    public function updateBusinessCategory(Request $request,$id){ 
    	    $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $category = BusinessCategory::where('id',$id)->first();
            $cate = BusinessCategory::where('parent',$id)->where('language_id',$language_id);

            if($category->language_id != $language_id){

            	if( $cate->count() == 0){
	                  $c = new BusinessCategory;
		              $c->parent = $category->id;
		              $c->name = $category->name;
		              $c->language_id = $language_id;
		              $c->image = $category->image; 
		              $c->save();
		              $category = $c;
            	}else{
            		  $category = $cate->first();
            	}
            }



            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'business categories loaded successfully',
		        'data' => [
		           'listing' => $category
			    ]
		    ];
	        return response()->json($response);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function businessCategoryStore(Request $request){
            $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $business_types = BusinessCategory::where('name',$request->name) ->where('language_id',$language_id)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add category name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This category is already exists!'];
		    }else{

                  $c = new BusinessCategory;
	              $c->name = $request->name;
	              $c->language_id = $language_id;
	              $c->image = $request->image; 
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This category saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function businessCategoryUpdate(Request $request,$id){
            $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $business_types = BusinessCategory::where('name',$request->name)->where(function($t) use($id,$language_id){

            	        if($language_id == 1){
            	        	$t->where('id','!=',$id);
            	        }else{
            	        	$t->where('parent','!=',$id);
            	        }

            })->where('id','!=',$id)->where('language_id',$language_id)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add category name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This category is already exists!'];
		    }else{

		    	    $category = BusinessCategory::where('id',$id)->first();
		            $cate = BusinessCategory::where('parent',$id)->where('language_id',$language_id);

		            if($category->language_id != $language_id){

		            	if( $cate->count() == 0){
			                  $c = new BusinessCategory;
				              $c->parent = $category->id;
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->image = $request->image; 
				              $c->status = $request->status; 
				              $c->save();
				              
		            	}else{
		            		  $c = $cate->first();
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->image = $request->image; 
				              $c->status = $request->status; 
				              $c->save();
		            	}
		            }else{
		            	  $category->name = $request->name;
			              $category->language_id = $language_id;
		            } 

					$category->image = $request->image; 
					$category->status = $request->status; 
					$category->save();
					$cates = BusinessCategory::where('parent',$id)->get();

                     foreach ($cates as $key => $ct) {
                     	   $ct->image = $request->image; 
                     	   $ct->status = $request->status; 
			               $ct->save();
                     }

                    $status = ['status' => 1, 'message' => 'This category updated successfully!'];
		    }
		     return response()->json($status);
    }





}