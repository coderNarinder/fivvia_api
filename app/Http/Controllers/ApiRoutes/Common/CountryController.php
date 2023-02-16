<?php

namespace App\Http\Controllers\ApiRoutes\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class CountryController extends Controller
{
   

#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getCountries(Request $request)
   {
   	   $countries = DB::table('countries')->select(['id','code','name','nicename','iso3','numcode','phonecode','latitude','longitude','language_id','tax'])->orderBy('name','ASC')->get();
       $data =[
       	 'message' => 'Country loaded successfully',
       	 'data' => [
           'listing' => $countries
       	 ]

       ];
   	   return response()->json($data);
   }


#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getStates(Request $request)
   {
   	   $states = DB::table('states')->select(['id','name','country_id','language_id'])->where('country_id',$request->country_id)->orderBy('name','ASC')->get();
       $data =[
       	 'message' => 'states loaded successfully',
       	 'data' => [
           'listing' => $states
       	 ]

       ];
   	   return response()->json($data);
   }



#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getCities(Request $request)
   {
       $countries = DB::table('cities')->select(['id','name','country_id','state_id','language_id'])->where('state_id',$request->state_id)->orderBy('name','ASC')->get();
       $data =[
         'message' => 'Country loaded successfully',
         'data' => [
           'listing' => $countries
         ]

       ];
       return response()->json($data);
   }


#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getAmenties(Request $request)
   {
       $countries = DB::table('amenties')->select(['id','name'])->orderBy('name','ASC')->get();
       $data =[
         'message' => 'Amenities loaded successfully',
         'data' => [
           'listing' => $countries
         ]

       ];
       return response()->json($data);
   }



#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getTags(Request $request)
   {
   	   $countries = DB::table('tour_tags')->select(['id','name'])->orderBy('name','ASC')->get();
       $data =[
       	 'message' => 'Amenities loaded successfully',
       	 'data' => [
           'listing' => $countries
       	 ] 
       ];
   	   return response()->json($data);
   }



#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

public function vendorDetail(Request $request,$slug)
{
       $domain = $this->domain;
       $categories = \Cache::remember($domain.'_API_triours',36,function() use($domain){
                $vendor = Vendor::where('slug', $slug)->where('client_id',1)->first();
                return $categories = \App\Models\Category::select('id','icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id','category_type')
                        ->with('translation_one')
                        ->where('category_type',1)
                        ->where(function ($q) use ($vendor) {
                            $q->whereNull('vendor_id')
                            ->orWhere('vendor_id', $vendor->id);
                        })
                        ->orderBy('position', 'asc')
                        ->orderBy('id', 'asc')
                        ->orderBy('parent_id', 'asc')
                        ->get();
       });
        
        $data = [
          'message'=> 'Vendor Category successfully!',
          'status'=> 1,
          'data' => [
             'listing' => $categories
          ]
        ];
       

     return response()->json($data);
}

}
