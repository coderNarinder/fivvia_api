<?php


 
Route::group(['prefix' => 'auth','middleware' =>  'auth:sanctum'], function () {
   Route::get('/loginDetails', 'ApiRoutes\Auth\LoginController@loginDetails')->name('api.auth.loginDetails');
});



Route::group(['prefix' => 'auth'], function () {
   Route::post('/login', 'ApiRoutes\Auth\LoginController@getLoggedIn')->name('api.auth.login');
});
Route::group(['prefix' => 'm1','middleware' =>  'auth:sanctum'], function () {
   Route::get('/vendors', 'ApiRoutes\M1\VenderController@getVendors')->name('api.m1.getVendors');
   Route::post('/vendors/create/new', 'ApiRoutes\M1\VenderController@createVendor')->name('api.m1.createVendor');
   Route::post('/vendor/{slug}/edit', 'ApiRoutes\M1\VenderController@editVendor')->name('api.m1.editVendor');
   Route::any('/vendors/delete', 'ApiRoutes\M1\VenderController@deleteVendor')->name('api.m1.deleteVendor');
   Route::get('/vendorDetail/{slug}', 'ApiRoutes\M1\VenderController@vendorDetail')->name('api.m1.vendorDetail');
   Route::get('/vendorStatusUpdate/{slug}', 'ApiRoutes\M1\VenderController@vendorStatusUpdate')->name('api.m1.vendorStatusUpdate');
   Route::get('/vendorCategories/{slug}', 'ApiRoutes\M1\VenderController@vendorCategories')->name('api.m1.vendorCategories');
   Route::post('/updateVendorCategories/{slug}', 'ApiRoutes\M1\VenderController@updateVendorCategories')->name('api.m1.updateVendorCategories');
   Route::get('/vendor-category-list/{slug}', 'ApiRoutes\M1\VenderController@getVendorCategories')->name('api.m1.getVendorCategories');


    Route::get('/vendor-products/{slug}', 'ApiRoutes\M1\ProductController@getVendorProducts')->name('api.m1.getVendorProducts');
    Route::get('/vendor/product/{slug}/detail', 'ApiRoutes\M1\ProductController@getProductDetails')->name('api.m1.getProductDetails');



    Route::post('/product/{slug}/update', 'ApiRoutes\M1\ProductController@getProductUpdate')->name('api.m1.getProductUpdate');
    Route::get('/products', 'ApiRoutes\M1\ProductController@getProducts')->name('api.m1.getProducts');
    Route::post('/product/{slug}/create', 'ApiRoutes\M1\ProductController@createProduct')->name('api.m1.createProduct');
    Route::get('/product/delete', 'ApiRoutes\M1\ProductController@deleteProduct')->name('api.m1.deleteProduct');
    Route::post('/settings/update', 'ApiRoutes\M1\SettingsController@updateSettings')->name('api.m1.updateSettings');

    Route::get('/settings/customization', 'ApiRoutes\M1\SettingsController@getCustomization')->name('api.m1.getCustomization'); 
    Route::get('/payments/options', 'ApiRoutes\M1\SettingsController@getPaymentOptions')->name('api.m1.getPaymentOptions'); 
    Route::post('settings/payments/update', 'ApiRoutes\M1\SettingsController@updatePaymentOptions')->name('api.m1.updatePaymentOptions');
});



Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});


Route::group(['prefix' => 'common'], function () {
   Route::post('/uploadImage', 'ApiRoutes\M1\VenderController@uploadImage')->name('api.common.uploadImage');
   Route::get('/get-countries', 'ApiRoutes\Common\CountryController@getCountries')->name('api.common.getCountries');
   Route::get('/get-states', 'ApiRoutes\Common\CountryController@getStates')->name('api.common.getStates');
   Route::get('/get-cities', 'ApiRoutes\Common\CountryController@getCities')->name('api.common.getCities');

   Route::get('/get-amenities', 'ApiRoutes\Common\CountryController@getAmenties')->name('api.common.getAmenties');
   Route::get('/get-tags', 'ApiRoutes\Common\CountryController@getTags')->name('api.common.getTags');
   Route::get('/get-languages', 'ApiRoutes\Common\CommonController@getLanguages')->name('api.common.getLanguages');
   Route::get('/get-currencies', 'ApiRoutes\Common\CommonController@getCurrencies')->name('api.common.getCurrencies');
   Route::get('/get-payments', 'ApiRoutes\Common\CommonController@getPayments')->name('api.common.getPayments');
});