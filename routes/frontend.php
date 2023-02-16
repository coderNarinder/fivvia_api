<?php

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/debug-sentry', function () {
	throw new Exception('My first Sentry error!');
});



Route::group(['middleware' => ['domain']], function () {

	Route::get('/home2', 'Front\UserhomeController@index')->name('userHome2');
	Route::get('/', 'Front\UserhomeController@index')->name('userHome');
    Route::get('category/{slug?}', 'Front\CategoryController@categoryProduct')->name('categoryDetail');
	Route::match(['get','post'],'vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');
	Route::get('cartProducts', 'Front\CartController@getCartData')->name('getCartProducts');
	Route::get('{vendor?}/product/{id?}', 'Front\ProductController@index')->name('productDetail');
    Route::get('viewcart', 'Front\CartController@showCart')->name('showCart');

   #----------------------------------------------------------------------------------------------------------------
 
	Route::any('webhook/lalamove', 'Front\LalaMovesController@webhooks')
	->name('webhook');
	Route::get('dispatch-order-status-update/{id?}', 'Front\DispatcherController@dispatchOrderStatusUpdate')
	->name('dispatch-order-update'); // Order Status update Dispatch
	Route::get('dispatch-pickup-delivery/{id?}', 'Front\DispatcherController@dispatchPickupDeliveryUpdate')
	->name('dispatch-pickup-delivery'); // pickup delivery update from dispatch


	Route::get('dispatch-order-status-update-details/{id?}', 'Front\DispatcherController@dispatchOrderDetails')
	->name('dispatch-order-update-details'); // Order Status update Dispatch details

    Route::get('testsms', 'Front\FrontController@testsms');

    Route::get('demo', 'Front\CustomerAuthController@getTestHtmlPage');
	Route::get('cabbooking', 'Front\CustomerAuthController@getTestHtmlPage');
	Route::get('demo/cabBooking', 'Front\CustomerAuthController@getDemoCabBookingPage');
	Route::get('fcm', 'Front\CustomerAuthController@fcm');
	Route::get('send-notification', 'Front\CustomerAuthController@sendNotification');
    Route::get('vendor-notification', 'Front\DispatcherController@test');
	Route::get('test/email', function () {
		$send_mail = 'test@yopmail.com';
		// App\Jobs\SendRefferalCodeEmailJob::dispatch($send_mail);
		dispatch(new App\Jobs\SendRefferalCodeEmailJob($send_mail));

		dd('send mail successfully !!');
	});


	// Start edit order routes
	Route::post('edit-order/search/vendor/products', 'Front\TempCartController@vendorProductsSearchResults');
	Route::post('edit-order/vendor/products/getProductsInCart', 'Front\TempCartController@getProductsInCart');
	Route::post('edit-order/temp-cart/product/add', 'Front\TempCartController@postAddToTempCart');
	Route::post('edit-order/temp-cart/product/updateQuantity', 'Front\TempCartController@updateQuantity');
	Route::post('edit-order/temp-cart/product/remove', 'Front\TempCartController@removeItem');
	Route::post('edit-order/temp-cart/remove', 'Front\TempCartController@emptyCartData');
	Route::post('edit-order/temp-cart/submit', 'Front\TempCartController@submitCart');
	Route::post('edit-order/vendor/product/{id}', 'Front\TempCartController@getProductById');
	// End edit order routes



	Route::get('payment/gateway/returnResponse', 'Front\PaymentController@getGatewayReturnResponse')->name('payment.gateway.return.response');

	//lalMoves Test Route
	Route::match(['get','post'],'order/lalamoves/quotation','Front\LalaMovesController@quotation')->name('order.lalamoves.quotation');

	Route::match(['get','post'],'order/lalamoves/place-order','Front\LalaMovesController@placeOrder')->name('order.lalamoves.place_order');



	////check Shiprocket
	Route::get('carrier/test/shiprocket','ShiprocketController@checkShiprocket')->name('carrier.test.shiprocket');
	Route::post('shiprocket_webhook','ShiprocketController@shiprocketWebhook')->name('carrier.webhook.shiprocket');


	// Stripe
	Route::post('payment/stripe', 'Front\StripeGatewayController@postPaymentViaStripe')->name('payment.stripe');
	Route::post('user/subscription/payment/stripe', 'Front\StripeGatewayController@subscriptionPaymentViaStripe')->name('user.subscription.payment.stripe');

	// Paypal
	Route::post('payment/paypal', 'Front\PaypalGatewayController@paypalPurchase')->name('payment.paypalPurchase');
	Route::get('payment/paypal/CompletePurchase', 'Front\PaypalGatewayController@paypalCompletePurchase')->name('payment.paypalCompletePurchase');
	# for App side paypal payment
	Route::get('payment/paypal/completeCheckout/{token?}/{action?}/{address?}', 'Front\PaymentController@paypalCompleteCheckout')->name('payment.paypalCompleteCheckout');
	Route::get('payment/checkoutSuccess/{id}', 'Front\PaymentController@getCheckoutSuccess')->name('payment.getCheckoutSuccess');

	// Paystack
	Route::post('payment/paystack', 'Front\PaystackGatewayController@paystackPurchase')->name('payment.paystackPurchase');
	Route::post('payment/paystack/completePurchase', 'Front\PaystackGatewayController@paystackCompletePurchase')->name('payment.paystackCompletePurchase');
	Route::get('payment/paystack/completePurchase/app', 'Front\PaystackGatewayController@paystackCompletePurchaseApp')->name('payment.paystackCompletePurchaseApp');
	Route::get('payment/paystack/cancelPurchase/app', 'Front\PaystackGatewayController@paystackCancelPurchaseApp')->name('payment.paystackCancelPurchaseApp');

	// Payfast
	Route::post('payment/payfast', 'Front\PayfastGatewayController@payfastPurchase')->name('payment.payfastPurchase');
	Route::post('payment/payfast/notify', 'Front\PayfastGatewayController@payfastNotify')->name('payment.payfastNotify');
	Route::post('payment/payfast/notify/app', 'Front\PayfastGatewayController@payfastNotifyApp')->name('payment.payfastNotifyApp');
	Route::post('payment/payfast/completePurchase', 'Front\PayfastGatewayController@payfastCompletePurchase')->name('payment.payfastCompletePurchase');

	// Mobbex
	Route::post('payment/mobbex', 'Front\MobbexGatewayController@mobbexPurchase')->name('payment.mobbexPurchase');
	Route::post('payment/mobbex/notify', 'Front\MobbexGatewayController@mobbexNotify')->name('payment.mobbexNotify');


	//GCash
	Route::post('payment/gcash','Front\GCashController@beforePayment')->name('payment.gcash.beforePayment');
	Route::get('payment/gcash/view','Front\GCashController@webView')->name('payment.gcash.webView');


	//Simplify
	Route::match(['get','post'],'payment/simplify/page','Front\SimplifyController@beforePayment')->name('payment.simplify.beforePayment');
	Route::post('payment/simplify','Front\SimplifyController@createPayment')->name('payment.simplify.createPayment');


	//Square
	Route::match(['get','post'],'payment/square/page','Front\SquareController@beforePayment')->name('payment.square.beforePayment');
	Route::post('payment/square','Front\SquareController@createPayment')->name('payment.square.createPayment');

	//Ozow
	Route::match(['get','post'],'payment/ozow/page','Front\OzowController@beforePayment')->name('payment.ozow.beforePayment');
	Route::post('payment/ozow','Front\OzowController@createPayment')->name('payment.ozow.createPayment');

	//Pagarme
	Route::match(['get','post'],'payment/pagarme/page','Front\PagarmeController@beforePayment')->name('payment.pagarme.beforePayment');
	Route::post('payment/pagarme','Front\PagarmeController@createPayment')->name('payment.pagarme.createPayment');
	Route::post('payment/pagarme/card','Front\PagarmeController@createPaymentCard')->name('payment.pagarme.createPaymentCard');

	// Checkout
	Route::post('payment/checkout', 'Front\CheckoutGatewayController@checkoutPurchase')->name('payment.checkoutPurchase');
	Route::post('payment/checkout/notify', 'Front\CheckoutGatewayController@checkoutNotify')->name('payment.checkoutNotify');



	//Route::get('payment/yoco-webview', 'Api\v1\YocoGatewayController@yocoWebView')->name('payment.yoco-webview');
	Route::post('payment/yoco', 'Front\YocoGatewayController@yocoPurchase')->name('payment.yocoPurchase');
	Route::post('payment/yoco/app', 'Front\YocoGatewayController@yocoPurchaseApp')->name('payment.yocoPurchaseApp');
	Route::get('/payment/yoco-webview', function(){
		return View::make('frontend.yoco_webview');
	 });

	Route::post('payment/paylink', 'Front\PaylinkGatewayController@paylinkPurchase')->name('payment.paylinkPurchase');
	Route::get('payment/paylink/return', 'Front\PaylinkGatewayController@paylinkReturn')->name('payment.paylinkReturn');
	Route::get('payment/paylink/return/app', 'Front\PaylinkGatewayController@paylinkReturnApp')->name('payment.paylinkReturnApp');
	// Route::post('payment/paylink/notify', 'Front\PaylinkGatewayController@paylinkNotify')->name('payment.paylinkNotify');

	Route::post('payment/razorpay', 'Front\RazorpayGatewayController@razorpayPurchase')->name('payment.razorpayPurchase');
	// Route::get("/payment/razorpay/view", function(){
	// 	return View::make("frontend.razorpay_view");
	//  })->name('razorpay.view');
	Route::post('payment/razorpay/pay', 'Front\RazorpayGatewayController@razorpayCompletePurchase')->name('payment.razorpayCompletePurchase');
	Route::get('payment/razorpay/notify', 'Front\RazorpayGatewayController@razorpayNotify')->name('payment.razorpayNotify');

	Route::post('payment/user/placeorder', 'Front\OrderController@postPaymentPlaceOrder')->name('user.postPaymentPlaceOrder');
	Route::post('payment/user/wallet/credit', 'Front\WalletController@postPaymentCreditWallet')->name('user.postPaymentCreditWallet');

	Route::get('user/login', [
		'as' => 'customer.login',
		'uses' => 'Front\CustomerAuthController@loginForm'
	]);
	Route::get('user/register', [
		'as' => 'customer.register',
		'uses' => 'Front\CustomerAuthController@registerForm'
	]);
	Route::get('user/forgotPassword', [
		'as' => 'customer.forgotPassword',
		'uses' => 'Front\ForgotPasswordController@getForgotPasswordForm'
	]);
	Route::get('user/resetPassword', [
		'as' => 'customer.resetPassword',
		'uses' => 'Front\CustomerAuthController@resetPasswordForm'
	]);
	Route::get('/autocomplete-search', 'Front\SearchController@postAutocompleteSearch')->name('autocomplete');
	Route::get('/search-all/{keyword}', 'Front\SearchController@showSearchResults')->name('showSearchResults');
	
	Route::get('/homeTemplateOne', 'Front\UserhomeController@indexTemplateOne')->name('indexTemplateOne');
	//Route::get('page/driver-registration', 'Front\UserhomeController@driverSignup')->name('page/driver-registration');
	Route::post('page/driverSignup', 'Front\OrderController@driverSignup')->name('page.driverSignup');
	Route::get('driver-documents', 'Front\UserhomeController@driverDocuments')->name('driver-documents');
	Route::get('page/{slug}', 'Front\UserhomeController@getExtraPage')->name('extrapage');
	Route::get('vendor-registration', 'Front\UserhomeController@vendorRegistration')->name('vendorRegistration');
	 
	Route::post('/homePageData', 'Front\UserhomeController@postHomePageData')->name('homePageData');
	Route::post('/homePageDataCategoryMenu', 'Front\UserhomeController@homePageDataCategoryMenu')->name('homePageDataCategoryMenu');
	Route::post('/theme', 'Front\UserhomeController@setTheme')->name('config.update');
	Route::get('/getConfig', 'Front\UserhomeController@getConfig')->name('config.get');
	Route::post('getClientPreferences', 'Front\UserhomeController@getClientPreferences')->name('getClientPreferences');
	Route::post('validateEmail', 'Front\CustomerAuthController@validateEmail')->name('validateEmail');
	Route::post('user/loginData', 'Front\CustomerAuthController@login')->name('customer.loginData');
	Route::post('user/register', 'Front\CustomerAuthController@register')->name('customer.register');
	Route::post('user/loginViaUsername', 'Front\CustomerAuthController@loginViaUsername')->name('customer.loginViaUsername');
	Route::post('user/verifyPhoneLoginOtp', 'Front\CustomerAuthController@verifyPhoneLoginOtp')->name('customer.verifyPhoneLoginOtp');
	Route::post('vendor-registeration', 'Front\CustomerAuthController@postVendorregister')->name('vendor.register');
	Route::post('user/forgotPassword', 'Front\ForgotPasswordController@postForgotPassword')->name('customer.forgotPass');
	Route::post('user/resetPassword', 'Front\CustomerAuthController@resetPassword')->name('customer.resetPass');
	Route::get('reset-password/{token}', 'Front\ForgotPasswordController@getResetPasswordForm');
	Route::post('reset-password', 'Front\ForgotPasswordController@postUpdateResetPassword')->name('reset-password');

	Route::post('primaryData', 'Front\UserhomeController@changePrimaryData')->name('changePrimaryData');
	Route::post('paginateValue', 'Front\UserhomeController@changePaginate')->name('changePaginate');
	
	Route::post('/product/variant/{id}', 'Front\ProductController@getVariantData')->name('productVariant');
	Route::post('cart/product/lastAdded', 'Front\CartController@getLastAddedProductVariant')->name('getLastAddedProductVariant');
	Route::post('cart/product/variant/different-addons', 'Front\CartController@getProductVariantWithDifferentAddons')->name('getProductVariantWithDifferentAddons');
	Route::post('add/product/cart', 'Front\CartController@postAddToCart')->name('addToCart');
	Route::post('add/product/cart-addons', 'Front\CartController@postAddToCartAddons')->name('addToCartAddons');
	Route::post('add/wishlist/cart', 'Front\CartController@addWishlistToCart')->name('addWishlistToCart');
	Route::post('add/vendorTable/cart', 'Front\CartController@addVendorTableToCart')->name('addVendorTableToCart');
	Route::post('add/product/prescription', 'Front\CartController@uploadPrescription')->name('cart.uploadPrescription');
	Route::post('cart/schedule/update', 'Front\CartController@updateSchedule')->name('cart.updateSchedule');
	Route::post('cart/schedule/slots', 'Front\CartController@checkScheduleSlots')->name('cart.check_schedule_slots');
	Route::post('cart/product-schedule/update', 'Front\CartController@updateProductSchedule')->name('cart.updateProductSchedule');
	
	Route::get('/changeType/{type?}', 'Front\CartController@changeType')->name('changeType');
	Route::get('cartDetails', 'Front\CartController@getCartProducts')->name('cartDetails');
	Route::post('cartDelete', 'Front\CartController@emptyCartData')->name('emptyCartData');
	Route::post('repeatOrder', 'Front\CartController@repeatOrder')->name('web.repeatOrder');
	Route::post('/product/updateCartQuantity', 'Front\CartController@updateQuantity')->name('updateQuantity');
	Route::post('/product/deletecartproduct', 'Front\CartController@deleteCartProduct')->name('deleteCartProduct');
	Route::get('userAddress', 'Front\UserController@getUserAddress')->name('getUserAddress');
	
	Route::get('category/{slug1}/{slug2}', 'Front\CategoryController@categoryVendorProducts')->name('categoryVendorProducts');
	Route::post('category/filters/{id}', 'Front\CategoryController@categoryFilters')->name('productFilters');
	Route::get('vendor/all', 'Front\VendorController@viewAll')->name('vendor.all');
	
	Route::get('vendor/{slug1}/{slug2}', 'Front\VendorController@vendorCategoryProducts')->name('vendorCategoryProducts');
	Route::post('vendor/filters/{id}', 'Front\VendorController@vendorFilters')->name('vendorProductFilters');
	Route::post('vendor/products/searchResults', 'Front\VendorController@vendorProductsSearchResults')->name('vendorProductsSearchResults');
	Route::post('vendor/product/addons', 'Front\VendorController@vendorProductAddons')->name('vendorProductAddons');
	Route::get('brand/{id?}', 'Front\BrandController@brandProducts')->name('brandDetail');
	Route::post('brand/filters/{id}', 'Front\BrandController@brandFilters')->name('brandProductFilters');
	Route::get('celebrity/{slug?}', 'Front\CelebrityController@celebrityProducts')->name('celebrityProducts');
	Route::get('auth/{driver}', 'Front\FacebookController@redirectToSocial');
	Route::get('auth/callback/{driver}', 'Front\FacebookController@handleSocialCallback');
	Route::get('UserCheck', 'Front\UserController@checkUserLogin')->name('checkUserLogin');
	Route::get('stripe/showForm/{token}', 'Front\PaymentController@showFormApp')->name('stripe.formApp');
	Route::post('stripe/make', 'Front\PaymentController@makePayment')->name('stripe.makePayment');
	Route::post('inquiryMode/store', 'Front\ProductInquiryController@store')->name('inquiryMode.store');
	
	Route::post('/getTimeSlotsForOndemand', 'Front\CategoryController@getTimeSlotsForOndemand')->name('getTimeSlotsForOndemand');
	Route::post('checkIsolateSingleVendor', 'Front\CartController@checkIsolateSingleVendor')->name('checkIsolateSingleVendor');
	Route::post('/updateCartSlot', 'Front\CartController@updateCartSlot')->name('updateCartSlot');
	Route::post('/getTimeSlotsForOndemand', 'Front\CategoryController@getTimeSlotsForOndemand')->name('getTimeSlotsForOndemand');
	Route::post('checkIsolateSingleVendor', 'Front\CartController@checkIsolateSingleVendor')->name('checkIsolateSingleVendor');
	Route::get('firebase-messaging-sw.js', 'Front\FirebaseController@service_worker');
});
Route::group(['middleware' => ['domain', 'webAuth']], function () {
	Route::get('user/orders', 'Front\OrderController@orders')->name('user.orders');
	Route::post('user/orders/tip-after-order', 'Front\OrderController@tipAfterOrder')->name('user.tip_after_order');
	Route::post('user/store', 'Front\AddressController@store')->name('address.store');
	Route::get('user/addAddress', 'Front\AddressController@add')->name('addNewAddress');
	Route::get('user/address/{id}', 'Front\AddressController@address')->name('user.address');
	Route::get('user/checkout', 'Front\UserController@checkout')->name('user.checkout');
	Route::get('user/profile', 'Front\ProfileController@profile')->name('user.profile');
	Route::get('user/logout', 'Front\CustomerAuthController@logout')->name('user.logout');
	Route::get('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::get('user/editAddress/{id}', 'Front\AddressController@edit')->name('editAddress');
	Route::post('user/update/{id?}', 'Front\AddressController@update')->name('address.update');
	Route::get('user/wishlists', 'Front\WishlistController@wishlists')->name('user.wishlists');
	Route::post('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::post('sendToken/{id}', 'Front\UserController@sendToken')->name('verifyInformation');
	Route::post('user/placeorder', 'Front\OrderController@placeOrder')->name('user.placeorder');
	Route::get('user/newsLetter', 'Front\ProfileController@newsLetter')->name('user.newsLetter');
	Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');
	Route::post('wishlist/update', 'Front\WishlistController@updateWishlist')->name('addWishlist');
	Route::post('verifTokenProcess', 'Front\UserController@verifyToken')->name('user.verifyToken');
	Route::get('user/addressBook', 'Front\AddressController@index')->name('user.addressBook');
	Route::get('user/wallet', 'Front\WalletController@index')->name('user.wallet');
	Route::post('user/wallet/credit', 'Front\WalletController@creditWallet')->name('user.creditWallet');
	Route::post('wallet/transfer/user/verify', 'Front\WalletController@walletTransferUserVerify')->name('wallet.transfer.user.verify');
	Route::post('wallet/transfer/confirm', 'Front\WalletController@walletTransferConfirm')->name('wallet.transfer.confirm');
	Route::get('user/loyalty', 'Front\LoyaltyController@index')->name('user.loyalty');
	Route::post('wallet/payment/option/list', 'Front\WalletController@paymentOptions')->name('wallet.payment.option.list');
	Route::get('user/deleteAddress/{id}', 'Front\AddressController@delete')->name('deleteAddress');
	Route::post('user/updateAccount', 'Front\ProfileController@updateAccount')->name('user.updateAccount');
	Route::post('user/updateTimezone', 'Front\ProfileController@updateTimezone')->name('user.updateTimezone');
	Route::get('user/editAccount', 'Front\ProfileController@editAccount')->name('user.editAccount');
	Route::get('user/sendRefferal', 'Front\ProfileController@showRefferal')->name('user.sendRefferal');
	Route::get('wishlist/remove/{sku}', 'Front\WishlistController@removeWishlist')->name('removeWishlist');
	Route::get('user/changePassword', 'Front\ProfileController@changePassword')->name('user.changePassword');
	Route::post('user/placeorder/make', 'Front\OrderController@makePayment')->name('placeorder.makePayment');
	Route::post('user/sendRefferalCode', 'Front\ProfileController@sendRefferalCode')->name('user.sendEmail');
	Route::get('user/resetSuccess', 'Front\CustomerAuthController@resetSuccess')->name('customer.resetSuccess');
	Route::post('verify/promocode', 'Front\PromoCodeController@postVerifyPromoCode')->name('verify.promocode');
	Route::post('remove/promocode', 'Front\PromoCodeController@postRemovePromoCode')->name('remove.promocode');
	Route::get('order/success/{order_id}', 'Front\OrderController@getOrderSuccessPage')->name('order.success');
	Route::get('order/return/success', 'Front\OrderController@getOrderSuccessReturnPage')->name('order.return.success');
	Route::post('promocode/list', 'Front\PromoCodeController@postPromoCodeList')->name('verify.promocode.list');
	Route::post('promocode/validate_code', 'Front\PromoCodeController@validate_code')->name('verify.promocode.validate_code');
	Route::post('payment/option/list', 'Front\PaymentController@index')->name('payment.option.list');
	Route::get('user/setPrimaryAddress/{id}', 'Front\AddressController@setPrimaryAddress')->name('setPrimaryAddress');
	Route::post('user/submitPassword', 'Front\ProfileController@submitChangePassword')->name('user.submitChangePassword');
	Route::get('user/wallet/history', 'Front\WalletController@index')->name('user.walletHistory');
	Route::get('user/subscription/plans', 'Front\UserSubscriptionController@getSubscriptionPlans')->name('user.subscription.plans');
	Route::get('user/subscription/select/{slug}', 'Front\UserSubscriptionController@selectSubscriptionPlan')->name('user.subscription.plan.select');
	Route::post('user/subscription/purchase/{slug}', 'Front\UserSubscriptionController@purchaseSubscriptionPlan')->name('user.subscription.plan.purchase');
	Route::post('user/subscription/cancel/{slug}', 'Front\UserSubscriptionController@cancelSubscriptionPlan')->name('user.subscription.plan.cancel');
	Route::get('user/subscription/checkActive/{slug}', 'Front\UserSubscriptionController@checkActiveSubscription')->name('user.subscription.plan.checkActive');
	Route::post('user/save_fcm_token', 'Front\ProfileController@save_fcm')->name('user.save_fcm');
	// Rating & review
	Route::group(['prefix' => 'rating'], function () {
		Route::post('update-product-rating', 'Front\RatingController@updateProductRating')->name('update.order.rating');
		Route::get('get-product-rating', 'Front\RatingController@getProductRating')->name('get-product-rating-details');
	});
	// Return product
	Route::group(['prefix' => 'return-order'], function () {
		Route::get('get-order-data-in-model', 'Front\ReturnOrderController@getOrderDatainModel')
		     ->name('getOrderDatainModel');
		Route::get('get-return-products', 'Front\ReturnOrderController@getReturnProducts')
		     ->name('get-return-products');
		Route::post('update-product-return', 'Front\ReturnOrderController@updateProductReturn')
		     ->name('update.order.return');
        Route::get('get-vendor-order-for-cancel', 'Front\ReturnOrderController@getVendorOrderForCancel')
              ->name('get-vendor-order-for-cancel');
		Route::post('vendor-order-for-cancel', 'Front\ReturnOrderController@vendorOrderForCancel')->name('order.cancel.customer');

	});
	// Return product




//changed routes according to template




	// Route::get('category/{slug?}', 'Templates\CategoryController@categoryProduct')->name('categoryDetail');
	// Route::match(['get','post'],'vendor/{id?}', 'Templates\VendorController@vendorProducts')->name('vendorDetail');
    // Route::get('{vendor?}/product/{id?}', 'Templates\ProductController@index')->name('productDetail');
    // Route::get('cartProducts', 'Templates\CartController@getCartData')->name('getCartProducts');
//changed routes according to template

// Route::get('viewcart', 'Front\CartController@showCart')->name('showCart');

	Route::group(['prefix' => 'template'], function () {
		// Route::get('{vendor?}/product/{id?}', 'Templates\ProductController@index')->name('productDetail2');
		// Route::get('viewcart', 'Templates\CartController@showCart')->name('showCart2');
        // Route::get('category/{slug?}', 'Templates\CategoryController@categoryProduct')->name('template.categoryDetail');
        // Route::match(['get','post'],'vendor/{id?}', 'Templates\VendorController@vendorProducts')->name('vendorDetail1');
	   // Route::get('vendor/{slug1}/{slug2}', 'Templates\VendorController@vendorCategoryProducts')->name('vendorCategoryProducts1');
	});
	Route::group(['prefix' => 'looking'], function () {

		Route::get('/', 'Front\BookingController@index')->name('bookingIndex');
		Route::get('details/{id?}', 'Front\BookingController@bookingDetails')->name('front.booking.details');
		Route::post('orderPlaceDetails/{id}', 'Front\BookingController@orderPlaceDetails')->name('front.booking.orderplacedetails');

		Route::get('payment/options', 'Front\PickupDeliveryController@getPaymentOptions');
		Route::post('create-order', 'Front\PickupDeliveryController@createOrder');
		Route::post('cart/updateQuantity', 'Front\CartController@updateQuantity');
		Route::post('promo-code/list', 'Front\PickupDeliveryController@postPromoCodeList');
		Route::post('promo-code/remove', 'Front\PickupDeliveryController@postRemovePromoCode');
		Route::post('product-detail/{id}', 'Front\PickupDeliveryController@postCabProductById');
		Route::post('get-list-of-vehicles-old/{id}', 'Front\PickupDeliveryController@getListOfVehicles');
		Route::post('vendor/list/{category_id}', 'Front\PickupDeliveryController@postVendorListByCategoryId')->name('pickup-delivery-route');
		Route::post('get-list-of-vehicles/{vid}/{cid?}', 'Front\PickupDeliveryController@productsByVendorInPickupDelivery');
		Route::post('order-tracking-details', 'Front\PickupDeliveryController@getOrderTrackingDetails')->name('bookingIndex');
		Route::post('promo-code/verify', 'Front\PickupDeliveryController@postVerifyPromoCode')->name('verify.cab.booking.promo-code');
		Route::get('get-product-order-form', 'Front\PickupDeliveryController@getProductOrderForm')->name('get-product-order-form');
	});
	Route::post('upload-file', 'Front\RatingController@uploadFile')->name('uploadfile');
});
