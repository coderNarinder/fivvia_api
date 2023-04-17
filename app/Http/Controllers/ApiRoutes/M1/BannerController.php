<?php

namespace App\Http\Controllers\ApiRoutes\M1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Controllers\ApiRoutes\M1\BaseController; 
use Illuminate\Support\Str;
class BannerController extends BaseController
{
  


	#--------------------------------------------------------------------------------------------------------------------------------------------------------------
	#  UPLOAD FILE FUNCTION
	#--------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function getBanners(Request $request)
	{ 
		 
		    $banners = \App\Models\Banner::where('client_id',$this->client_id)->orderBy('sorting', 'asc')->get();
		    $response = [
		        'status' => 0,
		        'message' => 'upload successfully',
		        'data' => [
		           'listing' => $banners
			       ]
		    ];
	        return response()->json($response);
	}


	#--------------------------------------------------------------------------------------------------------------------------------------------------------------
	#  UPLOAD FILE FUNCTION
	#--------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function topDestinations(Request $request)
	{ 
		 
		    $banners = \App\Models\TopDestination::with('country','state','city')->where('client_id',$this->client_id)->get();
		    $response = [
		        'status' => 0,
		        'message' => 'destination loaded successfully',
		        'data' => [
		           'listing' => $banners
			       ]
		    ];
	        return response()->json($response);
	}

	#------------------------------------------------------------------------------------------------
	# getVendors 
	#------------------------------------------------------------------------------------------------

	public function deleteBanner(Request $request)
	{
	    
	        $v = Banner::where('id', $request->banner_id)->where('client_id',$this->client_id);
	        if($v->count() == 1){
	        //  $vendor->delete();
	          $data = ['message'=> 'Banner deleted successfully!','status'=> 1];
	        }else{
	          $data = ['message'=> 'Something wrong!','status'=> 0];
	        }

	     return response()->json($data);
	}

	#------------------------------------------------------------------------------------------------
	# getVendors 
	#------------------------------------------------------------------------------------------------

	public function deleteDestinations(Request $request)
	{
	    
	        $v = \App\Models\TopDestination::where('id', $request->destination_id)->where('client_id',$this->client_id);
	        if($v->count() == 1){
	         $v->delete();
	          $data = ['message'=> 'Banner destination successfully!','status'=> 1];
	        }else{
	          $data = ['message'=> 'Something wrong!','status'=> 0];
	        }

	     return response()->json($data);
	}

	#------------------------------------------------------------------------------------------------
	# getVendors 
	#------------------------------------------------------------------------------------------------

    public function update(Request $request,$id)
    {
        $rules = array(
            'name' => 'required|string|max:150',
            'banner' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        );

        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['banner'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }
        
        if ($request->hasFile('image_mobile')) {    /* upload logo file */
            $rules['banner'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }

    
        $v  = \Validator::make($request->all(), $rules);

        if($v->fails()){
           return response()->json([
	            'status'=> 0,
	            'message' => 'Please fill all the fields!',
	            
	        ]);
        }else{
            $banner = Banner::where('id',$id)->where('client_id',$this->client_id)->first();
	        $banner->client_id = $this->client_id;
	        $banner->start_date_time = $request->start_date;
	        $banner->end_date_time = $request->end_date;
	        $banner->image = $request->banner;
	        $banner->description = $request->description;
	        $banner->link = $request->type;
	        $banner->redirect_category_id  = $request->category_id ;
	        $banner->redirect_vendor_id  = $request->vendor_id;
	        $banner->status  = !empty($request->status) ? 1 : 0;
	        $banner->save();
	        return response()->json([
	            'status'=>1,
	            'message' => 'Banner update Successfully!',
	            
	        ]);
        }
       
        
    }


	#------------------------------------------------------------------------------------------------
	# getVendors 
	#------------------------------------------------------------------------------------------------

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|max:150',
            'banner' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        );

        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['banner'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }
        
        if ($request->hasFile('image_mobile')) {    /* upload logo file */
            $rules['banner'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }

    
        $v  = \Validator::make($request->all(), $rules);

        if($v->fails()){
           return response()->json([
	            'status'=> 0,
	            'message' => 'Please fill all the fields!',
	            
	        ]);
        }elseif(strtotime($request->start_date) > strtotime($request->end_date)){
           return response()->json([
	            'status'=> 0,
	            'message' => 'Start date must be greater than end date!',
	            
	        ]);
        }else{
            $banner = new Banner();
	        $banner->client_id = $this->client_id;
	        $banner->start_date_time = $request->start_date;
	        $banner->end_date_time = $request->end_date;
	        $banner->image = $request->banner;
	        $banner->description = $request->description;
	        $banner->link = $request->type;
	        $banner->redirect_category_id  = $request->category_id ;
	        $banner->redirect_vendor_id  = $request->vendor_id;
	        $banner->status  = !empty($request->status) ? 1 : 0;
	        $banner->save();
	        return response()->json([
	            'status'=>1,
	            'message' => 'Banner created Successfully!',
	            
	        ]);
        }
       
        
    }


	#------------------------------------------------------------------------------------------------
	# getVendors 
	#------------------------------------------------------------------------------------------------

    public function updateDestinations(Request $request,$id)
    {
        $rules = array(
            'image' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        );

        $d = \App\Models\TopDestination::where('client_id',$this->client_id)->where('id','!=',$id)->where('city_id',$request->city_id)->count();

       
        $v  = \Validator::make($request->all(), $rules);

        if($v->fails()){
           return response()->json([
	            'status'=> 0,
	            'message' => 'Please fill all the fields!',
	            
	        ]);
        }elseif($d == 1){
           return response()->json([
	            'status'=> 0,
	            'message' => 'This destination already exists',
	            
	        ]);
        }else{
             $banner = \App\Models\TopDestination::where('client_id',$this->client_id)->where('id',$id)->first();
	         $banner->client_id = $this->client_id;
	         $banner->country_id = $request->country_id;
	         $banner->state_id = $request->state_id;
	         $banner->image = $request->image;
	         $banner->city_id = $request->city_id;
	         $banner->save();
	        return response()->json([
	            'status'=>1,
	            'message' => 'destination updated Successfully!',
	            
	        ]);
        }
       
        
    }


	#------------------------------------------------------------------------------------------------
	# getVendors 
	#------------------------------------------------------------------------------------------------

    public function storeDestinations(Request $request)
    {
        $rules = array(
            'image' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        );

        $d = \App\Models\TopDestination::where('client_id',$this->client_id)->where('city_id',$request->city_id)->count();

       
        $v  = \Validator::make($request->all(), $rules);

        if($v->fails()){
           return response()->json([
	            'status'=> 0,
	            'message' => 'Please fill all the fields!',
	            
	        ]);
        }elseif($d == 1){
           return response()->json([
	            'status'=> 0,
	            'message' => 'This destination already exists',
	            
	        ]);
        }else{
             $banner = new \App\Models\TopDestination;
	         $banner->client_id = $this->client_id;
	         $banner->country_id = $request->country_id;
	         $banner->state_id = $request->state_id;
	         $banner->image = $request->image;
	         $banner->city_id = $request->city_id;
	         $banner->save();
	        return response()->json([
	            'status'=>1,
	            'message' => 'destination created Successfully!',
	            
	        ]);
        }
       
        
    }


}