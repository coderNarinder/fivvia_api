<?php


 
Route::group(['prefix' => 'auth','middleware' => ['auth:sanctum']], function () {
   Route::get('/loginDetails', 'ApiRoutes\Auth\LoginController@loginDetails')->name('api.auth.loginDetails');
});



Route::group(['prefix' => 'auth'], function () {
   Route::post('/login', 'ApiRoutes\Auth\LoginController@getLoggedIn')->name('api.auth.login');
});

Route::group(['prefix' => 'm1','middleware' =>  ['auth:sanctum']], function () {
   Route::get('/vendors', 'ApiRoutes\M1\VenderController@getVendors')->name('api.m1.getVendors');
   Route::post('/vendors/create/new', 'ApiRoutes\M1\VenderController@createVendor')->name('api.m1.createVendor');
   Route::post('/vendor/{slug}/edit', 'ApiRoutes\M1\VenderController@editVendor')->name('api.m1.editVendor');

   Route::any('/vendors/delete', 'ApiRoutes\M1\VenderController@deleteVendor')->name('api.m1.deleteVendor');
   Route::any('/vendor/change/status', 'ApiRoutes\M1\VenderController@statusVendor')->name('api.m1.statusVendor');

   Route::get('/vendorDetail/{slug}', 'ApiRoutes\M1\VenderController@vendorDetail')->name('api.m1.vendorDetail');
   Route::get('/vendorStatusUpdate/{slug}', 'ApiRoutes\M1\VenderController@vendorStatusUpdate')->name('api.m1.vendorStatusUpdate');
   Route::get('/clientCategories', 'ApiRoutes\M1\BaseController@clientCategories')->name('api.m1.clientCategories');
   Route::get('/clientVendors', 'ApiRoutes\M1\BaseController@clientVendors')->name('api.m1.clientVendors');
   Route::get('/vendorCategories/{slug}', 'ApiRoutes\M1\VenderController@vendorCategories')->name('api.m1.vendorCategories');
   Route::post('/updateVendorCategories/{slug}', 'ApiRoutes\M1\VenderController@updateVendorCategories')->name('api.m1.updateVendorCategories');
   Route::get('/vendor-category-list/{slug}', 'ApiRoutes\M1\VenderController@getVendorCategories')->name('api.m1.getVendorCategories');
   Route::get('/vendor-products/{slug}', 'ApiRoutes\M1\ProductController@getVendorProducts')->name('api.m1.getVendorProducts');
   Route::get('/vendor/product/{slug}/detail', 'ApiRoutes\M1\ProductController@getProductDetails')->name('api.m1.getProductDetails');



    Route::post('/product/{slug}/update', 'ApiRoutes\M1\ProductController@getProductUpdate')->name('api.m1.getProductUpdate');
    Route::get('/products', 'ApiRoutes\M1\ProductController@getProducts')->name('api.m1.getProducts');
    Route::post('/product/{slug}/create', 'ApiRoutes\M1\ProductController@createProduct')->name('api.m1.createProduct');
    Route::get('/product/delete', 'ApiRoutes\M1\ProductController@deleteProduct')->name('api.m1.deleteProduct');
    Route::get('product/faqs/{slug}', 'ApiRoutes\M1\ProductController@faqs_listing')->name('api.mi.productFAQs');
    Route::get('product/faqs/{slug}/delete/{id}', 'ApiRoutes\M1\ProductController@deleteFaqs')->name('api.mi.deleteFaqs');

    Route::post('product/faqs/{slug}/add', 'ApiRoutes\M1\ProductController@faqStore')->name('api.mi.productFAQsAdd');
    Route::get('product/faqs/{slug}/edit/{id}', 'ApiRoutes\M1\ProductController@faqEdit')->name('api.mi.faqEdit');
    Route::post('product/faqs/{slug}/edit/{id}', 'ApiRoutes\M1\ProductController@faqUpdate')->name('api.mi.faqUpdate');

    Route::post('/settings/update', 'ApiRoutes\M1\SettingsController@updateSettings')->name('api.m1.updateSettings');


    Route::get('/settings/customization', 'ApiRoutes\M1\SettingsController@getCustomization')->name('api.m1.getCustomization'); 
    Route::get('/payments/options', 'ApiRoutes\M1\SettingsController@getPaymentOptions')->name('api.m1.getPaymentOptions'); 
    Route::post('settings/payments/update', 'ApiRoutes\M1\SettingsController@updatePaymentOptions')->name('api.m1.updatePaymentOptions');

    # BANNER
     Route::get('get-banners', 'ApiRoutes\M1\BannerController@getBanners')->name('api.m1.getBanners');
     Route::any('/banners/delete', 'ApiRoutes\M1\BannerController@deleteBanner')->name('api.m1.deleteBanner');
     Route::post('create-banners', 'ApiRoutes\M1\BannerController@store')->name('api.m1.getBannerCreate');
     Route::post('update-banner/{id}', 'ApiRoutes\M1\BannerController@update')->name('api.m1.getBannerUpdate');


      Route::get('get-topDestinations', 'ApiRoutes\M1\BannerController@topDestinations')->name('api.m1.topDestinations');
      Route::post('create-destination', 'ApiRoutes\M1\BannerController@storeDestinations')->name('api.m1.storeDestinations');
      Route::get('destination/delete', 'ApiRoutes\M1\BannerController@deleteDestinations')->name('api.m1.deleteDestinations');
      Route::post('update-destination/{id}', 'ApiRoutes\M1\BannerController@updateDestinations')->name('api.m1.updateDestinations');


      # BANNER
     Route::get('{id}/get-pickup-addresses', 'ApiRoutes\M1\AddressController@getAdresses')->name('api.m1.getAdresses');
     Route::post('{id}/save-pickup-address', 'ApiRoutes\M1\AddressController@storeAddress')->name('api.m1.storeAddress');
     Route::post('{id}/save-pickup-address/{address_id}', 'ApiRoutes\M1\AddressController@updateAddress')->name('api.m1.updateAddress');
    

});



Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});


