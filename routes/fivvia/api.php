<?php



Route::get('/business-categories', 'Api\Fivvia\FilterDataController@getBusinessCategory')->name('api.getBusinessCategory');



Route::get('/getProducts/{id}', 'Api\Fivvia\FilterDataController@getProducts')->name('api.getProducts');

?>