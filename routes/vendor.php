<?php

use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\Client\CMS\PageController;
use App\Http\Controllers\Client\CMS\EmailController;
use App\Http\Controllers\Client\CMS\NotificationController;
use App\Http\Controllers\Client\SocialMediaController;
use App\Http\Controllers\Client\VendorPayoutController;
use App\Http\Controllers\Client\DownloadFileController;
use App\Http\Controllers\Client\ProductImportController;
use App\Http\Controllers\Client\Accounting\TaxController;
use App\Http\Controllers\Client\Accounting\OrderController;
use App\Http\Controllers\Client\Accounting\VendorController;
use App\Http\Controllers\Client\Accounting\LoyaltyController;
use App\Http\Controllers\Client\Accounting\PromoCodeController;
use App\Http\Controllers\Client\VendorRegistrationDocumentController;
use App\Http\Controllers\Client\TagController;
use App\Http\Controllers\Client\ClientSlotController;
use App\Http\Controllers\Client\DriverRegistrationDocumentController;
use App\Http\Controllers\Client\ProductFaqController;
use App\Http\Controllers\Client\EstimationController;

Route::get('email-test', function () {
    $details['email'] = 'testmail@yopmail.com';
    dispatch(new App\Jobs\SendVerifyEmailJob($details))->delay(now()->addSeconds(2))->onQueue('course_interactions');
    dd('done');
});

Route::get('admin/login', 'Auth\LoginController@getClientLogin')->name('admin.login')->middleware('domain');
Route::get('file-download/{filename}', [DownloadFileController::class, 'index'])->name('file.download.index');
Route::post('admin/login/client', 'Auth\LoginController@clientLogin')->name('client.login');
Route::get('admin/wrong/url', 'Auth\LoginController@wrongurl')->name('wrong.client');

