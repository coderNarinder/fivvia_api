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
use App\Models\PaymentOption;
class PaymentController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20; 
            
            $business_types = $cate = PaymentOption::orderBy('code','ASC')->where('client_id',0);
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
            $category = PaymentOption::where('id',$id)->first();
             $response = [
		        'status' => 1,
		        
		        'message' => 'PaymentOption loaded successfully',
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
            $business_types = PaymentOption::where('code',$request->name)->where('client_id',0)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add PaymentOption name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This PaymentOption is already exists!'];
		    }else{

                  $c = new PaymentOption;
	              $c->code = $request->name;
				  $c->image = $request->image; 
				  $c->payment_gateway_link = $request->payment_gateway_link; 
				  $c->status = $request->status;  
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This PaymentOption saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            
		      $business_types = PaymentOption::where('code',$request->name)->where('client_id',0)->where('id','!=',$id)->count();
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add PaymentOption name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This PaymentOption is already exists!'];
		    }else{ 
				$c = PaymentOption::where('id',$id)->first();
				$c->code = $request->name;
				$c->image = $request->image; 
				$c->payment_gateway_link = $request->payment_gateway_link; 
				$c->status = $request->status;  
				$c->save();
                $status = ['status' => 1, 'message' => 'This PaymentOption updated successfully!'];
		    }
		     return response()->json($status);
    }





}