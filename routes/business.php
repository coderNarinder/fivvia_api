<?php
# THIS FILE HAS BUSINESS RELATED ROUTES THAT ARE USED TO REGISTER A NEW BUSINESS
 
// Route::get('business-register',[RegisterController::class,'index'])->name('business.register');

Route::get('business-register','\App\Http\Controllers\Business\RegisterController@register')->name('business.register');
Route::get('home','\App\Http\Controllers\Business\RegisterController@index')->name('business.home');
Route::get('business-logout','\App\Http\Controllers\Business\RegisterController@logout')->name('business.logout');


Route::any('check/business/login','\App\Http\Controllers\Business\RegisterController@createToken')->name('business.check.token'); 


Route::group(['middleware' => ['CheckBusinessSteps'], 'prefix' => '/business'], function () {
     Route::get('/choose-business-category','\App\Http\Controllers\Business\DashboardController@business_category')
     ->name('business.business_category');
     Route::post('/choose-business-category','\App\Http\Controllers\Business\DashboardController@store_business_category')->name('business.business_category');

     Route::get('/choose-business-type','\App\Http\Controllers\Business\DashboardController@businessType')->name('business.business_type');
     Route::post('/choose-business-type','\App\Http\Controllers\Business\DashboardController@businessTypeStore')->name('business.business_type');

      Route::post('/save-business-type','\App\Http\Controllers\Business\DashboardController@businessTypeStoreNew')->name('business.business_type_new');
     Route::get('/choose-business-detail','\App\Http\Controllers\Business\DashboardController@businessDetail')->name('business.business_details');
     Route::post('/choose-business-detail','\App\Http\Controllers\Business\DashboardController@businessDetailStore')->name('business.business_details');




     Route::get('/choose-subscription-plan','\App\Http\Controllers\Business\DashboardController@businessPlan')->name('business.businessPlan');
     

     
     
     Route::post('business-check-client','\App\Http\Controllers\Business\DashboardController@checkBusinesField')->name('business.check.client');



     Route::get('/choose-logistics','\App\Http\Controllers\Business\DashboardController@logistics')
     ->name('business.logistics');
      Route::post('/choose-logistics','\App\Http\Controllers\Business\DashboardController@logisticsSaved')
     ->name('business.logistics');

     Route::get('/bank-account-details','\App\Http\Controllers\Business\DashboardController@bankDetails')
     ->name('business.bankDetails');

     Route::get('/choose-payment-options','\App\Http\Controllers\Business\DashboardController@paymentOptions')
     ->name('business.paymentOptions');
     Route::post('/choose-payment-options','\App\Http\Controllers\Business\DashboardController@paymentOptionSaved')
     ->name('business.paymentOptions');

   
     Route::get('/choose-domains','\App\Http\Controllers\Business\DashboardController@domains')
     ->name('business.domains');
     Route::post('/choose-domains','\App\Http\Controllers\Business\DashboardController@domainsSave')
     ->name('business.domains');

   
    

     Route::get('/choose-template','\App\Http\Controllers\Business\DashboardController@template')
                  ->name('business.template');
     Route::post('/choose-template','\App\Http\Controllers\Business\DashboardController@templateSave')
                 ->name('business.template');

     
      Route::get('/checkout','\App\Http\Controllers\Business\DashboardController@checkout')
                  ->name('business.checkout');

     Route::get('/checkout/back','\App\Http\Controllers\Business\DashboardController@checkoutBack')
                  ->name('business.checkout.back');
     
     Route::any('/checkout/change/package/{id}/duration','\App\Http\Controllers\Business\DashboardController@checkoutDuration')
                  ->name('business.checkout.duration');

     Route::post('/checkout','\App\Http\Controllers\Business\DashboardController@checkoutSave')
                 ->name('business.checkout');

   
});




Route::group(['middleware' => ['BusinessAuth'], 'prefix' => '/business'], function () {
     
     Route::get('/dashboard','\App\Http\Controllers\Business\DashboardController@index')->name('business.dashboard');
});



?>