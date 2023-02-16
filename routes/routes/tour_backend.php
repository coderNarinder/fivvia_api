<?php




Route::get('admin/login', 'Tours\Backend\LoginController@login')->name('admin.login');
Route::get('set-theme-mode', 'Tours\Backend\DashboardController@themeMode')->name('admin.themeMode');
// Route::get('file-download/{filename}', [DownloadFileController::class, 'index'])->name('file.download.index');
 Route::post('admin/login', 'Tours\Backend\LoginController@clientLogin')->name('client.login');
 Route::get('admin/logout', 'Tours\Backend\LoginController@logout')->name('client.logout');
// Route::get('admin/wrong/url', 'Auth\LoginController@wrongurl')->name('wrong.client');




// ADMIN LANGUAGE SWITCH
Route::group(['middleware' => 'adminLanguageSwitch'], function () {
    Route::group(['middleware' => ['ClientAuth'], 'prefix' => '/client'], function () {
         Route::get('/dashboard', 'Tours\Backend\DashboardController@dashboard')->name('client.dashboard');

         # VENDOR LISTING
         Route::get('/vendors/listing', 'Tours\Backend\VendorController@list')->name('client.vendor.list');
         Route::get('/vendors/records/ajax', 'Tours\Backend\VendorController@ajax')->name('client.vendor.records.ajax');
         Route::get('/vendors/create', 'Tours\Backend\VendorController@list')->name('client.vendor.create');
         Route::get('/vendors/{slug}/edit', 'Tours\Backend\VendorController@edit')->name('client.vendor.edit');
         Route::get('/vendors/{slug}/view', 'Tours\Backend\VendorController@view')->name('client.vendor.view');


         Route::get('/vendors/{slug}/status', 'Tours\Backend\VendorController@status')->name('client.vendor.status');
         Route::get('/vendors/product/{slug}/add', 'Tours\Backend\ProductController@addProduct')->name('client.vendor.product.create'); 
         Route::post('/vendors/product/{slug}/add', 'Tours\Backend\ProductController@addProduct')->name('client.vendor.product.create'); 
         
    });
});