Route::group(['prefix' => 'common/m1'], function () {
    Route::get('get-business-types', 'ApiRoutes\M1\BaseController@businessTypes')->name('api.m1.businessTypes');

});
Route::group(['prefix' => 'common'], function () {
   Route::post('/uploadImage', 'ApiRoutes\Common\CommonController@uploadImage')->name('api.common.uploadImage');
   Route::get('/get-countries', 'ApiRoutes\Common\CountryController@getCountries')->name('api.common.getCountries');
   Route::get('/get-states', 'ApiRoutes\Common\CountryController@getStates')->name('api.common.getStates');
   Route::get('/get-cities', 'ApiRoutes\Common\CountryController@getCities')->name('api.common.getCities');
   Route::get('/get-amenities', 'ApiRoutes\Common\CountryController@getAmenties')->name('api.common.getAmenties');
   Route::get('/get-tags', 'ApiRoutes\Common\CountryController@getTags')->name('api.common.getTags');
   Route::get('/get-languages', 'ApiRoutes\Common\CommonController@getLanguages')->name('api.common.getLanguages');
   Route::get('/get-currencies', 'ApiRoutes\Common\CommonController@getCurrencies')->name('api.common.getCurrencies');
   Route::get('/get-payments', 'ApiRoutes\Common\CommonController@getPayments')->name('api.common.getPayments');
   Route::get('get-banners', 'ApiRoutes\M1\BannerController@getBanners')->name('api.common.getBanners');
   Route::get('get-topDestinations', 'ApiRoutes\M1\BannerController@topDestinations')->name('api.common.topDestinations');
   Route::get('get-settings', 'ApiRoutes\M1\SettingsController@getSettings')->name('api.common.getSettings');
   Route::get('get-clientCategories', 'ApiRoutes\M1\SettingsController@clientCategory')->name('api.common.clientCategory');
 
   Route::get('/get-logistics', 'ApiRoutes\M1\SettingsController@logistics')->name('api.business.logistics'); 
 
   
});



Route::group(['prefix' => 'home'], function () {
   Route::get('/products', 'ApiRoutes\Home\ProductController@getTypeProducts')->name('api.home.getTypeProducts'); 
});