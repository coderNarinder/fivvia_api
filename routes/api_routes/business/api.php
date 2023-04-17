<?php

 


Route::post('/check/login', 'ApiRoutes\Business\LoginController@createToken')->name('api.business.checkBusinessLogin');

Route::group(['middleware' =>  ['auth:sanctum']], function () { 
  Route::post('/checkBusinesField', 'ApiRoutes\Business\RegisterController@checkBusinesField')->name('api.business.checkBusinesField');
  Route::get('/businesCategories', 'ApiRoutes\Business\LoginController@getBusinessCategories')->name('api.business.getBusinessCategories');
  Route::post('/businesCategories', 'ApiRoutes\Business\RegisterController@store_business_category')->name('api.business.getBusinessCategories');
  Route::get('/getBusinessTypes', 'ApiRoutes\Business\LoginController@getBusinessTypes')->name('api.business.getBusinessTypes');
  Route::post('/getBusinessTypes', 'ApiRoutes\Business\RegisterController@businessTypeStore')->name('api.business.getBusinessTypes');
  Route::post('/businessTypeStoreNew', 'ApiRoutes\Business\RegisterController@businessTypeStoreNew')->name('api.business.businessTypeStoreNew');





   Route::get('/businessDetails', 'ApiRoutes\Business\RegisterController@businessDetails')->name('api.business.businessDetails');
   Route::post('/businessDetails', 'ApiRoutes\Business\RegisterController@businessDetailStore')->name('api.business.businessDetails');
   
   Route::get('/packages', 'ApiRoutes\Business\RegisterController@packages')->name('api.business.packages');
   Route::post('/packages', 'ApiRoutes\Business\RegisterController@savePackage')->name('api.business.packages');

   Route::get('/getPaymentOptions', 'ApiRoutes\Business\RegisterController@getPaymentOptions')->name('api.business.getPaymentOptions');
   Route::post('/getPaymentOptions', 'ApiRoutes\Business\RegisterController@paymentOptionSaved')->name('api.business.getPaymentOptions');
 
   Route::get('/logistics', 'ApiRoutes\Business\RegisterController@logistics')->name('api.business.logistics');
   Route::post('/logistics', 'ApiRoutes\Business\RegisterController@logisticsSaved')->name('api.business.logistics');
 
   Route::get('/getDomains', 'ApiRoutes\Business\RegisterController@getClientDomians')->name('api.business.getClientDomians');
   Route::post('/getDomains', 'ApiRoutes\Business\RegisterController@saveDomains')->name('api.business.getClientDomians');
  
   Route::get('/getTemplates', 'ApiRoutes\Business\RegisterController@template')->name('api.business.getTemplates');
   Route::post('/getTemplates', 'ApiRoutes\Business\RegisterController@templateSave')->name('api.business.getTemplates');
 

   Route::get('/getMyCheckout', 'ApiRoutes\Business\RegisterController@checkout')->name('api.business.getMySubscription');
   Route::post('/getMyCheckout', 'ApiRoutes\Business\RegisterController@checkoutDuration')->name('api.business.checkoutDuration');
 
});


Route::group(['prefix' => 'auth','middleware' => ['auth:sanctum']], function () {
   Route::get('/loginDetails', 'ApiRoutes\Business\LoginController@loginDetails')->name('api.businss.auth.loginDetails');
});

