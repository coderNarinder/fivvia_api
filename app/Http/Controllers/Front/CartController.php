<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GCLIENT;
use App\Http\Traits\{ApiResponser,CartManager};
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\PromoCodeController;
use App\Http\Controllers\Front\LalaMovesController;
use App\Http\Controllers\ShiprocketController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, CartCoupon, CartDeliveryFee, User, Product, ClientCurrency, ClientLanguage, CartProductPrescription, ProductVariantSet, Country, UserAddress, Client, ClientPreference, Vendor, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor,PaymentOption, OrderTax, LuxuryOption, UserWishlist, SubscriptionInvoicesUser, LoyaltyCard, VendorDineinCategory, VendorDineinTable, VendorDineinCategoryTranslation, VendorDineinTableTranslation, VendorSlot};
use Log;
use Illuminate\Support\Facades\Redirect;
use App\Providers\Custom\ClientCacheClass;
class CartController extends FrontController
{
    use ApiResponser,CartManager;

     public  $client_id = 0;
     public $ClientPreferencs=[];
    public function __construct(){
        $obj = new ClientCacheClass;
        $this->client_id = $obj->client_id;
        $this->ClientPreferencs = $obj->ClientPreferencs;
        
    }

    private function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 32);
        while (User::where('system_id', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 32);
        }
        return $random_string;
    }

    public function showCart(Request $request, $domain = '')
    {
        if(($request->has('gateway')) && (($request->gateway == 'mobbex')||($request->gateway == 'yoco'))){
            if($request->has('order')){
                $order = Order::where('order_number', $request->order)->first();
                if($order){
                    if($request->status == 0){
                        $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                        foreach($order_products as $order_prod){
                            OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                        }
                        OrderProduct::where('order_id', $order->id)->delete();
                        OrderProductPrescription::where('order_id', $order->id)->delete();
                        VendorOrderStatus::where('order_id', $order->id)->delete();
                        OrderVendor::where('order_id', $order->id)->delete();
                        OrderTax::where('order_id', $order->id)->delete();
                        $order->delete();
                        return redirect()->route('showCart')->with('error', 'Your order has been cancelled');
                    }
                    elseif($request->status == 200){
                        return redirect()->route('order.success', $order->id);
                    }
                }
            }
            return redirect()->route('showCart');
        }

        $cartData = [];
        $user = Auth::user();
        $countries = Country::get();
        $langId = Session::get('customerLanguage');
        $guest_user = true;
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count','comment_for_pickup_driver','comment_for_dropoff_driver','comment_for_vendor','specific_instructions')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
            $addresses = UserAddress::where('user_id', $user->id)->get();
            $guest_user = false;
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count','comment_for_pickup_driver','comment_for_dropoff_driver','comment_for_vendor','specific_instructions')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
            $addresses = collect();
        }
        if ($cart) {
            $cartData = CartProduct::where('status', [0, 1])->where('cart_id', $cart->id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        }
        $navCategories = $this->categoryNav($langId);
        $subscription_features = array();
        if ($user) {
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $user->id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
        }
        $action = (Session::has('vendorType')) ? Session::get('vendorType') : 'delivery';
        $data = array(
            'navCategories' => $navCategories,
            'cartData' => $cartData,
            'addresses' => $addresses,
            'countries' => $countries,
            'subscription_features' => $subscription_features,
            'guest_user'=>$guest_user,

            'action' => $action
        );
        $client_preference_detail = ClientPreference::first();
        $client_detail = Client::first();
        // dd($client_detail);
        $public_key_yoco=PaymentOption::where('code','yoco')->first();
        if($public_key_yoco){

            $public_key_yoco= $public_key_yoco->credentials??'';
            $public_key_yoco= json_decode($public_key_yoco);
            $public_key_yoco= $public_key_yoco->public_key??'';
        }





        return view('frontend.cartnew',compact('public_key_yoco','cart','client_detail'))->with($data,$client_preference_detail,$client_detail);
        // return view('frontend.cartnew')->with(['navCategories' => $navCategories, 'cartData' => $cartData, 'addresses' => $addresses, 'countries' => $countries, 'subscription_features' => $subscription_features, 'guest_user'=>$guest_user]);
    }

    public function postAddToCart(Request $request, $domain = '')
    {
        $preference = ClientPreference::first();
        $luxury_option = LuxuryOption::where('title', Session::get('vendorType'))->first();
        try {
            $cart_detail = [];
            $user = Auth::user();
            // $addon_ids = $request->addonID;
            // $addon_options_ids = $request->addonoptID;
            $langId = Session::get('customerLanguage');
            $new_session_token = session()->get('_token');
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $user_id = $user ? $user->id : '';
            $variant_id = $request->variant_id;
            if ($user) {
                $cart_detail['user_id'] = $user_id;
                $cart_detail['created_by'] = $user_id;
            }
            $cart_detail = [
                'is_gift' => 1,
                'status' => '0',
                'item_count' => 0,
                'currency_id' => $client_currency->currency_id,
                'unique_identifier' => !$user ? $new_session_token : '',
            ];
            if ($user) {
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
                $already_added_product_in_cart = CartProduct::where(["product_id" => $request->product_id, 'cart_id' => $cart_detail->id])->first();
            } else {
                $cart_detail = Cart::updateOrCreate(['unique_identifier' => $new_session_token], $cart_detail);
                $already_added_product_in_cart = CartProduct::where(["product_id" => $request->product_id, 'cart_id' => $cart_detail->id])->first();
            }
            $productDetail = Product::with([
                'variant' => function ($sel) use($variant_id) {
                    $sel->where('id', $variant_id);
                    $sel->groupBy('product_id');
                }
            ])->find($request->product_id);

            # if product type is not equal to on demand
            if( ($productDetail->category->categoryDetail->type_id != 8) && ($productDetail->has_inventory == 1)  && ($productDetail->sell_when_out_of_stock == 0)){
                if(!empty($already_added_product_in_cart)){
                    if($productDetail->variant[0]->quantity <= $already_added_product_in_cart->quantity){
                        return response()->json(['status' => 'error', 'message' => __('Maximum quantity already added in your cart s')]);
                    }
                    if($productDetail->variant[0]->quantity <= ($already_added_product_in_cart->quantity + $request->quantity)){
                        $request->quantity = $productDetail->variant[0]->quantity - $already_added_product_in_cart->quantity;
                    }
                }
                if($productDetail->variant[0]->quantity < $request->quantity){
                    if($productDetail->variant[0]->quantity == 0){
                        $productDetail->variant[0]->quantity = 1;
                    }
                     $request->quantity = $productDetail->variant[0]->quantity;
                }
            }


            $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addonID')){
                $addon_ids = $request->addonID;
            }
            if($request->has('addonoptID')){
                $addon_options = $request->addonoptID;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach($addonSets as $key => $value){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                            ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                            ->where('ast.language_id', $langId)
                            ->where('addon_sets.status', '!=', '2')
                            ->where('addon_sets.id', $key)->first();
                if(!$addon){
                    return response()->json(["status" => "error", 'message' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
                }
                if($addon->min_select > count($value)){
                    return response()->json([
                        "status" => "error",
                        'message' => 'Select minimum ' . $addon->min_select .' options of ' .$addon->title,
                        'data' => $addon
                    ], 400);
                }
                if($addon->max_select < count($value)){
                    return response()->json([
                        "status" => "error",
                        'message' => 'You can select maximum ' . $addon->min_select .' options of ' .$addon->title,
                        'data' => $addon
                    ], 400);
                }
            }
            $oldquantity = $isnew = 0;
            $cart_product_detail = [
                'status'  => '0',
                'is_tax_applied'  => '1',
                'created_by'  => $user_id,
                'cart_id'  => $cart_detail->id,
                'quantity'  => $request->quantity,
                'vendor_id'  => $request->vendor_id,
                'product_id' => $request->product_id,
                'variant_id'  => $request->variant_id,
                'currency_id' => $client_currency->currency_id,
                'luxury_option_id' => ($luxury_option) ? $luxury_option->id : 0
            ];

            $checkVendorId = CartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', '!=', $request->vendor_id)->first();

            if ($luxury_option) {
                $checkCartLuxuryOption = CartProduct::where('luxury_option_id', '!=', $luxury_option->id)
                ->where('cart_id', $cart_detail->id)->first();
                if ($checkCartLuxuryOption) {
                    CartProduct::where('cart_id', $cart_detail->id)->delete();
                }
                if ($luxury_option->id == 2 || $luxury_option->id == 3) {
                    if ($checkVendorId) {
                        CartProduct::where('cart_id', $cart_detail->id)
                        ->delete();
                    }else{
                        $checkVendorTableAdded = CartProduct::where('cart_id', $cart_detail->id)
                        ->where('vendor_id', $request->vendor_id)
                        ->whereNotNull('vendor_dinein_table_id')
                        ->first();
                        $cart_product_detail['vendor_dinein_table_id'] = ($checkVendorTableAdded) ? $checkVendorTableAdded->vendor_dinein_table_id : NULL;
                    }
                }
            }
            if ( (isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) ) {
                if ($checkVendorId) {
                    CartProduct::where('cart_id', $cart_detail->id)->delete();
                }
            }

            $cartProduct = CartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cart_detail->id)->first();
            if(!$cartProduct){
                $isnew = 1;
            }else{
                $checkaddonCount = CartAddon::where('cart_product_id', $cartProduct->id)->count();
                if(count($addon_ids) != $checkaddonCount){
                    $isnew = 1;
                }else{
                    foreach ($addon_options as $key => $opts) {
                        $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                    ->where('addon_id', $addon_ids[$key])
                                    ->where('option_id', $opts)->first();

                        if(!$cart_addon){
                            $isnew = 1;
                        }
                    }
                }
            }

            if($isnew == 1){
                $cartProduct = CartProduct::create($cart_product_detail);
                if(!empty($addon_ids) && !empty($addon_options)){
                    $saveAddons = array();
                    foreach ($addon_options as $key => $opts) {
                        $saveAddons[] = [
                            'option_id' => $opts,
                            'cart_id' => $cart_detail->id,
                            'addon_id' => $addon_ids[$key],
                            'cart_product_id' => $cartProduct->id,
                        ];
                    }
                    if(!empty($saveAddons)){
                        CartAddon::insert($saveAddons);
                    }
                }
            }else{
                $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                $cartProduct->save();
            }

            // if ($checkIfExist) {
            //     $checkIfExist->quantity = (int)$checkIfExist->quantity + $request->quantity;
            //     $cart_detail->cartProducts()->save($checkIfExist);
            // } else {
                // $productForVendor = Product::where('id', $request->product_id)->first();

                // $cart_product = CartProduct::updateOrCreate(['cart_id' =>  $cart_detail->id, 'product_id' => $request->product_id], $cart_product_detail);
                // $create_cart_addons = [];
                // if ($addon_options_ids) {
                //     foreach ($addon_options_ids as $k => $addon_options_id) {
                //         $create_cart_addons[] = [
                //             'addon_id' => $addon_ids[$k],
                //             'cart_id' => $cart_detail->id,
                //             'option_id' => $addon_options_id,
                //             'cart_product_id' => $cart_product->id,
                //         ];
                //     }
                // }
                // CartAddon::insert($create_cart_addons);
            // }
            return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!','cart_product_id' => $cartProduct->id]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    /**
     * add product to cart
     *
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        if ($request->has('addonID') && $request->has('addonoptID')) {
            $addon_ids = $request->addonID;
            $addon_options = $request->addonoptID;
            $addonSets = array();
            foreach ($addon_options as $key => $opt) {
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach ($addonSets as $key => $value) {
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                    ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                    ->where('ast.language_id', $langId)
                    ->where('addon_sets.status', '!=', '2')
                    ->where('addon_sets.id', $key)->first();
                if (!$addon) {
                    return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
                }
                if ($addon->min_select > count($value)) {
                    return response()->json([
                        'error' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
                if ($addon->max_select < count($value)) {
                    return response()->json([
                        'error' => 'You can select maximum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
            }
        }
        $user_id = ' ';
        $cartInfo = ' ';
        $user = Auth::user();
        $currency = ClientCurrency::where('is_primary', '=', 1)->first();
        if ($user) {
            $user_id = $user->id;
            $userFind = Cart::where('user_id', $user_id)->first();
            if (!$userFind) {
                $cart = new Cart;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->user_id = $user_id;
                $cart->created_by = $user_id;
                $cart->currency_id = $currency->currency->id;
                $cart->unique_identifier = $user->system_id;
                $cart->save();
                $cartInfo = $cart;
            } else {
                $cartInfo = $userFind;
            }
            $checkIfExist = CartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cartInfo->id)->first();
            if ($checkIfExist) {
                $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
                $cartInfo->cartProducts()->save($checkIfExist);
                return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!']);
            } else {
            }
        } else {
            $cart_detail = Cart::where('unique_identifier', session()->get('_token'))->first();
            if (!$cart_detail) {
                $cart = new Cart;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->currency_id = $currency->currency->id;
                $cart->unique_identifier = session()->get('_token');
                $cart->save();
            }
            $productForVendor = Product::where('id', $request->product_id)->first();
            $cartProduct = new CartProduct;
            $cartProduct->status  = '0';
            $cartProduct->is_tax_applied  = '1';
            $cartProduct->created_by  = $user_id;
            $cartProduct->cart_id  = $cart_detail->id;
            $cartProduct->quantity  = $request->quantity;
            $cartProduct->product_id = $request->product_id;
            $cartProduct->variant_id  = $request->variant_id;
            $cartProduct->currency_id = $cart_detail->currency_id;
            $cartProduct->vendor_id  = $productForVendor->vendor_id;
            $cartProduct->save();
            if ($request->has('addonID') && $request->has('addonID')) {
                foreach ($addon_ids as $key => $value) {
                    $aa = $addon_ids[$key];
                    $bb = $addon_options[$key];
                    $cartAddOn = new CartAddon;
                    $cartAddOn->addon_id = $aa;
                    $cartAddOn->option_id = $bb;
                    $cartAddOn->cart_id = $cart_detail->id;
                    $cartAddOn->cart_product_id = $cartProduct->id;
                    $cartAddOn->save();
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!','cart_product_id' => $cartProduct->id]);
        }
    }

    /**
     * add wishlist products to cart
     *
     * @return \Illuminate\Http\Response
     */
    public function addWishlistToCart(Request $request, $domain = '')
    {
        try {
            $cart_detail = [];
            $user = Auth::user();
            $new_session_token = session()->get('_token');
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $user_id = $user ? $user->id : '';
            if ($user) {
                $cart_detail['user_id'] = $user_id;
                $cart_detail['created_by'] = $user_id;
                $cart_detail = [
                    'is_gift' => 1,
                    'status' => '0',
                    'item_count' => 0,
                    'currency_id' => $client_currency->currency_id,
                    'unique_identifier' => !$user ? $new_session_token : '',
                ];
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
                foreach ($request->wishlistProducts as $product) {
                    $checkIfExist = CartProduct::where('product_id', $product['product_id'])->where('variant_id', $product['variant_id'])->where('cart_id', $cart_detail->id)->first();
                    if ($checkIfExist) {
                        $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
                        $cart_detail->cartProducts()->save($checkIfExist);
                    } else {
                        $productVendor = Product::where('id', $product['product_id'])->first();
                        $cart_product_detail = [
                            'status'  => '0',
                            'is_tax_applied'  => '1',
                            'created_by'  => $user_id,
                            'cart_id'  => $cart_detail->id,
                            'quantity'  => 1,
                            'vendor_id'  => $productVendor->vendor_id,
                            'product_id' => $product['product_id'],
                            'variant_id'  => $product['variant_id'],
                            'currency_id' => $client_currency->currency_id,
                        ];
                        $cart_product = CartProduct::updateOrCreate(['cart_id' =>  $cart_detail->id, 'product_id' => $product['product_id']], $cart_product_detail);
                    }
                    $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product['product_id'])->where('product_variant_id', $product['variant_id'])->first();
                    if ($exist) {
                        $exist->delete();
                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Products Has Been Added to Cart Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->message()]);
        }
    }

    /**
     * get products from cart
     *
     * @return \Illuminate\Http\Response
     */

    public function changeType(Request $request,$domain='',$type='')
    {
        Session()->forget('vendorType');
        Session()->put('vendorType', $type);
        return Redirect::To(url('/'));
    }

    public function getCartProducts(Request $request,$domain = '')
    {
        
        Session()->forget('vendorType');
        Session()->put('vendorType', $request->type);

        $cart_details = [];
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time'
        )->with('coupon.promo')
            ->where('status', '0')
            ->where('client_id',$this->client_id)
            ->where('user_id', $user->id)
            ->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')
            ->with('coupon.promo')
            ->where('status', '0')
            ->where('client_id',$this->client_id)
            ->where('unique_identifier', session()->get('_token'))
            ->first();
        }
        if ($cart) {
            $cart_details = $this->getCart($cart);
        }
        if ($cart_details && !empty($cart_details)) {
            return response()->json([
                'data' => $cart_details,
            ]);
        }
        return response()->json([
            'message' => "No product found in cart",
            'data' => $cart_details,
        ]);
    }

    /**
     * Get Cart Items
     *
     */
    public function getCart($cart, $address_id=0 , $code = 'D')
    {
        $address = [];
      $cart_id = $cart->id;
        $user = Auth::user();
        $langId = Session::has('customerLanguage') ? Session::get('customerLanguage') : 1;
        $curId = Session::get('customerCurrency');
        $preferences = ClientPreference::with(['client_detail:id,code,country_id'])->where('client_id',$this->client_id)->first();
        $countries = Country::get();
        $cart->pharmacy_check = $preferences->pharmacy_check;
        $customerCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $nowdate = Carbon::now()->toDateTimeString();
        $latitude = '';
        $longitude = '';
        $user_allAddresses = collect();
        $upSell_products = collect();
        $crossSell_products = collect();
        if($user){
            $user_allAddresses = UserAddress::where('user_id', $user->id)->get();
            if($address_id > 0){
                $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->first();
            }else{
                $address = UserAddress::where('user_id', $user->id)->where('is_primary', 1)->first();
                $address_id = ($address) ? $address->id : 0;
            }
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';

        $delifproductnotexist = CartProduct::where('cart_id', $cart_id)
        ->where('client_id',$this->client_id)->doesntHave('product')->delete();

       $cartData = CartProduct::with([
            'vendor','vendor.slots','vendor.slot.day', 'vendor.slotDate', 'coupon' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            }, 'vendorProducts.pvariant.media.pimage.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation_one' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'vendorProducts' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id) ->groupBy('product_id');
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                $qry->select('addon_options.id', 'addon_options.price', 'apt.title', 'addon_options.addon_id', 'apt.language_id');
                $qry->where('apt.language_id', $langId)
                ->groupBy(['addon_options.id', 'apt.language_id']);
                // $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])
        ->select('vendor_id', 'luxury_option_id', 'vendor_dinein_table_id')
        ->where('status', [0, 1])
        ->where('cart_id', $cart_id)
        ->where('client_id',$this->client_id)
        ->groupBy('vendor_id')
         ->orderBy('created_at', 'asc')->get();

       // return $cartData;
        $loyalty_amount_saved = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $subscription_features = array();
        if($user){
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)
            ->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))
            ->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                    if( ($customerCurrency) && ($customerCurrency->is_primary != 1) ){
                        $loyalty_amount_saved = $loyalty_amount_saved * $customerCurrency->doller_compare;
                    }
                }
            }
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $user->id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }

            $cart->scheduled_date_time = convertDateTimeInTimeZone($cart->scheduled_date_time, $user->timezone, 'Y-m-d\TH:i');
        }
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = $deliver_charges_lalmove = 0.00;
        if ($cartData) {

            $cart_dinein_table_id = NULL;
            $action = (Session::has('vendorType')) ? Session::get('vendorType') : 'delivery';
            $vendor_details = [];
            $delivery_status = 1;
            $is_vendor_closed = 0;
            $closed_store_order_scheduled = 0;
            $deliver_charge = 0;
            $deliveryCharges = 0;
            $delay_date = 0;
            $pickup_delay_date = 0;
            $dropoff_delay_date = 0;
            $total_service_fee = 0;
            $product_out_of_stock = 0;
            $PromoFreeDeliver = 0;
            $PromoDelete = 0;
            $d = 0;
            foreach ($cartData as $ven_key => $vendorData) {
                $is_promo_code_available = 0;
                $vendor_products_total_amount = $payable_amount = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = $delivery_fee_charges_static =  $deliver_charges_lalmove = 0.00;
                $delivery_count = 0;
                $delivery_count_lm = 0;
                $coupon_amount_used = 0;

                if(Session::has('vendorTable')){
                    if((Session::has('vendorTableVendorId')) && (Session::get('vendorTableVendorId') == $vendorData->vendor_id)){
                        $cart_dinein_table_id = Session::get('vendorTable');
                    }
                    Session::forget(['vendorTable', 'vendorTableVendorId']);
                }else{
                    $cart_dinein_table_id = $vendorData->vendor_dinein_table_id;
                }

                if($action != 'delivery'){
                    $vendor_details['vendor_address'] = $vendorData->vendor->select('id','latitude','longitude','address')->where('id', $vendorData->vendor_id)->first();
                    if($action == 'dine_in'){
                        $vendor_tables = VendorDineinTable::where('vendor_id', $vendorData->vendor_id)->with('category')->get();
                        foreach ($vendor_tables as $vendor_table) {
                            $vendor_table->qr_url = url('/vendor/'.$vendorData->vendor->slug.'/?id='.$vendorData->vendor_id.'&name='.$vendorData->vendor->name.'&table='.$vendor_table->id);
                        }
                        $vendor_details['vendor_tables'] = $vendor_tables;
                    }
                }
                else{
                    if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
                        if($address_id > 0){

                            if (!empty($latitude) && !empty($longitude)) {
                                $serviceArea = $vendorData->vendor->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                                    $query->select('vendor_id')
                                ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                                })->where('id', $vendorData->vendor_id)->get();
                            }
                        }
                    }
                }
                Session()->put('vid','');
                foreach ($vendorData->vendorProducts as $ven_key => $prod) {
                    if($prod->product->sell_when_out_of_stock == 0 && $prod->product->has_inventory == 1){
                        $quantity_check = productvariantQuantity($prod->variant_id);
                        if($quantity_check < $prod->quantity ){
                            $delivery_status = 0;
                            $product_out_of_stock = 1;
                        }
                    }

                    if($cart_dinein_table_id > 0){
                        $prod->update(['vendor_dinein_table_id' => $cart_dinein_table_id]);
                    }
                    $prod->product_out_of_stock =  $product_out_of_stock;

                    $quantity_price = 0;
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant->price;
                    $price_in_doller_compare = $prod->pvariant->price;
                    if($customerCurrency){
                        $price_in_currency = $prod->pvariant->price / $divider;
                        $price_in_doller_compare = $price_in_currency * $customerCurrency->doller_compare;
                    }
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $prod->pvariant->price_in_cart = $prod->pvariant->price;
                    $prod->pvariant->price = number_format($price_in_currency, 2, '.', '');
                    $prod->image_url = $this->loadDefaultImage();
                    $prod->pvariant->media_one = isset($prod->pvariant->media) ? $prod->pvariant->media->first() : [];
                    $prod->pvariant->media_second = isset($prod->product->media) ? $prod->product->media->first() : [];
                    $prod->pvariant->multiplier = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                    $prod->quantity_price = number_format($quantity_price, 2, '.', '');
                    $payable_amount = $payable_amount + $quantity_price;
                    $vendor_products_total_amount = $vendor_products_total_amount + $quantity_price;

                    $taxData = array();
                    if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                        foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                            $rate = round($tax_value->tax_rate);
                            $tax_amount = ($price_in_doller_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            $taxData[$tckey]['rate'] = $rate;
                            $taxData[$tckey]['tax_amount'] = number_format($tax_amount, 2, '.', '');
                            $taxData[$tckey]['product_tax'] = number_format($product_tax, 2, '.', '');
                            $taxable_amount = $taxable_amount + $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                        unset($prod->product->taxCategory);
                    }
                    $prod->taxdata = $taxData;
                    if($prod->addon->isNotEmpty()){
                        foreach ($prod->addon as $ck => $addons) {
                            if(isset($addons->option)){
                                $opt_price_in_currency = $addons->option->price;
                                $opt_price_in_doller_compare = $addons->option->price;
                                if($customerCurrency){
                                    $opt_price_in_currency = $addons->option->price / $divider;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $customerCurrency->doller_compare;
                                }
                                $opt_quantity_price = number_format($opt_price_in_doller_compare * $prod->quantity, 2, '.', '');
                                $addons->option->price_in_cart = $addons->option->price;
                                $addons->option->price = number_format($opt_price_in_currency, 2, '.', '');
                                $addons->option->multiplier = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                                $addons->option->quantity_price = $opt_quantity_price;
                                $payable_amount = $payable_amount + $opt_quantity_price;
                            }
                        }
                    }
                    if (isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)) {
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    } else {
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }

                    if($prod->product->delay_hrs_min != 0){
                        if($prod->product->delay_hrs_min > $delay_date)
                        $delay_date = $prod->product->delay_hrs_min;
                    }
                    if($prod->product->pickup_delay_hrs_min != 0){
                        if($prod->product->pickup_delay_hrs_min > $delay_date)
                        $pickup_delay_date = $prod->product->pickup_delay_hrs_min;
                    }

                    if($prod->product->dropoff_delay_hrs_min != 0){
                        if($prod->product->dropoff_delay_hrs_min > $delay_date)
                        $dropoff_delay_date = $prod->product->dropoff_delay_hrs_min;
                    }
                    $select = '';
                      
                    if($action == 'delivery'){
                        $delivery_fee_charges = 0;
                        $deliver_charges_lalmove =0;
                        $deliveryCharges = 0;
                         $code = (($code)?$code:$cart->shipping_delivery_type);
                        if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {
                            $deliveries = $this->getDeliveryOptions($vendorData,$preferences,$payable_amount);
                           if(isset($deliveries[0]))
                           {
                            $select .= '<select name="vendorDeliveryFee" class="form-control delivery-fee select">'; 
                            foreach($deliveries as $k=> $opt)
                                {
                                    $select .= '<option value="'.$opt['code'].'" '.(($opt['code']==$code)?'selected':'').'  >'.__($opt['courier_name']).' '.$opt['rate'].'</option>'; 
                                }
                            $select .= '</select>'; 
                                if($code){
                                    $new = array_filter($deliveries, function ($var) use ($code) {
                                        return ($var['code'] == $code);
                                    });
                                    foreach($new as $rate){
                                        $deliveryCharges = $rate['rate'];
                                    }
                                    if($deliveryCharges)
                                    {
                                        $deliveryCharges = $rate['rate'];
                                    }else{
                                        $deliveryCharges = $deliveries[0]['rate'];
                                        $code = $deliveries[0]['code'];
                                    }

                                }else{
                                    $deliveryCharges = $deliveries[0]['rate'];
                                    $code = $deliveries[0]['code'];
                                }
                            }

                    if(isset($deliveryCharges) && !empty($deliveryCharges)){
                            $dtype = explode('_',$code);
                            CartDeliveryFee::updateOrCreate(['cart_id' => $cart->id, 'vendor_id' => $vendorData->vendor->id],['delivery_fee' => $deliveryCharges,'shipping_delivery_type' => $dtype[0]??'D','courier_id'=>$dtype[1]??'0']);
                    }
                    
                    }//End Check last time stone 
                        
                }

                    $product = Product::with([
                        'variant' => function ($sel) {
                            $sel->groupBy('product_id');
                        },
                        'variant.media.pimage.image', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        }])->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating','minimum_order_count','batch_count')
                        ->where('url_slug', $prod->product->url_slug)
                        ->where('is_live', 1)
                        ->first();
                    $doller_compare = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                    $up_prods = $this->metaProduct($langId, $doller_compare, 'upSell', $product->upSell);
                    if($up_prods){
                        $upSell_products->push($up_prods);
                    }
                    $cross_prods = $this->metaProduct($langId, $doller_compare, 'crossSell', $product->crossSell);
                    if($cross_prods){
                        $crossSell_products->push($cross_prods);
                    }
                }

                $couponGetAmount = $payable_amount ;
                if (isset($vendorData->coupon) && !empty($vendorData->coupon) ) {
                    if (isset($vendorData->coupon->promo) && !empty($vendorData->coupon->promo)) {
                        if($vendorData->coupon->promo->first_order_only==1){
                            if(Auth::user()){
                                $userOrder = auth()->user()->orders->first();
                                if($userOrder){
                                    $cart->coupon()->delete();
                                    $vendorData->coupon()->delete();
                                    unset($vendorData->coupon);
                                    $PromoDelete =1;
                                }
                            }
                        }
                        if ($PromoDelete !=1) {
                            if(!($vendorData->coupon->promo->expiry_date >= $nowdate) ){
                                $cart->coupon()->delete();
                                $vendorData->coupon()->delete();
                                unset($vendorData->coupon);
                                $PromoDelete =1;
                            }
                        }
                        if ( $PromoDelete !=1) {

                            $minimum_spend = 0;
                            if (isset($vendorData->coupon->promo->minimum_spend)) {
                                $minimum_spend = $vendorData->coupon->promo->minimum_spend * $doller_compare;
                            }

                            $maximum_spend = 0;
                            if (isset($vendorData->coupon->promo->maximum_spend)) {
                                $maximum_spend = $vendorData->coupon->promo->maximum_spend * $doller_compare;
                            }

                            if( ($minimum_spend <= $payable_amount ) && ($maximum_spend >= $payable_amount)    )
                            {
                                if ($vendorData->coupon->promo->promo_type_id == 2) {
                                    $total_discount_percent = $vendorData->coupon->promo->amount;

                                    $payable_amount -= $total_discount_percent;
                                    $coupon_amount_used = $total_discount_percent;
                                } else {
                                    $gross_amount = number_format(($payable_amount - $taxable_amount), 2, '.', '');
                                    $percentage_amount = ($gross_amount * $vendorData->coupon->promo->amount / 100);
                                    $payable_amount -= $percentage_amount;
                                    $coupon_amount_used = $percentage_amount;
                                }
                            }
                            else{

                                $cart->coupon()->delete();
                                $vendorData->coupon()->delete();
                                unset($vendorData->coupon);
                                $PromoDelete =1;
                            }
                        }
                        if ( $PromoDelete !=1) {
                            if($vendorData->coupon->promo->allow_free_delivery ==1   ){
                                $PromoFreeDeliver = 1;
                                $coupon_amount_used = $coupon_amount_used +  $deliveryCharges;
                                $payable_amount = $payable_amount - $deliveryCharges;
                            }
                        }
                    }
                }

                $promoCodeController = new PromoCodeController();
                $promoCodeRequest = new Request();
                $promoCodeRequest->setMethod('POST');
                $promoCodeRequest->request->add(['vendor_id' => $vendorData->vendor_id,'amount' => $couponGetAmount,'is_cart' => 1]);
                $promoCodeResponse = $promoCodeController->postPromoCodeList($promoCodeRequest)->getData();
                if($promoCodeResponse->status == 'Success'){
                    if(!empty($promoCodeResponse->data)){
                        $is_promo_code_available = 1;
                    }
                }
                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $deliveryCharges;
                }
               // pr($PromoFreeDeliver);

                 $subtotal_amount = $payable_amount;
                // if($PromoFreeDeliver != 1){
                    $payable_amount = $payable_amount + $deliveryCharges;
                //}
                //$payable_amount = $payable_amount + $deliver_charge;
                //Start applying service fee on vendor products total

                $slotsDate = findSlot('',$vendorData->vendor->id,'');
                $vendorData->delaySlot = (($slotsDate)?$slotsDate:'');

                $vendor_service_fee_percentage_amount = 0;
                if($vendorData->vendor->service_fee_percent > 0){
                    $vendor_service_fee_percentage_amount = ($vendor_products_total_amount * $vendorData->vendor->service_fee_percent) / 100 ;
                    $payable_amount = $payable_amount + $vendor_service_fee_percentage_amount;
                }


                //end applying service fee on vendor products total
                $total_service_fee = $total_service_fee + $vendor_service_fee_percentage_amount;
                $vendorData->coupon_amount_used = number_format($coupon_amount_used, 2, '.', '');
                $vendorData->service_fee_percentage_amount = number_format($vendor_service_fee_percentage_amount, 2, '.', '');
                $vendorData->delivery_fee_charges = number_format($delivery_fee_charges, 2, '.', '');
                //$vendorData->delivery_fee_charges_static = number_format($delivery_fee_charges_static, 2, '.', '');;
               
                $vendorData->payable_amount = number_format($payable_amount, 2, '.', '');
                $vendorData->discount_amount = number_format($discount_amount, 2, '.', '');
                $vendorData->discount_percent = number_format($discount_percent, 2, '.', '');
                $vendorData->taxable_amount = number_format($taxable_amount, 2, '.', '');
                $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount), 2, '.', '');
                $vendorData->product_sub_total_amount = number_format($subtotal_amount, 2, '.', '');
                $vendorData->isDeliverable = 1;
                $vendorData->promo_free_deliver = $PromoFreeDeliver;
                $vendorData->is_vendor_closed = $is_vendor_closed;
                $slotsDate = findSlot('',$vendorData->vendor->id,'');
                $vendorData->delaySlot = (($slotsDate)?$slotsDate:'');
                $vendorData->closed_store_order_scheduled = (($slotsDate)?$product->vendor->closed_store_order_scheduled:0);
                $vendorData->delOptions = $select;
                
                if(isset($serviceArea)){
                    if($serviceArea->isEmpty()){
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }
                }
                if($vendorData->vendor->show_slot == 0){
                    if( ($vendorData->vendor->slotDate->isEmpty()) && ($vendorData->vendor->slot->isEmpty()) ){
                        $vendorData->is_vendor_closed = 1;
                        if($delivery_status != 0){
                            $delivery_status = 0;
                        }
                    }else{
                        $vendorData->is_vendor_closed = 0;
                    }
                }
                if($vendorData->vendor->$action == 0){
                    $vendorData->is_vendor_closed = 1;
                    $delivery_status = 0;
                }

                if((float)($vendorData->vendor->order_min_amount) > $subtotal_amount){  # if any vendor total amount of order is less then minimum order amount
                    $delivery_status = 0;
                }





                $total_payable_amount = $total_payable_amount + $payable_amount;
                $total_taxable_amount = $total_taxable_amount + $taxable_amount;
                $total_discount_amount = $total_discount_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;


                $vendorData->is_promo_code_available = $is_promo_code_available;
            }

            $is_percent = 0;
            $amount_value = 0;
            if ($cart->coupon) {
                foreach ($cart->coupon as $ck => $coupon) {
                    if (isset($coupon->promo)) {
                        if ($coupon->promo->promo_type_id == 1) {
                            $is_percent = 1;
                            $total_discount_percent = $total_discount_percent + round($coupon->promo->amount);
                        }
                    }
                }
            }
            if ($is_percent == 1) {
                $total_discount_percent = ($total_discount_percent > 100) ? 100 : $total_discount_percent;
                $total_discount_amount = $total_discount_amount + ($total_payable_amount * $total_discount_percent) / 100;
            }
            if ($amount_value > 0) {
                if($customerCurrency){
                    $amount_value = $amount_value * $customerCurrency->doller_compare;
                }
                $total_discount_amount = $total_discount_amount + $amount_value;
            }
            if (!empty($subscription_features)) {
                $total_discount_amount = $total_discount_amount + $total_subscription_discount;
                $cart->total_subscription_discount = number_format($total_subscription_discount, 2, '.', '');
            }
            $total_payable_amount = $total_payable_amount - $total_discount_amount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $total_payable_amount) {
                    $loyalty_amount_saved =  $total_payable_amount;
                }
                $total_payable_amount = $total_payable_amount - $loyalty_amount_saved;
            }
            $wallet_amount_used = 0;
            if($user){
                if($user->balanceFloat > 0){
                    $wallet_amount_used = $user->balanceFloat;
                    if($customerCurrency){
                        $wallet_amount_used = $user->balanceFloat * $customerCurrency->doller_compare;
                    }
                    if($wallet_amount_used > $total_payable_amount){
                        $wallet_amount_used = $total_payable_amount;
                    }
                    $total_payable_amount = $total_payable_amount - $wallet_amount_used;
                    $cart->wallet_amount_used = number_format($wallet_amount_used, 2, '.', '');
                }
            }

            $scheduled = (object)array(
                'scheduled_date_time'=>(($cart->scheduled_slot)?date('Y-m-d',strtotime($cart->scheduled_date_time)):$cart->scheduled_date_time),'slot'=>$cart->scheduled_slot,
            );
            $cart->deliver_status = $delivery_status;
            $cart->vendorCnt = $cartData->count();
            $cart->scheduled = $scheduled;
            $cart->schedule_type =  $cart->schedule_type;
            $cart->closed_store_order_scheduled =  0;
            $myDate = date('Y-m-d');
            if($cart->vendorCnt==1){
                $vendorId = $cartData[0]->vendor_id;
                //type must be a : delivery , takeaway,dine_in
                $duration = Vendor::where('id',$vendorId)->select('slot_minutes','closed_store_order_scheduled')->first();
                $closed_store_order_scheduled = (($slotsDate)?$duration->closed_store_order_scheduled:0);
                if($cart->deliver_status == 0 && $closed_store_order_scheduled == 1)
                {
                    $cart->deliver_status = $duration->closed_store_order_scheduled;
                    $cart->closed_store_order_scheduled = $duration->closed_store_order_scheduled;
                    $myDate = date('Y-m-d',strtotime($cart->scheduled_date_time));
                    $sttime =  strtotime($myDate);
                    $todaytime =  strtotime(date('Y-m-d'));
                    if($todaytime == $sttime){$sttime =  strtotime('+1 day',$sttime);}
                    $myDate = (($myDate)?date('Y-m-d',$sttime):date('Y-m-d',strtotime('+1 day')));
                    $cart->schedule_type =  'schedule';
                    //$cart->closed_store_order_scheduled =  1;
                }
                $slots = (object)showSlot($myDate,$vendorId,'delivery',$duration->slot_minutes);
                if(count((array)$slots) == 0){
                    $myDate  = date('Y-m-d',strtotime('+1 day'));
                    $slots = (object)showSlot($myDate,$vendorId,'delivery',$duration->slot_minutes);
                }
                if(count((array)$slots) == 0){
                    $myDate  = date('Y-m-d',strtotime('+1 day'));
                    $slots = (object)showSlot($myDate,$vendorId,'delivery',$duration->slot_minutes);
                }

                if(count((array)$slots) == 0){
                    $myDate  = date('Y-m-d',strtotime('+3 day'));
                    $slots = (object)showSlot($myDate,$vendorId,'delivery',$duration->slot_minutes);
                }
                $cart->slots = $slots;
                $cart->vendor_id =  $vendorId;

            }else{
                $slots = [];
                $cart->slots = [];
                $cart->vendor_id =  0;
            }
            $cart->slotsCnt = count((array)$slots);
            $cart->total_service_fee = number_format($total_service_fee, 2, '.', '');
            $cart->loyalty_amount = number_format($loyalty_amount_saved, 2, '.', '');
            $cart->gross_amount = number_format(($total_payable_amount + $total_discount_amount + $loyalty_amount_saved + $wallet_amount_used - $total_taxable_amount), 2, '.', '');
            $cart->new_gross_amount = number_format(($total_payable_amount + $total_discount_amount), 2, '.', '');
            $cart->total_payable_amount = number_format($total_payable_amount, 2, '.', '');
            $cart->total_discount_amount = number_format($total_discount_amount, 2, '.', '');
            $cart->total_taxable_amount = number_format($total_taxable_amount, 2, '.', '');
            $cart->tip_5_percent = number_format((0.05 * $total_payable_amount), 2, '.', '');
            $cart->tip_10_percent = number_format((0.1 * $total_payable_amount), 2, '.', '');
            $cart->tip_15_percent = number_format((0.15 * $total_payable_amount), 2, '.', '');


            $cart->action = $action;
            $cart->left_section = view('frontend.cartnew-left')->with(['action' => $action,  'vendor_details' => $vendor_details, 'addresses'=> $user_allAddresses, 'countries'=> $countries, 'cart_dinein_table_id'=> $cart_dinein_table_id, 'preferences' => $preferences])->render();
            $cart->upSell_products = ($upSell_products) ? $upSell_products->first() : collect();
            $cart->crossSell_products = ($crossSell_products) ? $crossSell_products->first() : collect();
            $cart->scheduled_date_time = $myDate;
            if($cart->slotsCnt>0){
                $mdate = (object)findSlotNew('',$cart->vendor_id,'');
                $cart->delay_date =  $mdate->mydate;
             }else{
                $cart->delay_date =  $delay_date??0;
            }


            $cart->pickup_delay_date =  $pickup_delay_date??0;
            $cart->dropoff_delay_date =  $dropoff_delay_date??0;
            $cart->delivery_type =  $code??'D';

            // dd($cart->toArray());
            $cart->products = $cartData->toArray();
        }
        return $cart;
    }


    public function checkScheduleSlots(Request $request)
    {
        $message = '';
        $status = 'Success';
        $vendorId = $request->vendor_id??0;
        $option = "";
        //type must be a : delivery , takeaway,dine_in
        $duration = Vendor::where('id',$vendorId)->select('slot_minutes')->first();
        $slots = (object)showSlot($request->date,$vendorId,'delivery',$duration->slot_minutes);
        $option ="<option value=''>".__("Select Slot")."</option>";
        if(count((array)$slots)<=0){
            $message = 'Slot not found.';
            $status = 'error';
        }else{
            foreach($slots as $opt)
            {
                $option .="<option value='".$opt['value']."'>".$opt['name']."</option>";
            }
        }
        $data = array('status'=>$status,'data'=>$option,'message'=>$message);
        return response()->json($data);
    }

    /**
     * Show Main Cart
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * Get Last added product variant
     *
     * @return \Illuminate\Http\Response
     */
    public function getLastAddedProductVariant(Request $request, $domain='')
    {
        try{
            $cartProduct = CartProduct::with('addon')
                ->where('cart_id', $request->cart_id)
                ->where('product_id', $request->product_id)
                ->orderByDesc('created_at')->first();

            return $this->successResponse($cartProduct, '', 200);
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Get current product variants with different addons
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductVariantWithDifferentAddons(Request $request, $domain='')
    {
        try{
            $langId = Session::get('customerLanguage');
            $cur_ids = Session::get('customerCurrency');
            if(isset($cur_ids) && !empty( $cur_ids)){
                $clientCurrency = ClientCurrency::where('currency_id','=', $cur_ids)->first();
            }else{
                $clientCurrency = ClientCurrency::where('is_primary','=', 1)->first();
            }

            $cartProducts = CartProduct::with(['product.translation' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId)->groupBy('product_translations.language_id');
            },
            //  'addon.option.translation' => function ($qry) use ($langId) {
            //     $qry->where('language_id', $langId)->groupBy('addon_option_translations.language_id');
            // }, 'addon.set' => function ($qry) use ($langId) {
            //     $qry->where('language_id', $langId)->groupBy('addon_sets.id');
            // },
            'product.media.image',
            'pvariant.media.pimage.image',
            'vendor.slot.day', 'vendor.slotDate',
            ])
            ->where('cart_id', $request->cart_id)
            ->where('product_id', $request->product_id)
            ->select('*','id as add_on_set_and_option')->orderByDesc('created_at')->get();

            $multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
            foreach ($cartProducts as $key => $cart) {
                $cart->is_vendor_closed = 0;
                $cart->variant_multiplier = $multiplier;
                $variant_price = ($cart->pvariant) ? ($cart->pvariant->price * $multiplier) : 0;

                $product = $cart->product;
                $product->translation_title = ($product->translation->isNotEmpty()) ? $product->translation->first()->title : $product->sku;

                if($cart->pvariant && $cart->pvariant->media->isNotEmpty()){
                    $image_fit = $cart->pvariant->media->first()->pimage->image->path['image_fit'];
                    $image_path = $cart->pvariant->media->first()->pimage->image->path['image_path'];
                    $product->product_image =$image_path;
                }elseif($product->media->isNotEmpty()){
                    $image_fit = $product->media->first()->image->path['image_fit'];
                    $image_path = $product->media->first()->image->path['image_path'];
                    $product->product_image =$image_path;
                }else{
                    $product->product_image = $this->loadDefaultImage();
                }

                $addon_set = $cart->add_on_set_and_option;
                foreach ($addon_set as $skey => $set) {
                    $set->addon_set_translation_title = ($set->translation->isNotEmpty()) ? $set->translation->first()->title : $set->title;
                    foreach ($set->options as $okey => $option) {
                        $option->option_translation_title = ($option->translation->isNotEmpty()) ? $option->translation->first()->title : $option->title;
                        $opt_price_in_doller_compare = $option->price * $multiplier;
                        $variant_price = $variant_price + $opt_price_in_doller_compare;
                    }
                }
                $cart->variant_price = $variant_price;
                $cart->addon_set = $addon_set;
                $cart->total_variant_price = number_format($cart->quantity * $variant_price, 2, '.', '');

                if($cart->vendor->show_slot == 0){
                    if( ($cart->vendor->slotDate->isEmpty()) && ($cart->vendor->slot->isEmpty()) ){
                        $cart->is_vendor_closed = 1;
                    }else{
                        $cart->is_vendor_closed = 0;
                    }
                }
                unset($cartProducts[$key]->add_on_set_and_option);
            }

            $returnHTML = view('frontend.product-with-different-addons-modal')->with(['cartProducts'=>$cartProducts])->render();
            return $this->successResponse($returnHTML, '', 200);
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Update Quantityt
     *
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity($domain = '', Request $request)
    {
        $cartProduct = CartProduct::find($request->cartproduct_id);
        $variant_id = $cartProduct->variant_id;
        $productDetail = Product::with([
            'variant' => function ($sel) use($variant_id) {
                $sel->where('id', $variant_id);
                $sel->groupBy('product_id');
            }
        ])->find($cartProduct->product_id);

        if( ($productDetail->category->categoryDetail->type_id != 8) && ($productDetail->has_inventory == 1)  && ($productDetail->sell_when_out_of_stock == 0) ){
            if($productDetail->variant[0]->quantity < $request->quantity){
                return response()->json(['status' => 'error', 'message' => __('Maximum quantity already added in your cart')]);
            }

        }

        $cartProduct->quantity = $request->quantity;
        $cartProduct->save();

        return response()->json("Successfully Updated");
    }

    /**
     * Delete Cart Product
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCartProduct($domain = '', Request $request)
    {
        CartProduct::where('id', $request->cartproduct_id)->delete();
        CartCoupon::where('vendor_id', $request->vendor_id)->delete();
        CartAddon::where('cart_product_id', $request->cartproduct_id)->delete();
        return response()->json(['status' => 'success', 'message' => __('Product removed from cart successfully.') ]);
    }

    /**
     * Empty Cart
     *
     * @return \Illuminate\Http\Response
     */
    public function emptyCartData($domain = '', Request $request)
    {
        $cart_id = $request->cart_id;
        if (($cart_id != '') && ($cart_id > 0)) {
            // Cart::where('id', $cart_id)->delete();
            CartProduct::where('cart_id', $cart_id)->delete();
            CartCoupon::where('cart_id', $cart_id)->delete();
            CartAddon::where('cart_id', $cart_id)->delete();


            return response()->json(['status' => 'success', 'message' => 'Cart has been deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Cart cannot be deleted.']);
        }
    }


    public function repeatOrder($domain = '', Request $request){

        $order_vendor_id = $request->order_vendor_id;
        $cart_id = $request->cart_id;
        $getallproduct = OrderProduct::where('order_vendor_id',$order_vendor_id)->get();

        if(isset($cart_id) && !empty($cart_id)){
            CartProduct::where('cart_id', $cart_id)->delete();
            CartCoupon::where('cart_id', $cart_id)->delete();
            CartAddon::where('cart_id', $cart_id)->delete();
        }




        foreach($getallproduct as $data){
            $request->vendor_id = $data->vendor_id;
            $request->product_id = $data->product_id;
            $request->quantity = $data->quantity;
            $request->variant_id = $data->variant_id;

            $addonID = OrderProductAddon::where('order_product_id',$data->id)->pluck('addon_id');
            $addonoptID = OrderProductAddon::where('order_product_id',$data->id)->pluck('option_id');

            if(count($addonID))
            $request->request->add(['addonID' => $addonID->toArray()]);

            if(count($addonoptID))
            $request->request->add(['addonoptID' => $addonoptID->toArray()]);

            $this->postAddToCart($request);

        }

        return response()->json(['status' => 'success', 'message' => 'Order added to cart.','cart_url' => route('showCart')]);



    }

    /**
     * Delete Cart Product
     *
     * @return \Illuminate\Http\Response
     */
     public function getCartData(Request $request)
    {   



        $cart_details = [];
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $address_id = 0;
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time','schedule_pickup','schedule_dropoff','scheduled_slot','shipping_delivery_type')
            ->with('coupon.promo')->where('client_id',$this->client_id)
            ->where('status', '0')->where('user_id', $user->id)->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time','schedule_pickup','schedule_dropoff','scheduled_slot','shipping_delivery_type')
            ->with('coupon.promo')->where('client_id',$this->client_id)
            ->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
        }


        if (isset($request->address_id) && !empty($request->address_id)) {
            $address_id = $request->address_id;
            $address = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->update(['is_primary' => 1]);
        }



 
        if ($cart) {
         $cart_details = $this->getCart($cart, $address_id,$request->code);
        }
        $client_preference_detail = $this->ClientPreferencs;

        $expected_vendors = [];
    //    $expected_vendors = $this->searchProductExpection($cart_details);
        $expected_vendor_html = '';
        // if(count($expected_vendors))
        // {
        //     $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();

        //     $expected_vendor_html = view('frontend.modals.expected_vendor_pricing')->with(['expected_vendors'=>$expected_vendors,'clientCurrency' => $clientCurrency])->render();
        // }
// return $cart_details;
        return response()->json(['status' => 'success', 'cart_details' => $cart_details, 'expected_vendor_html' => $expected_vendor_html,'expected_vendors' => $expected_vendors, 'client_preference_detail' => $client_preference_detail]);
    }


    public function searchProductExpection($cart_details){

       $langId = Session::get('customerLanguage');

        $all_vendors = array();
        $keywords = array();
            foreach($cart_details->products as $product)
            {
                foreach ($product['vendor_products'] as $vendor_product) {
                    $keywords[] = isset($vendor_product['product']['translation_one']) ? $vendor_product['product']['translation_one']['title'] :  $vendor_product['product']['sku'];
                }
            }

            $all_vendors = Vendor::OrderBy('id','desc')->with(['products' => function($q) use($langId, $keywords){
                $q->whereHas('translation',function($q) use($langId, $keywords){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId)->whereIn('title', $keywords);
                    }
                )->with('media.image','variant');
            }])->whereHas('products.translation',function($q) use($langId, $keywords){
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId)->whereIn('title', $keywords);
            }
            )->where('status',1)->get();


            return $all_vendors;
    }



        //Fetch all delivery fee option
        public function getDeliveryOptions($vendorData,$preferences,$payable_amount)
        {
            $option = array(); 
            $delivery_count = 0;
            try {
                if($vendorData->vendor_id)
                {
                   Session()->put('vid',$vendorData->vendor_id);
                
            if($preferences->static_delivey_fee != 1)
            {
                //Dispatcher Delivery changes code
                $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                if (!empty($deliver_charge)){
                    $deliver_charge = number_format($deliver_charge, 2, '.', '');
                    $option[] = array(
                        'type'=>'D',
                        'courier_name'=>__('Dispatcher, Rate :'),
                        'rate' => $deliver_charge,
                        'courier_company_id' => 0,
                        'etd' => 0, 
                        'etd_hours' => 0,
                        'estimated_delivery_days' => 0,
                        'code' => 'D_0'
                    );
                }
         
            
            //Lalamove Delivery changes code
            $lalamove = new LalaMovesController();
            $deliver_lalmove_fee = $lalamove->getDeliveryFeeLalamove($vendorData->vendor_id);
            if($deliver_lalmove_fee>0)
            {   
                $deliver_charge_lalamove = number_format($deliver_lalmove_fee, 2, '.', '');
            
                $optionLala[] = array(
                    'type'=>'L',
                    'courier_name'=>__('Lalamove, Rate :'),
                    'rate' => $deliver_charge_lalamove,
                    'courier_company_id' => 0,
                    'etd' => 0,
                    'etd_hours' => 0,
                    'estimated_delivery_days' => 0,
                    'code' => 'L_0'
                );
                $option = array_merge($option,$optionLala);
            }
            //End Lalamove Delivery changes code

            //getShiprocketFee Delivery changes code
            $ship = new ShiprocketController();
            $deliver_ship_fee = $ship->getCourierService($vendorData->vendor_id);
            //dd($deliver_ship_fee);
            if($deliver_ship_fee)
            {  
                $option = array_merge($option,$deliver_ship_fee);
            }
            
        }elseif($preferences->static_delivey_fee == 1 &&  $vendorData->vendor->order_amount_for_delivery_fee != 0){
             # for static fees 
           
                if( $payable_amount >= (float)($vendorData->vendor->order_amount_for_delivery_fee)){ 
                    $deliveryCharges = number_format($vendorData->vendor->delivery_fee_maximum, 2, '.', '');
                }elseif($payable_amount < (float)($vendorData->vendor->order_amount_for_delivery_fee)){
                    $deliveryCharges = number_format($vendorData->vendor->delivery_fee_minimum, 2, '.', '');
                }

                $option[] = array(
                    'type'=>'S',
                    'courier_name'=>__('Static, Rate :'),
                    'rate' => $deliveryCharges,
                    'courier_company_id' => 0,
                    'etd' => 0,
                    'etd_hours' => 0,
                    'estimated_delivery_days' => 0,
                    'code' => 'S_0'
                );

           }//End statis fe code

          }
        } catch (\Exception $e) {
        }
        return $option;
    }




    # get delivery fee from dispatcher
    public function getDeliveryFeeDispatcher($vendor_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address) {
                    $tasks = array();
                    $vendor_details = Vendor::find($vendor_id);
                    $location[] = array(
                        'latitude' => $vendor_details->latitude ?? 30.71728880,
                        'longitude' => $vendor_details->longitude ?? 76.80350870
                    );
                    $location[] = array(
                        'latitude' => $cus_address->latitude ?? 30.717288800000,
                        'longitude' => $cus_address->longitude ?? 76.803508700000
                    );
                    $postdata =  ['locations' => $location];
                    $client = new GClient([
                        'headers' => [
                            'personaltoken' => $dispatch_domain->delivery_service_key,
                            'shortcode' => $dispatch_domain->delivery_service_key_code,
                            'content-type' => 'application/json'
                        ]
                    ]);
                    $url = $dispatch_domain->delivery_service_key_url;
                    $res = $client->post(
                        $url . '/api/get-delivery-fee',
                        ['form_params' => ($postdata)]
                    );
                    $response = json_decode($res->getBody(), true);
                    if ($response && $response['message'] == 'success') {
                        return $response['total'];
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
    # check if last mile delivery on
    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }

    public function uploadPrescription(Request $request, $domain = '')
    {
        $user = Auth::user();
        if ($user) {
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            foreach ($request->prescriptions as $prescription) {
                $cart_product_prescription = new CartProductPrescription();
                $cart_product_prescription->cart_id = $cart->id;
                $cart_product_prescription->vendor_id = $request->vendor_idd;
                $cart_product_prescription->product_id = $request->product_id;
                $cart_product_prescription->prescription = Storage::disk('s3')->put('prescription', $prescription, 'public');
                $cart_product_prescription->save();
            }
        }
        return response()->json(['status' => 'success', 'message' => "Uploaded Successfully"]);
    }

    public function addVendorTableToCart(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user) {
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->firstOrFail();
                $cartData = CartProduct::where('cart_id', $cart->id)->where('vendor_id', $request->vendor)->update(['vendor_dinein_table_id' => $request->table]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Table has been selected']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    public function updateSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            $new_session_token = session()->get('_token');
            if ($user || $new_session_token) {
                if($request->task_type == 'now'){
                    $time = Carbon::now()->format('Y-m-d H:i:s');
                }else{

                    if(isset($request->slot))
                    {
                        $time = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                        $slot = $request->slot;
                    }else{
                        if(isset($request->schedule_dt) && !empty($request->schedule_dt))
                        $time = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                    }
                    
                }

                if(isset($request->schedule_pickup) && !empty($request->schedule_pickup))    # for pickup laundry
                $request->schedule_pickup = Carbon::parse($request->schedule_pickup, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');

                if(isset($request->schedule_dropoff) && !empty($request->schedule_dropoff))  # for pickup laundry
                $request->schedule_dropoff = Carbon::parse($request->schedule_dropoff, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');

                if ($user) {
                    $cart_detail = Cart::where('user_id', $user->id)->first();
                } else {
                    $cart_detail = Cart::where('unique_identifier', $new_session_token)->first();
                }
              

                $cart_detail = $cart_detail->update(['specific_instructions' => $request->specific_instructions??null,
                'schedule_type' => $request->task_type,
                'scheduled_date_time' => $time??null,
                'scheduled_slot' => $slot??null,
                'shipping_delivery_type' => $request->delivery_type??'D',
                'comment_for_pickup_driver' => $request->comment_for_pickup_driver??null,
                'comment_for_dropoff_driver' => $request->comment_for_dropoff_driver??null,
                'comment_for_vendor' => $request->comment_for_vendor??null,
                'schedule_pickup' => $request->schedule_pickup??null,
                'schedule_dropoff' => $request->schedule_dropoff??null]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Cart has been scheduled']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    # update schedule for home services basis on services
    public function updateProductSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user) {
                if($request->task_type == 'now'){
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                }else{
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }
                CartProduct::where('id', $request->cart_product_id)->update(['schedule_type' => $request->task_type, 'scheduled_date_time' => $request->schedule_dt]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Cart has been scheduled']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    // add ones add in cart for ondemand

    public function postAddToCartAddons(Request $request, $domain = '')
    {

        try {

            $user = Auth::user();
             $addon_ids = $request->addonID;
             $addon_options_ids = $request->addonoptID;
             $langId = Session::get('customerLanguage');

           $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addonID')){
                $addon_ids = $request->addonID;
            }
            if($request->has('addonoptID')){
                $addon_options = $request->addonoptID;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }

            if($request->has('addonoptID')){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                ->where('ast.language_id', $langId)
                ->where('addon_sets.status', '!=', '2')
                ->where('addon_sets.id', $request->addonID[0])->first();
             if (!$addon) {
                return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
            }
            if ($addon->min_select > count($request->addonID)) {
                return response()->json([
                    'error' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                    'data' => $addon
                ], 404);
            }
            if ($addon->max_select < count($request->addonID)) {
                return response()->json([
                    'error' => 'You can select maximum ' . $addon->max_select . ' options of ' . $addon->title,
                    'data' => $addon
                ], 404);
            }

            }

            if(isset($addon_ids) && !empty($addon_ids[0]))
            CartAddon::where('cart_id',$request->cart_id)->where('cart_product_id',$request->cart_product_id)->where('addon_id',$addon_ids[0])->delete();
            else
            CartAddon::where('cart_id',$request->cart_id)->where('cart_product_id',$request->cart_product_id)->delete();

                    if (count($addon_options) > 0) {
                        $saveAddons = array();
                        foreach ($addon_options as $key => $opts) {
                            $saveAddons[] = [
                            'option_id' => $opts,
                            'cart_id' => $request->cart_id,
                            'addon_id' => $addon_ids[$key],
                            'cart_product_id' => $request->cart_product_id,
                        ];
                        }
                        CartAddon::insert($saveAddons);
                    }





            return response()->json(['status' => 'success', 'message' => 'Addons Added Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function checkIsolateSingleVendor(Request $request, $domain=''){
        $preference = ClientPreference::first();
        $user = Auth::user();
        $new_session_token = session()->get('_token');
        if ($user) {
            $cart_detail = Cart::where('user_id', $user->id)->first();
        } else {
            $cart_detail = Cart::where('unique_identifier', $new_session_token)->first();
        }
        if ( (isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) && (!empty($cart_detail)) ) {
            $checkVendorId = CartProduct::where('vendor_id', '!=', $request->vendor_id)->where('cart_id', $cart_detail->id)->first();
            return response()->json(['status'=>'Success', 'otherVendorExists'=>($checkVendorId ? 1 : 0), 'isSingleVendorEnabled'=>1]);
        }else{
            return response()->json(['status'=>'Success', 'otherVendorExists'=>0 , 'isSingleVendorEnabled'=>0]);
        }
    }


    public function updateCartSlot(Request $request){
        $checkVendorProd = CartProduct::where('vendor_id',$request->vid)->update(['schedule_type'=>$request->slot,'scheduled_date_time'=>$request->date]);
            return true;
        }

}
