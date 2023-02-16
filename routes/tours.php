<?php





Route::get('/', 'Tours\HomeController@index')->name('home.index');
Route::get('category/{slug?}', 'Tours\CategoryController@categoryProduct')->name('categoryDetail');
Route::get('page/{slug}', 'Front\UserhomeController@getExtraPage')->name('extrapage');
Route::match(['get','post'],'vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');

?>