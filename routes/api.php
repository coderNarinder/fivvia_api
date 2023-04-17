<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

 
 require_once "api_routes/m1/api.php";
 

Route::group(['prefix' => 'business'], function () {
    require_once "api_routes/business/api.php";
});


 require_once "api_routes/admin/api.php";
 

$prefix = 'v1';
require_once $prefix."/auth.php";
require_once $prefix."/guest.php";
