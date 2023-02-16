<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


 

Route::group(['middleware' => 'languageSwitch'], function () {
    Auth::routes();
    include_once "images.php"; 
    Route::group(['middleware' => 'IsMainDomainAuth'], function () {
        include_once "business.php";
        //include_once "backend.php";
    });


     Route::group(['middleware' => 'IsAdminDomainAuth'], function () {
       include_once "godpanel.php";
     });
    Route::middleware(['CheckMainDomainAuth'])->group(function() {
            if(getDomainName() == 'localhost:3000'){
                 include_once "routes/tour_backend.php";
            }else{
                 include_once "backend.php";
            }
       
        Route::middleware(['subdomain','IsBusinessCompleted'])->group(function() {
            if(getDomainName() == 'localhost:1000' || getDomainName() == 'localhost:3000'){
                 include_once "tours.php";
            }else{
                 include_once "frontend.php";
            }
           
            Route::get('/share','HomeController@share')->name('share_link');
        });       
    });

    Route::get('showImg/{folder}/{img}',function($folder, $img){
        $image  = \Storage::disk('s3')->url($folder . '/' . $img);
        return \Image::make($image)->fit(460, 120)->response('jpg');
    });
    
    Route::get('/prods/{img}',function($img){
        $image  = \Storage::disk('s3')->url('prods/' . $img);
        return \Image::make($image)->fit(460, 320)->response('jpg');
    });


});

# Languange Switch
Route::get('/switch/language',function(Request $request){
    if($request->lang){
        session()->put("applocale",$request->lang);
    }
    return redirect()->back();
});

Route::get('comming-soon',function(){
            $existRedis = getWebClient();
                
                // dd($existRedis->isCompleted);
            if($existRedis->isCompleted == 1){
                 return redirect('/');
            }
 return view('commingSoonPage');
})->name('comming.soon');

// ADMIN languageSwitch 
Route::get('/switch/admin/language',function(Request $request){
    if($request->lang){
        session()->put("applocale_admin",$request->lang);
        session()->put("adminLanguage",$request->langid);
    }
    return redirect()->back();
});



