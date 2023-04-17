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
use App\Models\Currency;
class CurrencyController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20; 
            
            $business_types = $cate = Currency::orderBy('name','ASC');
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
            $category = Currency::where('id',$id)->first();
             $response = [
		        'status' => 1,
		        
		        'message' => 'Currency loaded successfully',
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
            $business_types = Currency::where('name',$request->name)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add Currency name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This Currency is already exists!'];
		    }else{

                  $c = new Currency;
	              $c->name = $request->name;
	              $c->priority = $request->priority;
	              $c->iso_code = $request->iso_code;
	              $c->status = $request->status;
	              $c->symbol = $request->symbol;
	              $c->subunit = $request->subunit;
	              $c->subunit_to_unit = $request->subunit_to_unit;
	              $c->symbol_first = $request->symbol_first;
	              $c->html_entity = $request->html_entity;
	              $c->iso_code = $request->iso_code;
	              // $c->decimal_mark = $request->decimal_mark;
	              // $c->thousands_separator = $request->thousands_separator;
	              $c->iso_numeric = $request->iso_numeric;
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This Currency saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            
		      $business_types = Currency::where('name',$request->name)->where('id','!=',$id)->count();
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add Currency name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This Currency is already exists!'];
		    }else{ 
				$c = Currency::where('id',$id)->first();
				$c->name = $request->name;
				$c->priority = $request->priority;
				$c->iso_code = $request->iso_code; 
				$c->status = $request->status; 
				$c->symbol = $request->symbol;  
				$c->subunit = $request->subunit;  
				$c->subunit_to_unit = $request->subunit_to_unit;  
				$c->symbol_first = $request->symbol_first;  
				$c->html_entity = $request->html_entity;  
				$c->iso_code = $request->iso_code;  
				// $c->decimal_mark = $request->decimal_mark;  
				// $c->thousands_separator = $request->thousands_separator;  
				$c->iso_numeric = $request->iso_numeric;  
				$c->save();
                $status = ['status' => 1, 'message' => 'This Currency updated successfully!'];
		    }
		     return response()->json($status);
    }





}