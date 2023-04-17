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
class StateController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20;
    	    $language_id = !empty($request->language_id) ? $request->language_id : 1;
            
            $business_types = $cate = State::with('country')->where('parent',0)
                                            ->where(function($t) use($request){
                                            	if(!empty($request->country_id)){
                                            		$t->where('country_id',$request->country_id);
                                            	}
                                            })
										              ->where('language_id',$language_id)
										              ->orderBy('name','ASC');
            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'states loaded successfully',
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
            $category = State::where('id',$id)->first();
            $cate = State::where('parent',$id)->where('language_id',$language_id);

            if($category->language_id != $language_id){
                if($cate->count() == 0){
						$c = new State;
						$c->parent = $category->id;
						$c->name = $category->name;
						$c->language_id = $language_id;
						$c->status = $category->status; 
		                $c->save();
		                $category = $c;
            	}else{
            		    $category = $cate->first();
            	}
            }



            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'State loaded successfully',
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
            $business_types = State::where('name',$request->name)->where('country_id',$request->country_id)->where('language_id',$language_id)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add state name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This state is already exists!'];
		    }else{

                  $c = new State;
	              $c->name = $request->name;
	              $c->language_id = $language_id;
	              $c->country_id = $request->country_id; 
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This state saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            $language_id = !empty($request->language_id) ? $request->language_id : 1;
            $business_types = State::where('name',$request->name)->where('country_id',$request->country_id)
                ->where(function($t) use($id,$language_id){

            	        if($language_id == 1){
            	        	$t->where('id','!=',$id);
            	        }else{
            	        	$t->where('parent','!=',$id);
            	        }

                })->where('language_id',$language_id)->count();
			     
			    if(empty($request->name)) {
			    	$status = ['status' => 0, 'message' => 'Please add state name!'];
			    }elseif($business_types > 0) {
			    	$status = ['status' => 0, 'message' => 'This state is already exists!'];
			    }else{

		    	    $category = State::where('id',$id)->first();
		            $cate = State::where('parent',$id)->where('language_id',$language_id);

		            if($category->language_id != $language_id){

		            	if( $cate->count() == 0){
			                  $c = new State;
				              $c->parent = $category->id;
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->country_id = $request->country_id; 
				              $c->status = $request->status; 
				              $c->save();
				              
		            	}else{
		            		  $c = $cate->first();
				              $c->name = $request->name;
				              $c->language_id = $language_id;
				              $c->country_id = $request->country_id;
				              $c->status = $request->status; 
				              $c->save();
		            	}
		            }else{
		            	  $category->name = $request->name;
			              $category->language_id = $language_id;
		            } 

					 
					$category->country_id = $request->country_id; 
				    $category->status = $request->status; 
					$category->save();
					$cates = State::where('parent',$id)->get();

                     foreach ($cates as $key => $ct) {
                     	    $ct->country_id = $request->country_id;  
							$ct->status = $request->status; 
			                $ct->save();
                     }

                    $status = ['status' => 1, 'message' => 'This state updated successfully!'];
		    }
		     return response()->json($status);
    }





}