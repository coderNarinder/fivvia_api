<?php
 Route::group(['prefix' => 'admin'], function () {
  Route::post('/check/login', 'ApiRoutes\Admin\LoginController@getLoggedIn')->name('api.admin.Login');

  Route::group(['middleware' =>  ['auth:sanctum','ApiAdminAuth']], function () { 



Route::get('/check/login', 'ApiRoutes\Admin\LoginController@loginDetails')->name('api.admin.loginDetails');
Route::get('/clients', 'ApiRoutes\Admin\ClientController@index')->name('api.admin.clients');
Route::get('/clients/{id}/details', 'ApiRoutes\Admin\ClientController@clientDetails')->name('api.admin.clientDetails');
Route::post('/clients/{id}/details', 'ApiRoutes\Admin\ClientController@updateClient')->name('api.admin.clientDetails');
Route::get('/get-business-types', 'ApiRoutes\Admin\ClientController@businessTypes')->name('api.admin.businessTypes');
      
#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-business-categories', 'ApiRoutes\Admin\BusinessCategoryController@list')->name('api.admin.businessCategories');
Route::post('/saveBusinessCategory', 'ApiRoutes\Admin\BusinessCategoryController@businessCategoryStore')->name('api.admin.businessCategoryStore');
Route::get('/updateBusinessCategory/{id}', 'ApiRoutes\Admin\BusinessCategoryController@updateBusinessCategory')->name('api.admin.updateBusinessCategory');
Route::post('/updateBusinessCategory/{id}', 'ApiRoutes\Admin\BusinessCategoryController@businessCategoryUpdate')->name('api.admin.updateBusinessCategory');

#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-countries', 'ApiRoutes\Admin\CountryController@list')->name('api.admin.country');
Route::post('/saveCountry', 'ApiRoutes\Admin\CountryController@store')->name('api.admin.CountryStore');
Route::get('/updateCountry/{id}', 'ApiRoutes\Admin\CountryController@edit')->name('api.admin.updateCountry');
Route::post('/updateCountry/{id}', 'ApiRoutes\Admin\CountryController@update')->name('api.admin.updateCountry');

Route::get('/settingsCountry/{id}', 'ApiRoutes\Admin\CountryController@getSettings')->name('api.admin.settingsCountry');
Route::post('/settingsCountry/{id}', 'ApiRoutes\Admin\CountryController@storeSettings')->name('api.admin.settingsCountry');


#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-states', 'ApiRoutes\Admin\StateController@list')->name('api.admin.state');
Route::post('/saveState', 'ApiRoutes\Admin\StateController@store')->name('api.admin.stateStore');
Route::get('/updateState/{id}', 'ApiRoutes\Admin\StateController@edit')->name('api.admin.updateState');
Route::post('/updateState/{id}', 'ApiRoutes\Admin\StateController@update')->name('api.admin.updateState');


#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-cities', 'ApiRoutes\Admin\CityController@list')->name('api.admin.cities');
Route::post('/saveCity', 'ApiRoutes\Admin\CityController@store')->name('api.admin.CityStore');
Route::get('/updateCity/{id}', 'ApiRoutes\Admin\CityController@edit')->name('api.admin.updateCity');
Route::post('/updateCity/{id}', 'ApiRoutes\Admin\CityController@update')->name('api.admin.updateCity');


#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-languages', 'ApiRoutes\Admin\LanguageController@list')->name('api.admin.languages');
Route::post('/saveLanguage', 'ApiRoutes\Admin\LanguageController@store')->name('api.admin.LanguageStore');
Route::get('/updateLanguage/{id}', 'ApiRoutes\Admin\LanguageController@edit')->name('api.admin.updateLanguage');
Route::post('/updateLanguage/{id}', 'ApiRoutes\Admin\LanguageController@update')->name('api.admin.updateLanguage');

#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-currencies', 'ApiRoutes\Admin\CurrencyController@list')->name('api.admin.currencies');
Route::post('/saveCurrency', 'ApiRoutes\Admin\CurrencyController@store')->name('api.admin.CurrencyStore');
Route::get('/updateCurrency/{id}', 'ApiRoutes\Admin\CurrencyController@edit')->name('api.admin.updateCurrency');
Route::post('/updateCurrency/{id}', 'ApiRoutes\Admin\CurrencyController@update')->name('api.admin.updateCurrency');



#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-shipping', 'ApiRoutes\Admin\ShippingController@list')->name('api.admin.shipping');
Route::post('/saveShipping', 'ApiRoutes\Admin\ShippingController@store')->name('api.admin.ShippingStore');
Route::get('/updateShipping/{id}', 'ApiRoutes\Admin\ShippingController@edit')->name('api.admin.updateShipping');
Route::post('/updateShipping/{id}', 'ApiRoutes\Admin\ShippingController@update')->name('api.admin.updateShipping');


#---------------------------------------------------------------------------------------------------------------------------------
# Business Categories
#---------------------------------------------------------------------------------------------------------------------------------
Route::get('/get-payments', 'ApiRoutes\Admin\PaymentController@list')->name('api.admin.Payment');
Route::post('/savePayment', 'ApiRoutes\Admin\PaymentController@store')->name('api.admin.PaymentStore');
Route::get('/updatePayment/{id}', 'ApiRoutes\Admin\PaymentController@edit')->name('api.admin.updatePayment');
Route::post('/updatePayment/{id}', 'ApiRoutes\Admin\PaymentController@update')->name('api.admin.updatePayment');


  });
});

?>