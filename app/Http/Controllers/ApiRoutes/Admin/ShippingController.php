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
use App\Models\ShippingType;
class ShippingController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20; 
            
            $business_types = $cate = ShippingType::orderBy('name','ASC');
            $response = [
		        'status' => 1,
		        'count' => $cate->count(),
		        'message' => 'currencies loaded successfully',
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
            $category = ShippingType::where('id',$id)->first();
             $response = [
		        'status' => 1,
		        
		        'message' => 'ShippingType loaded successfully',
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
            $business_types = ShippingType::where('name',$request->name)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add ShippingType name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This ShippingType is already exists!'];
		    }else{

                  $c = new ShippingType;
	              $c->name = $request->name;
				  $c->image = $request->image; 
				  $c->status = $request->status;  
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This ShippingType saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            
		      $business_types = ShippingType::where('name',$request->name)->where('id','!=',$id)->count();
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add ShippingType name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This ShippingType is already exists!'];
		    }else{ 
				$c = ShippingType::where('id',$id)->first();
				$c->name = $request->name;
				$c->image = $request->image; 
				$c->status = $request->status;  
				$c->save();
                $status = ['status' => 1, 'message' => 'This ShippingType updated successfully!'];
		    }
		     return response()->json($status);
    }





}