// ADMIN LANGUAGE SWITCH
Route::group(['middleware' => 'adminLanguageSwitch'], function () {
    Route::group(['middleware' => ['ClientAuth', 'database'], 'prefix' => '/client'], function () {

        Route::any('/logout', 'Auth\LoginController@logout')->name('client.logout');
        Route::get('profile', 'Client\UserController@profile')->name('client.profile');
        Route::get('dashboard', 'Client\DashBoardController@index')->name('client.dashboard');
        Route::get('dashboard/filter', 'Client\DashBoardController@postFilterData')->name('client.dashboard.filter');
        Route::get('salesInfo/monthly', 'Client\DashBoardController@monthlySalesInfo')->name('client.monthlySalesInfo');
        Route::get('salesInfo/yearly', 'Client\DashBoardController@yearlySalesInfo')->name('client.yearlySalesInfo');
        Route::get('salesInfo/weekly', 'Client\DashBoardController@weeklySalesInfo')->name('client.weeklySalesInfo');
        Route::get('categoryInfo', 'Client\DashBoardController@categoryInfo')->name('client.categoryInfo');
        Route::get('cms/pages', [PageController::class, 'index'])->name('cms.pages');
        Route::get('cms/page/{id}', [PageController::class, 'show'])->name('cms.page.show');
        Route::post('cms/page/update', [PageController::class, 'update'])->name('cms.page.update');
        Route::post('cms/page/create', [PageController::class, 'store'])->name('cms.page.create');
        Route::post('cms/page/delete', [PageController::class, 'destroy'])->name('cms.page.delete');
        Route::post('cms/page/ordering',[PageController::class, 'saveOrderOfPage'])->name('cms.page.saveOrderOfPage');
        Route::get('cms/emails', [EmailController::class, 'index'])->name('cms.emails');
        Route::get('cms/emails/{id}', [EmailController::class, 'show'])->name('cms.emails.show');
        Route::post('cms/emails/update', [EmailController::class, 'update'])->name('cms.emails.update');
        Route::get('cms/notifications', [NotificationController::class, 'index'])->name('cms.notifications');
        Route::get('cms/notifications/{id}', [NotificationController::class, 'show'])->name('cms.notifications.show');
        Route::post('cms/notifications/update', [NotificationController::class, 'update'])->name('cms.notifications.update');
        Route::get('account/orders', [OrderController::class, 'index'])->name('account.orders');
        Route::get('account/promo-code', [PromoCodeController::class, 'index'])->name('account.promo.code');
        Route::post('woocommerce/save', [ProductImportController::class, 'postWoocommerceDetail'])->name('woocommerce.save');
        Route::post('product/import', [ProductImportController::class, 'getProductImportViaWoocommerce'])->name('product.import.woocommerce');
        Route::get('account/promo-code/filter', [PromoCodeController::class, 'filter'])->name('account.promo-code.filter');
        Route::get('account/promo-code/export', [PromoCodeController::class, 'export'])->name('account.promo-code.export');
        Route::get('social/media', [SocialMediaController::class, 'index'])->name('social.media.index');
        Route::post('social/media/create', [SocialMediaController::class, 'create'])->name('social.media.create');
        Route::post('social/media/update', [SocialMediaController::class, 'update'])->name('social.media.update');
        Route::get('social/media/edit', [SocialMediaController::class, 'edit'])->name('social.media.edit');
        Route::post('social/media/delete', [SocialMediaController::class, 'delete'])->name('social.media.delete');
        Route::get('account/loyalty', [LoyaltyController::class, 'index'])->name('account.loyalty');
        Route::get('account/tax', [TaxController::class, 'index'])->name('account.tax');
        Route::get('account/vendor', [VendorController::class, 'index'])->name('account.vendor');
        // Route::get('account/vendor/payout', [VendorPayoutController::class, 'index'])->name('account.vendor.payout');
        // Route::get('account/vendor/payout/filter', [VendorPayoutController::class, 'filter'])->name('account.vendor.payout.filter');
        Route::get('account/vendor/payout/requests', [VendorPayoutController::class, 'vendorPayoutRequests'])->name('account.vendor.payout.requests');
        Route::get('account/vendor/payout/requests/filter', [VendorPayoutController::class, 'vendorPayoutRequestsFilter'])->name('account.vendor.payout.requests.filter');
        Route::post('account/vendor/payout/request/complete/{id}', [VendorPayoutController::class, 'vendorPayoutRequestComplete'])->name('account.vendor.payout.request.complete');
        Route::get('account/tax/filter', [TaxController::class, 'filter'])->name('account.tax.filter');
        Route::get('account/tax/export', [TaxController::class, 'export'])->name('account.tax.export');
        Route::get('account/vendor/filter', [VendorController::class, 'filter'])->name('account.vendor.filter');
        Route::get('account/vendor/export', [VendorController::class, 'export'])->name('account.vendor.export');
        Route::get('account/order/filter', [OrderController::class, 'filter'])->name('account.order.filter');
        Route::get('account/loyalty/filter', [LoyaltyController::class, 'filter'])->name('account.loyalty.filter');
        Route::get('account/loyalty/export', [LoyaltyController::class, 'export'])->name('account.loyalty.export');
        Route::get('account/order/export', [OrderController::class, 'export'])->name('account.order.export');
        Route::get('configure', 'Client\ClientPreferenceController@index')->name('configure.index')->middleware('onlysuperadmin');
        Route::post('nomenclature/add', 'Client\NomenclatureController@store')->name('nomenclature.store');
        Route::post('cleanSoftDeleted', 'Client\ManageContentController@deleteAllSoftDeleted')->name('config.cleanSoftDeleted');
        Route::post('importDemoContent', 'Client\ManageContentController@importDemoContent')->name('config.importDemoContent');
        Route::post('hardDeleteEverything', 'Client\ManageContentController@hardDeleteEverything')->name('config.hardDeleteEverything');
        Route::get('customize', 'Client\ClientPreferenceController@getCustomizePage')->name('configure.customize')->middleware('onlysuperadmin');
        Route::post('configUpdate/{code}', 'Client\ClientPreferenceController@update')->name('configure.update');
        Route::post('referandearnUpdate/{code}', 'Client\ClientPreferenceController@referandearnUpdate')->name('referandearn.update');
        Route::post('updateDomain/{code}', 'Client\ClientPreferenceController@postUpdateDomain')->name('client.updateDomain');
        Route::resource('banner', 'Client\BannerController')->middleware('onlysuperadmin');
        Route::post('banner/saveOrder', 'Client\BannerController@saveOrder');
        Route::post('banner/changeValidity', 'Client\BannerController@validity');
        Route::post('vendor/saveLocation/{id}', 'Client\VendorController@updateLocation')->name('vendor.config.pickuplocation');
        Route::post('banner/toggle', 'Client\BannerController@toggleAllBanner')->name('banner.toggle');
        Route::resource('mobilebanner', 'Client\MobileBannerController')->middleware('onlysuperadmin');
        Route::post('mobilebanner/saveOrder', 'Client\MobileBannerController@saveOrder');
        Route::post('mobilebanner/changeValidity', 'Client\MobileBannerController@validity');
        Route::post('mobilebanner/toggle', 'Client\MobileBannerController@toggleAllBanner')->name('mobilebanner.toggle');
        Route::get('web-styling', 'Client\WebStylingController@index')->name('webStyling.index')->middleware('onlysuperadmin');
        Route::post('web-styling/updateWebStyles', 'Client\WebStylingController@updateWebStyles')->name('styling.updateWebStyles');
        Route::post('web-styling/updateWebStylesNew', 'Client\WebStylingController@updateWebStylesNew')->name('styling.updateWebStylesNew');
        Route::get('web-styling/get-html-data-in-modal', 'Client\WebStylingController@getHtmlDatainModal')->name('get-html-data-in-modal');
        Route::get('web-styling/get-image-data-in-modal', 'Client\WebStylingController@getImageDatainModal')->name('get-image-data-in-modal');
        Route::put('web-styling/update-image-data-in-modal', 'Client\WebStylingController@updateImageDatainModal')->name('update-image-data-in-modal');
        Route::post('web-styling/updateDarkMode', 'Client\WebStylingController@updateDarkMode')->name('styling.updateDarkMode');
        Route::post('homepagelabel/saveOrder', 'Client\WebStylingController@saveOrder');
        Route::post('pickuplabel/saveOrder', 'Client\WebStylingController@saveOrderPickup');
        Route::post('web-styling/pickup-add-section', 'Client\WebStylingController@addNewPickupSection')->name('pickup.add.section');
        Route::post('web-styling/edit-Dynamic-Html-Section', 'Client\WebStylingController@editDynamicHtmlSection')->name('edit.Dynamic.Html.Section');
        Route::post('web-styling/pickup-append-section', 'Client\WebStylingController@appendPickupSection')->name('pickup.append.section');
        Route::get('web-styling/pickup-delete-section/{id}', 'Client\WebStylingController@deletePickupSection')->name('pickup.delete.section');
        Route::post('web-styling/updateHomePageStyle', 'Client\WebStylingController@updateHomePageStyle')->name('web.styling.updateHomePageStyle');
        Route::post('web-styling/update-contact-up', 'Client\WebStylingController@updateContactUs')->name('web.styling.update_contact_up');
        Route::get('app-styling', 'Client\AppStylingController@index')->name('appStyling.index')->middleware('onlysuperadmin');
        Route::post('app-styling/updateFont', 'Client\AppStylingController@updateFont')->name('styling.updateFont');
        Route::post('app-styling/updateColor', 'Client\AppStylingController@updateColor')->name('styling.updateColor');
        Route::post('app-styling/updateTabBar', 'Client\AppStylingController@updateTabBar')->name('styling.updateTabBar');
        Route::post('app-styling/updateHomePage', 'Client\AppStylingController@updateHomePage')->name('styling.updateHomePage');
        Route::post('app-styling/updateSignupTagLine', 'Client\AppStylingController@updateSignupTagLine')->name('styling.updateSignupTagLine');
        Route::post('app-styling/addTutorials', 'Client\AppStylingController@addTutorials')->name('styling.addTutorials');
        Route::post('app_styling/saveOrderTutorials', 'Client\AppStylingController@saveOrderTutorials')->name('styling.saveOrderTutorials');
        Route::delete('app-styling/deleteTutorials/{id}', 'Client\AppStylingController@deleteTutorials')->name('styling.deleteTutorials');
        Route::resource('category', 'Client\CategoryController');
        Route::post('categoryOrder', 'Client\CategoryController@updateOrder')->name('category.order');
        Route::get('category/delete/{id}', 'Client\CategoryController@destroy');
        Route::resource('variant', 'Client\VariantController');
        Route::post('variant/order', 'Client\VariantController@updateOrders')->name('variant.order');
        Route::get('variant/cate/{cid}', 'Client\VariantController@variantbyCategory');
        Route::resource('brand', 'Client\BrandController');
        Route::post('brand/order', 'Client\BrandController@updateOrders')->name('brand.order');
        Route::resource('cms', 'Client\CmsController');
        Route::resource('vendorregistrationdocument', 'Client\VendorRegistrationDocumentController');
        Route::get('vendor/registration/document/edit', [VendorRegistrationDocumentController::class, 'show'])->name('vendor.registration.document.edit');
        Route::post('vendorregistrationdocument/create', [VendorRegistrationDocumentController::class, 'store'])->name('vendor.registration.document.create');
        Route::post('vendorregistrationdocument/update', [VendorRegistrationDocumentController::class, 'update'])->name('vendor.registration.document.update');
        Route::post('vendor/registration/document/delete', [VendorRegistrationDocumentController::class, 'destroy'])->name('vendor.registration.document.delete');

        Route::resource('tag', 'Client\TagController');

        Route::get('tag/edit', [TagController::class, 'show'])->name('tag.edit');
        Route::post('tag/create', [TagController::class, 'store'])->name('tag.create');
        Route::post('tag/update', [TagController::class, 'update'])->name('tag.update');
        Route::post('tag/delete', [TagController::class, 'destroy'])->name('tag.delete');


        Route::resource('estimations', 'Client\EstimationController');

        Route::get('estimations/edit', [EstimationController::class, 'show'])->name('estimations.edit');
        Route::post('estimations/create', [EstimationController::class, 'store'])->name('estimations.create');
        Route::post('estimations/update', [EstimationController::class, 'update'])->name('estimations.update');
        Route::post('estimations/delete', [EstimationController::class, 'destroy'])->name('estimations.delete');



        Route::resource('slot', 'Client\ClientSlotController');

        Route::get('slot/edit', [ClientSlotController::class, 'show'])->name('slot.edit');
        Route::post('slot/create', [ClientSlotController::class, 'store'])->name('slot.create');
        Route::post('slot/update', [ClientSlotController::class, 'update'])->name('slot.update');
        Route::post('slot/delete', [ClientSlotController::class, 'destroy'])->name('slot.delete');


        Route::resource('productfaq', 'Client\ProductFaqController');
        Route::get('product/faq/edit', [ProductFaqController::class, 'show'])->name('product.faq.edit');
        Route::post('productfaq/create', [ProductFaqController::class, 'store'])->name('product.faq.create');
        Route::post('productfaq/update', [ProductFaqController::class, 'update'])->name('product.faq.update');
        Route::post('product/faq/delete', [ProductFaqController::class, 'destroy'])->name('product.faq.delete');



        Route::resource('driverregistrationdocument', 'Client\DriverRegistrationDocumentController');
        Route::get('driver/registration/document/edit', [DriverRegistrationDocumentController::class, 'show'])->name('driver.registration.document.edit');
        Route::post('driverregistrationdocument/create', [DriverRegistrationDocumentController::class, 'store'])->name('driver.registration.document.create');
        Route::post('driverregistrationdocument/update', [DriverRegistrationDocumentController::class, 'update'])->name('driver.registration.document.update');
        Route::post('driver/registration/document/delete', [DriverRegistrationDocumentController::class, 'destroy'])->name('driver.registration.document.delete');
        Route::resource('tax', 'Client\TaxCategoryController');
        Route::resource('taxRate', 'Client\TaxRateController');
        Route::resource('addon', 'Client\AddonSetController');
        Route::resource('payment', 'Client\PaymentController');
        Route::resource('accounting', 'Client\AccountController');
        Route::get('vendor/filterdata', 'Client\VendorController@getFilterData')->name('vendor.filterdata');
        Route::post('vendor/status/update', 'Client\VendorController@postUpdateStatus')->name('vendor.status');
        Route::get('user/filterdata', 'Client\UserController@getFilterData')->name('user.filterdata');
        Route::resource('vendor', 'Client\VendorController');
        Route::get('vendor/categories/{id}', 'Client\VendorController@vendorCategory')->name('vendor.categories');
        Route::post('vendor/search/customer', 'Client\VendorController@searchUserForPermission')->name('searchUserForPermission');
        Route::post('vendor/permissionsForUserViaVendor', 'Client\VendorController@permissionsForUserViaVendor')->name('permissionsForUserViaVendor');
        Route::DELETE('vendor/vendor-permission-del/{id}', 'Client\VendorController@userVendorPermissionDestroy')->name('user.vendor.permission.destroy');
        Route::get('vendor/catalogs/{id}', 'Client\VendorController@vendorCatalog')->name('vendor.catalogs');
        Route::get('vendor/payout/{id}', 'Client\VendorController@vendorPayout')->name('vendor.payout');
        Route::get('vendor/payout/filter/{id}', 'Client\VendorController@payoutFilter')->name('vendor.payout.filter');
        Route::post('vendor/payout/create/{id}', 'Client\VendorController@vendorPayoutCreate')->name('vendor.payout.create');
        Route::post('vendor/saveConfig/{id}', 'Client\VendorController@updateConfig')->name('vendor.config.update');
        Route::post('vendor/saveLocation/{id}', 'Client\VendorController@updateLocation')->name('vendor.config.pickuplocation');
        Route::post('vendor/activeCategory/{id}', 'Client\VendorController@activeCategory')->name('vendor.category.update');
        Route::post('vendor/addCategory/{id}', 'Client\TableBookingController@storeCategory')->name('vendor.addCategory');
        Route::get('vendor/vendor_specific_categories/{id}', 'Client\VendorController@vendor_specific_categories')->name('vendor.specific_categories');
        Route::post('vendor/updateCategory/{id}', 'Client\TableBookingController@updateCategory')->name('vendor.updateCategory');
        Route::get('vendor/table/category/edit', 'Client\TableBookingController@editCategory')->name('vendor_table_category_edit');
        Route::get('vendor/table/number/edit', 'Client\TableBookingController@editTable')->name('vendor_table_edit');
        Route::post('vendor/category/delete/{id}', 'Client\TableBookingController@destroyCategory')->name('vendor.category.delete');
        Route::post('vendor/addTable/{id}', 'Client\TableBookingController@storeTable')->name('vendor.addTable');
        Route::post('vendor/updateTable/{id}', 'Client\TableBookingController@updateTable')->name('vendor.updateTable');
        Route::post('vendor/table/delete/{id}', 'Client\TableBookingController@destroyTable')->name('vendor.table.delete');
        Route::post('vendor/parentStatus/{id}', 'Client\VendorController@checkParentStatus')->name('category.parent.status');
        Route::get('calender/data/{id}', 'Client\VendorSlotController@returnJson')->name('vendor.calender.data');
        Route::post('vendor/slot/{id}', 'Client\VendorSlotController@store')->name('vendor.saveSlot');
        Route::post('vendor/updateSlot/{id}', 'Client\VendorSlotController@update')->name('vendor.updateSlot');
        Route::post('vendor/deleteSlot/{id}', 'Client\VendorSlotController@destroy')->name('vendor.deleteSlot');
        Route::post('vendor/importCSV', 'Client\VendorController@importCsv')->name('vendor.import');
        Route::post('vendor/serviceArea/{vid}', 'Client\ServiceAreaController@store')->name('vendor.serviceArea');
        Route::post('vendor/editArea/{vid}', 'Client\ServiceAreaController@edit')->name('vendor.serviceArea.edit');
        Route::post('vendor/updateArea/{id}', 'Client\ServiceAreaController@update');
        Route::post('vendor/deleteArea/{vid}', 'Client\ServiceAreaController@destroy')->name('vendor.serviceArea.delete');
        Route::post('draw-circle-with-radius/{vid}', 'Client\ServiceAreaController@drawCircleWithRadius')->name('draw.circle.with.radius');
        Route::resource('order', 'Client\OrderController');
        Route::post('orders/filter', 'Client\OrderController@postOrderFilter')->name('orders.filter');
        Route::get('order/return/{status}', 'Client\OrderController@returnOrders')->name('backend.order.returns');
        Route::get('order/return-modal/get-return-product-modal', 'Client\OrderController@getReturnProductModal')->name('get-return-product-modal');
        Route::post('order/update-product-return-client', 'Client\OrderController@updateProductReturn')->name('update.order.return.client');
        Route::get('order/{order_id}/{vendor_id}', 'Client\OrderController@getOrderDetail')->name('order.show.detail');
        Route::get('order-edit/{order_id}/{vendor_id}', 'Client\OrderController@getOrderDetailEdit')->name('order.edit.detail');
        Route::post('order/updateStatus', 'Client\OrderController@changeStatus')->name('order.changeStatus');
        Route::post('order/create-dispatch-request', 'Client\OrderController@createDispatchRequest')->name('create.dispatch.request'); # create dispatch request
        Route::resource('customer', 'Client\UserController')->middleware('onlysuperadmin');
        Route::get('customer/account/{user}/{action}', 'Client\UserController@deleteCustomer')->name('customer.account.action');
        Route::get('customer/edit/{id}', 'Client\UserController@newEdit')->name('customer.new.edit');
        Route::post('customer/import', 'Client\UserController@importCsv')->name('customer.import');
        Route::put('newUpdate/edit/{id}', 'Client\UserController@newUpdate')->name('customer.new.update');
        Route::put('profile/{id}', 'Client\UserController@updateProfile')->name('client.profile.update');
        Route::post('password/update', 'Client\UserController@changePassword')->name('client.password.update');
        Route::post('customer/change/status', 'Client\UserController@changeStatus')->name('customer.changeStatus');
        Route::get('customer/wallet/transactions', 'Client\UserController@filterWalletTransactions')->name('customer.filterWalletTransactions');
        Route::get('customer/export/export', 'Client\UserController@export')->name('customer.export');
        Route::resource('product', 'Client\ProductController');
        Route::post('product/updateActions', 'Client\ProductController@updateActions')->name('product.update.action');   # update all product actions
        Route::post('product/importCSV', 'Client\ProductController@importCsv')->name('product.import');
        Route::post('product/validate', 'Client\ProductController@validateData')->name('product.validate');
        Route::post('product/sku/validate', 'Client\ProductController@validateSku')->name('product.sku.validate');
        Route::get('product/add/{vendor_id}', 'Client\ProductController@create')->name('product.add');
        Route::post('product/getImages', 'Client\ProductController@getImages')->name('productImage.get');
        Route::post('product/deleteVariant', 'Client\ProductController@deleteVariant')->name('product.deleteVariant');
        Route::post('product/images', 'Client\ProductController@images')->name('product.images');
        Route::post('product/translation', 'Client\ProductController@translation')->name('product.translation');
        Route::post('product/variantRows', 'Client\ProductController@makeVariantRows')->name('product.makeRows');
        Route::post('product/variantImage/update', 'Client\ProductController@updateVariantImage')->name('product.variant.update');
        Route::get('product/image/delete/{pid}/{id}', 'Client\ProductController@deleteImage')->name('product.deleteImg');
        Route::resource('loyalty', 'Client\LoyaltyController');
        Route::post('loyalty/changeStatus', 'Client\LoyaltyController@changeStatus')->name('loyalty.changeStatus');
        Route::post('loyalty/getRedeemPoints', 'Client\LoyaltyController@getRedeemPoints')->name('loyalty.getRedeemPoints');
        Route::post('loyalty/setRedeemPoints', 'Client\LoyaltyController@setRedeemPoints')->name('loyalty.setRedeemPoints');
        Route::post('loyalty/setLoyaltyCheck', 'Client\LoyaltyController@setLoyaltyCheck')->name('loyalty.setLoyaltyCheck');
        Route::resource('celebrity', 'Client\CelebrityController');
        Route::post('celebrity/changeStatus', 'Client\CelebrityController@changeStatus')->name('celebrity.changeStatus');
        Route::post('celebrity/getBrands', 'Client\CelebrityController@getBrandList')->name('celebrity.getBrands');
        Route::resource('wallet', 'Client\WalletController');
        Route::resource('promocode', 'Client\PromocodeController');
        Route::resource('payoption', 'Client\PaymentOptionController');
        Route::resource('shipoption', 'Client\ShippingOptionController');
        Route::resource('deliveryoption', 'Client\DeliveryOptionController');
        Route::resource('tools','Client\ToolsController');
        Route::post('tools/tax','Client\ToolsController@taxCopy')->name('tools.taxCopy');
        Route::post('tool/uploadImage','Client\ToolsController@uploadImage')->name('tools.uploadImage');
        Route::post('updateAll', 'Client\PaymentOptionController@updateAll')->name('payoption.updateAll');
        Route::post('shippment/updateAll', 'Client\ShippingOptionController@updateAll')->name('shipoption.updateAll');
        Route::post('payoutUpdateAll', 'Client\PaymentOptionController@payoutUpdateAll')->name('payoutOption.payoutUpdateAll');
        Route::resource('inquiry', 'Client\ProductInquiryController');
        Route::get('inquiry/filter', [ProductInquiryController::class, 'show'])->name('inquiry.filter');

        Route::get('subscription/plans/user', 'Client\SubscriptionPlansUserController@getSubscriptionPlans')->name('subscription.plans.user')->middleware('onlysuperadmin');
        Route::post('subscription/plan/save/user/{slug?}', 'Client\SubscriptionPlansUserController@saveSubscriptionPlan')->name('subscription.plan.save.user');
        Route::get('subscription/plan/edit/user/{slug}', 'Client\SubscriptionPlansUserController@editSubscriptionPlan')->name('subscription.plan.edit.user');
        Route::get('subscription/plan/delete/user/{slug}', 'Client\SubscriptionPlansUserController@deleteSubscriptionPlan')->name('subscription.plan.delete.user');
        Route::post('subscription/plan/updateStatus/user/{slug}', 'Client\SubscriptionPlansUserController@updateSubscriptionPlanStatus')->name('subscription.plan.updateStatus.user');
        Route::get('subscription/plans/vendor', 'Client\SubscriptionPlansVendorController@getSubscriptionPlans')->name('subscription.plans.vendor')->middleware('onlysuperadmin');
        Route::post('subscription/plan/save/vendor/{slug?}', 'Client\SubscriptionPlansVendorController@saveSubscriptionPlan')->name('subscription.plan.save.vendor');
        Route::get('subscription/plan/edit/vendor/{slug}', 'Client\SubscriptionPlansVendorController@editSubscriptionPlan')->name('subscription.plan.edit.vendor');
        Route::get('subscription/plan/delete/vendor/{slug}', 'Client\SubscriptionPlansVendorController@deleteSubscriptionPlan')->name('subscription.plan.delete.vendor');
        Route::post('subscription/plan/updateStatus/vendor/{slug}', 'Client\SubscriptionPlansVendorController@updateSubscriptionPlanStatus')->name('subscription.plan.updateStatus.vendor');
        Route::post('subscription/plan/updateOnRequest/vendor/{slug}', 'Client\SubscriptionPlansVendorController@updateSubscriptionPlanOnRequest')->name('subscription.plan.updateOnRequest.vendor');

        Route::get('vendor/subscription/plans/{id}', 'Client\VendorController@getSubscriptionPlans')->name('vendor.subscription.plans');
        Route::get('vendor/subscription/select/{slug}', 'Client\VendorSubscriptionController@selectSubscriptionPlan')->name('vendor.subscription.plan.select');
        Route::post('vendor/subscription/purchase/{id}/{slug}', 'Client\VendorSubscriptionController@purchaseSubscriptionPlan')->name('vendor.subscription.plan.purchase');
        Route::post('vendor/subscription/cancel/{id}/{slug}', 'Client\VendorSubscriptionController@cancelSubscriptionPlan')->name('vendor.subscription.plan.cancel');
        Route::get('vendor/subscription/checkActive/{id}/{slug}', 'Client\VendorSubscriptionController@checkActiveSubscription')->name('vendor.subscription.plan.checkActive');
        Route::any('vendor/subscriptions/filterData', 'Client\VendorSubscriptionController@getSubscriptionsFilterData')->name('vendor.subscriptions.filterData');
        Route::post('vendor/subscription/status/update/{slug}', 'Client\VendorSubscriptionController@updateSubscriptionStatus')->name('vendor.subscription.status.update');


        Route::post('subscription/payment/stripe', 'Client\StripeGatewayController@subscriptionPaymentViaStripe')->name('subscription.payment.stripe');
        Route::get('verify/oauth/token/stripe', 'Client\StripeGatewayController@verifyOAuthToken')->name('verify.oauth.token.stripe');
        Route::get('create/custom/connected-account/stripe/{vendor_id}', 'Client\StripeGatewayController@createCustomConnectedAccount')->name('create.custom.connected-account.stripe');
        Route::post('vendor/payout/stripe/{id}', 'Client\StripeGatewayController@vendorPayoutViaStripe')->name('vendor.payout.stripe');

        Route::get('/admin/signup', 'Client\AdminSignUpController@index')->name('admin.signup');
        Route::post('save_fcm_token', 'Client\UserController@save_fcm')->name('client.save_fcm');

        // pickup & delivery
        Route::group(['prefix' => 'vendor/dispatcher'], function () {
            Route::post('updateCreateVendorInDispatch', 'Client\VendorController@updateCreateVendorInDispatch')->name('update.Create.Vendor.In.Dispatch');
            Route::post('updateCreateVendorInDispatchOnDemand', 'Client\VendorController@updateCreateVendorInDispatchOnDemand')->name('update.Create.Vendor.In.Dispatch.OnDemand');
            Route::post('updateCreateVendorInDispatchLaundry', 'Client\VendorController@updateCreateVendorInDispatchLaundry')->name('update.Create.Vendor.In.Dispatch.Laundry');
        });

        Route::resource('review', 'Client\ReviewController');
    });
});


Route::get('/search11', [SearchController::class, 'search']);
Route::group(['middleware' => 'auth:client', 'prefix' => '/admin'], function () {
    Route::get('/', 'Client\DashBoardController@index')->name('home');
    Route::get('{first}/{second}/{third}', 'Client\RoutingController@thirdLevel')->name('third');
    Route::get('{first}/{second}', 'Client\RoutingController@secondLevel')->name('second');
    Route::get('{any}', 'Client\RoutingController@root')->name('any');
});
