<?php
Route::group(['prefix' => '/godpanel'], function () {
    Route::get('login', function(){return view('godpanel/login');})->name('god.login');
    Route::post('login','Godpanel\LoginController@Login')->name('god.login');
    Route::middleware(['middleware' => 'auth:admin'])->group(function () {

		# CATEGORY MANAGEMENT
		Route::get('category',[App\Http\Controllers\Godpanel\CategoryController::class,'index'] )->name('godpanel.category.index');
		Route::get('category/create',[App\Http\Controllers\Godpanel\CategoryController::class,'create'] )->name('godpanel.category.create');
		Route::post('category/create',[App\Http\Controllers\Godpanel\CategoryController::class,'store'] )->name('godpanel.category.store');
		Route::get('category/order',[App\Http\Controllers\Godpanel\CategoryController::class,'updateOrder'] )->name('godpanel.category.order');
		Route::get('category/{id}/edit',[App\Http\Controllers\Godpanel\CategoryController::class,'edit'] )->name('godpanel.category.edit');
		Route::put('category/{id}/edit',[App\Http\Controllers\Godpanel\CategoryController::class,'update'] )->name('godpanel.category.update');
		Route::get('category/{id}/delete',[App\Http\Controllers\Godpanel\CategoryController::class,'destroy'] )->name('godpanel.category.delete');
		# CATEGORY MANAGEMENT

		# VARIANT MANGEMENT
		Route::get('variants',[App\Http\Controllers\Godpanel\VariantController::class,'index'] )->name('godpanel.variant.index');
		Route::get('variants/create',[App\Http\Controllers\Godpanel\VariantController::class,'create'] )->name('godpanel.variant.create');
		Route::post('variants/store',[App\Http\Controllers\Godpanel\VariantController::class,'store'] )->name('godpanel.variant.store');
		Route::delete('variants/{id}/delete',[App\Http\Controllers\Godpanel\VariantController::class,'destroy'] )->name('godpanel.variant.destroy');
		Route::get('variants/{id}/edit',[App\Http\Controllers\Godpanel\VariantController::class,'edit'] )->name('godpanel.variant.edit');
		Route::put('variants/{id}/edit',[App\Http\Controllers\Godpanel\VariantController::class,'update'] )->name('godpanel.variant.update');
		Route::post('variants/order',[App\Http\Controllers\Godpanel\VariantController::class,'updateOrders'] )->name('godpanel.variant.order');
		# VARIANT MANAGEMENT


		# BRAND MANGEMENT
		Route::get('brands',[App\Http\Controllers\Godpanel\BrandController::class,'index'] )->name('godpanel.brand.index');
		Route::get('brands/create',[App\Http\Controllers\Godpanel\BrandController::class,'create'] )->name('godpanel.brand.create');
		Route::post('brands/store',[App\Http\Controllers\Godpanel\BrandController::class,'store'] )->name('godpanel.brand.store');
		Route::delete('brands/{id}/delete',[App\Http\Controllers\Godpanel\BrandController::class,'destroy'] )->name('godpanel.brand.destroy');
		Route::get('brands/{id}/edit',[App\Http\Controllers\Godpanel\BrandController::class,'edit'] )->name('godpanel.brand.edit');
		Route::put('brands/{id}/edit',[App\Http\Controllers\Godpanel\BrandController::class,'update'] )->name('godpanel.brand.update');
		Route::post('brands/order',[App\Http\Controllers\Godpanel\BrandController::class,'updateOrders'] )->name('godpanel.brand.order');
		# BRAND MANAGEMENT


		# CATEGORY MANAGEMENT
		Route::get('country',[App\Http\Controllers\Godpanel\CountryController::class,'index'] )->name('godpanel.country.index');
		Route::get('country/create',[App\Http\Controllers\Godpanel\CountryController::class,'create'] )->name('godpanel.country.create');
		Route::post('country/create',[App\Http\Controllers\Godpanel\CountryController::class,'store'] )->name('godpanel.country.store');

		Route::get('country/{id}/settings',[App\Http\Controllers\Godpanel\CountryController::class,'settings'] )->name('godpanel.country.settings');

        
        Route::post('country/{id}/settings',[App\Http\Controllers\Godpanel\CountryController::class,'settingsSaving'])->name('godpanel.country.settings');
 



		Route::get('country/order',[App\Http\Controllers\Godpanel\CountryController::class,'updateOrder'] )->name('godpanel.country.order');
		Route::get('country/{id}/edit',[App\Http\Controllers\Godpanel\CountryController::class,'edit'] )->name('godpanel.country.edit');
		Route::post('country/{id}/edit',[App\Http\Controllers\Godpanel\CountryController::class,'update'] )->name('godpanel.country.update');
		Route::delete('country/{id}/delete',[App\Http\Controllers\Godpanel\CountryController::class,'destroy'] )->name('godpanel.country.delete');
		# CATEGORY MANAGEMENT

		# CATEGORY MANAGEMENT
		Route::get('get-states-ajax',[App\Http\Controllers\Godpanel\StateController::class,'ajax'] )->name('godpanel.state.ajax');
		Route::get('states',[App\Http\Controllers\Godpanel\StateController::class,'index'] )->name('godpanel.state.index');
		Route::get('states/create',[App\Http\Controllers\Godpanel\StateController::class,'create'] )->name('godpanel.state.create');
		Route::post('states/create',[App\Http\Controllers\Godpanel\StateController::class,'store'] )->name('godpanel.state.store');
		Route::get('states/order',[App\Http\Controllers\Godpanel\StateController::class,'updateOrder'] )->name('godpanel.state.order');
		Route::get('states/{id}/edit',[App\Http\Controllers\Godpanel\StateController::class,'edit'] )->name('godpanel.state.edit');
		Route::post('states/{id}/edit',[App\Http\Controllers\Godpanel\StateController::class,'update'] )->name('godpanel.state.update');
		Route::delete('states/{id}/delete',[App\Http\Controllers\Godpanel\StateController::class,'destroy'] )->name('godpanel.state.delete');
		# CATEGORY MANAGEMENT


		# CATEGORY MANAGEMENT
		Route::get('cities',[App\Http\Controllers\Godpanel\CityController::class,'index'] )->name('godpanel.city.index');
		Route::get('cities/create',[App\Http\Controllers\Godpanel\CityController::class,'create'] )->name('godpanel.city.create');
		Route::post('cities/create',[App\Http\Controllers\Godpanel\CityController::class,'store'] )->name('godpanel.city.store');
		Route::get('cities/order',[App\Http\Controllers\Godpanel\CityController::class,'updateOrder'] )->name('godpanel.city.order');
		Route::get('cities/{id}/edit',[App\Http\Controllers\Godpanel\CityController::class,'edit'] )->name('godpanel.city.edit');
		Route::post('cities/{id}/edit',[App\Http\Controllers\Godpanel\CityController::class,'update'] )->name('godpanel.city.update');
		Route::delete('cities/{id}/delete',[App\Http\Controllers\Godpanel\CityController::class,'destroy'] )->name('godpanel.city.delete');
		# CATEGORY cities


		# CATEGORY MANAGEMENT
		Route::get('business-category',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'index'] )->name('godpanel.businessCategory.index');
		Route::get('business-category/create',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'create'] )->name('godpanel.businessCategory.create');
		Route::post('business-category/create',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'store'] )->name('godpanel.businessCategory.store');
		Route::get('business-category/order',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'updateOrder'] )->name('godpanel.businessCategory.order');
		Route::get('business-category/{id}/edit',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'edit'] )->name('godpanel.businessCategory.edit');
		Route::post('business-category/{id}/edit',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'update'] )->name('godpanel.businessCategory.update');
		Route::delete('business-category/{id}/delete',[App\Http\Controllers\Godpanel\BusinessCategoryController::class,'destroy'] )->name('godpanel.businessCategory.delete');
		# CATEGORY cities


		# CATEGORY MANAGEMENT
		Route::get('shipping-types',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'index'] )->name('godpanel.ShippingType.index');
		Route::get('shipping-types/create',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'create'] )->name('godpanel.ShippingType.create');
		Route::post('shipping-types/create',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'store'] )->name('godpanel.ShippingType.store');
		Route::get('shipping-types/order',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'updateOrder'] )->name('godpanel.ShippingType.order');
		Route::get('shipping-types/{id}/edit',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'edit'] )->name('godpanel.ShippingType.edit');
		Route::post('shipping-types/{id}/edit',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'update'] )->name('godpanel.ShippingType.update');
		Route::delete('shipping-types/{id}/delete',[App\Http\Controllers\Godpanel\ShippingTypeController::class,'destroy'] )->name('godpanel.ShippingType.delete');
		# CATEGORY cities

		# CATEGORY MANAGEMENT
		Route::get('payment-options',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'index'] )->name('godpanel.PaymentOption.index');
		Route::get('payment-options/create',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'create'] )->name('godpanel.PaymentOption.create');
		Route::post('payment-options/create',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'store'] )->name('godpanel.PaymentOption.store');
		Route::get('payment-options/order',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'updateOrder'] )->name('godpanel.PaymentOption.order');
		Route::get('payment-options/{id}/edit',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'edit'] )->name('godpanel.PaymentOption.edit');
		Route::post('payment-options/{id}/edit',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'update'] )->name('godpanel.PaymentOption.update');
		Route::delete('payment-options/{id}/delete',[App\Http\Controllers\Godpanel\PaymentOptionController::class,'destroy'] )->name('godpanel.PaymentOption.delete');
		# CATEGORY cities


		# CATEGORY MANAGEMENT
		Route::get('business-type',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'index'] )->name('godpanel.businessType.index');
		Route::get('business-type/create',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'create'] )->name('godpanel.businessType.create');
		Route::post('business-type/create',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'store'] )->name('godpanel.businessType.store');
		Route::get('business-type/order',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'updateOrder'] )->name('godpanel.businessType.order');
		Route::get('business-type/{id}/edit',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'edit'] )->name('godpanel.businessType.edit');
		Route::post('business-type/{id}/edit',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'update'] )->name('godpanel.businessType.update');
		Route::delete('business-type/{id}/delete',[App\Http\Controllers\Godpanel\BusinessTypeController::class,'destroy'] )->name('godpanel.businessType.delete');
		# CATEGORY cities



		# CATEGORY MANAGEMENT
		Route::get('package-type',[App\Http\Controllers\Godpanel\PackageController::class,'index'] )->name('godpanel.packages.index');
		Route::get('package-type/create',[App\Http\Controllers\Godpanel\PackageController::class,'create'] )->name('godpanel.packages.create');
		Route::post('package-type/create',[App\Http\Controllers\Godpanel\PackageController::class,'store'] )->name('godpanel.packages.store');
		Route::get('package-type/order',[App\Http\Controllers\Godpanel\PackageController::class,'updateOrder'] )->name('godpanel.packages.order');
		Route::get('package-type/{id}/edit',[App\Http\Controllers\Godpanel\PackageController::class,'edit'] )->name('godpanel.packages.edit');
		Route::post('package-type/{id}/edit',[App\Http\Controllers\Godpanel\PackageController::class,'update'] )->name('godpanel.packages.update');
		Route::delete('package-type/{id}/delete',[App\Http\Controllers\Godpanel\PackageController::class,'destroy'] )->name('godpanel.packages.delete');
		# CATEGORY cities



		// Route::resource('variant', 'Client\VariantController');
		Route::post('variant/order', 'Client\VariantController@updateOrders')->name('variant.order');
		// Route::get('variant/cate/{cid}', 'Client\VariantController@variantbyCategory');

		Route::get('variant/list',[App\Http\Controllers\Client\VariantController::class,'index'])->name('variant.index');



		Route::resource('client','Godpanel\ClientController');
		Route::get('client/business-types/ajax','Godpanel\ClientController@getBusinessTypes')->name('godpanel.getBusinessTypes');

		Route::post('client/{id}/update-logistics','Godpanel\ClientController@updateLogistics')->name('godpanel.client.logistics.update');
		Route::post('client/{id}/update-template','Godpanel\ClientController@templateSave')->name('godpanel.client.template.update');
		Route::post('client/{id}/update-payment','Godpanel\ClientController@paymentOptionSaved')->name('godpanel.client.payment.update');





		Route::resource('map','Godpanel\MapProviderController');
		Route::resource('sms','Godpanel\SmsProviderController');
		Route::resource('language','Godpanel\LanguageController');
		Route::resource('currency','Godpanel\CurrencyController');
		Route::post('delete/client/{id}', 'Godpanel\ClientController@remove');
		Route::get('map/destroy/{id}', 'Godpanel\MapProviderController@destroy');
		Route::get('sms/destroy/{id}', 'Godpanel\SmsProviderController@destroy');
		Route::post('/logout', 'Godpanel\LoginController@logout')->name('god.logout');
		Route::get('dashboard','Godpanel\DashBoardController@index')->name('god.dashboard');
		Route::post('migrateDefaultData/{id}', 'Godpanel\ClientController@migrateDefaultData')->name('client.migrateDefaultData');
		Route::post('singleVendorSetting/{id}', 'Godpanel\ClientController@singleVendorSetting')->name('client.update_single_vendor');
		Route::get('exportDb/{dbname}', 'Godpanel\ClientController@exportDb')->name('client.exportdb');
		
	});
});