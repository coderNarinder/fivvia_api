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
use App\Models\Language;
class LanguageController extends BaseController
{




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function list(Request $request){

    	    $skip = !empty($request->skip) ? $request->skip : 0;
    	    $limit = !empty($request->limit) ? $request->limit : 20; 
            
            $business_types = $cate = Language::orderBy('basic_lang','DESC');
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
            $category = Language::where('id',$id)->first();
            



            $response = [
		        'status' => 1,
		        
		        'message' => 'Language loaded successfully',
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
            $business_types = Language::where('name',$request->name)->where('sort_code',$request->sort_code)->count();
		     
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add language name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This language is already exists!'];
		    }else{

                  $c = new Language;
	              $c->name = $request->name;
	              $c->sort_code = $request->sort_code;
	              $c->basic_lang = $request->basic_lang; 
	              $c->status = $request->status; 
	              $c->nativeName = $request->nativeName;  
	              $c->save();
                  $status = ['status' => 1, 'message' => 'This language saved successfully!'];
		    }
		     return response()->json($status);
    }




    #-------------------------------------------------------------------------------------------------
    # BUSINESS TYPES
    #-------------------------------------------------------------------------------------------------

    public function update(Request $request,$id){
            
		      $business_types = Language::where('name',$request->name)->where('sort_code',$request->sort_code)->where('id','!=',$id)->count();
		    if(empty($request->name)) {
		    	$status = ['status' => 0, 'message' => 'Please add language name!'];
		    }elseif($business_types > 0) {
		    	$status = ['status' => 0, 'message' => 'This language is already exists!'];
		    }else{ 
				$c = Language::where('id',$id)->first();
				$c->name = $request->name;
				$c->sort_code = $request->sort_code;
				$c->basic_lang = $request->basic_lang; 
				$c->status = $request->status; 
				$c->nativeName = $request->nativeName;  
				$c->save();
                $status = ['status' => 1, 'message' => 'This language updated successfully!'];
		    }
		     return response()->json($status);
    }





}