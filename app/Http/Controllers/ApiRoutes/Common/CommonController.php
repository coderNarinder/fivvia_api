<?php

namespace App\Http\Controllers\ApiRoutes\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Language;
class CommonController extends Controller
{
   

#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getLanguages(Request $request)
   {
       $countries = DB::table('languages')
       ->select(['id','sort_code','name','nativeName','basic_lang'])
       ->orderBy('name','ASC')->get();
       $data =[
         'message' => 'Languages loaded successfully',
         'data' => [
           'listing' => $countries
         ]

       ];
       return response()->json($data);
   }



#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getCurrencies(Request $request)
   {
       $countries = DB::table('currencies')
       ->select(['id','priority','name','iso_code','symbol','subunit','subunit_to_unit','symbol_first','html_entity','decimal_mark','thousands_separator','iso_numeric'])
       ->orderBy('name','ASC')->get();
       $data =[
         'message' => 'Currencies loaded successfully',
         'data' => [
           'listing' => $countries
         ]

       ];
       return response()->json($data);
   }



#-------------------------------------------------------------------------------------------------------------
#  Getting Countries
#-------------------------------------------------------------------------------------------------------------
   public function getPayments(Request $request)
   {
   	   $countries =  \App\Models\PaymentOption::where('client_id','0')
       ->orderBy('title','ASC')
       ->get();
       $data =[
       	 'message' => 'Payments loaded successfully',
       	 'data' => [
           'listing' => $countries
       	 ]

       ];
   	   return response()->json($data);
   }




}