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
use App\Models\State; 
use App\Models\City; 
class CityController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20;
    	    $language_id = !empty($request->language_id) ? $request->language_id : 1;
            
            $business_types = $cate = City::with('country','state')->where('parent',0)
										              ->where('language_id',$language_id)
										              ->orderBy('name','ASC');
            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'cities loaded successfully',
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
            $category = City::where('id',$id)->first();
            $cate = City::where('parent',$id)->where('language_id',$language_id);

            if($category->language_id != $language_id){
                if($cate->count() == 0){
						$c = new City;
						$c->parent = $category->id;
						$c->name = $category->name;
						$c->language_id = $language_id;
						$c->status = $category->status; 
						$c->country_id = $category->country_id; 
	                    $c->state_id = $category->state_id; 
		                $c->save();
		                $category = $c;
            	}else{
            		    $category = $cate->first();
            	}
            }



            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'City loaded successfully',
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
            $business_types = City::where('name',$request->name)
            ->where('country_id',$request->country_id)->where('state_id',$request->state_id)->where('language_id',$language_id)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add city name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This city is already exists!'];
		    }else{

                  $c = new City;
	              $c->name = $request->name;
	              $c->language_id = $language_id;
	              $c->country_id = $request->country_id; 
	              $c->state_id = $request->state_id; 
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This city saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $business_types =City::where('name',$request->name)
            ->where('country_id',$request->country_id)->where('state_id',$request->state_id)
            ->where(function($t) use($id,$language_id){

            	        if($language_id == 1){
            	        	$t->where('id','!=',$id);
            	        }else{
            	        	$t->where('parent','!=',$id);
            	        }

                })->where('language_id',$language_id)->count();
			     
			    if(empty($request->name)) {
			    	$status = ['status' => 0, 'message' => 'Please add city name!'];
			    }elseif($business_types > 0) {
			    	$status = ['status' => 0, 'message' => 'This city is already exists!'];
			    }else{

		    	    $category = City::where('id',$id)->first();
		            $cate = City::where('parent',$id)
		            ->where('country_id',$request->country_id)
		            ->where('state_id',$request->state_id)
		            ->where('language_id',$language_id);

		            if($category->language_id != $language_id){

		            	if( $cate->count() == 0){
			                  $c = new City;
				              $c->parent = $category->id;
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->country_id = $request->country_id; 
				              $c->state_id = $request->state_id; 
				              $c->status = $request->status; 
				              $c->save();
				              
		            	}else{
		            		  $c = $cate->first();
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->country_id = $request->country_id;
				              $c->state_id = $request->state_id; 
				              $c->status = $request->status; 
				              $c->save();
		            	}
		            }else{
		            	  $category->name = $request->name;
			              $category->language_id = $language_id;
		            } 

					 
					$category->country_id = $request->country_id; 
					$category->state_id = $request->state_id; 
				    $category->status = $request->status; 
					$category->save();
					$cates = City::where('parent',$id)->get();

                     foreach ($cates as $key => $ct) {
                     	    $ct->state_id = $request->state_id;  
                     	    $ct->country_id = $request->country_id;  
							$ct->status = $request->status; 
			                $ct->save();
                     }

                    $status = ['status' => 1, 'message' => 'This city updated successfully!'];
		    }
		     return response()->json($status);
    }





}