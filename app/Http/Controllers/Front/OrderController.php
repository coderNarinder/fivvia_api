<?php

namespace App\Http\Controllers\Front;

use DB;
use Log;
use Auth;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\ClientPreference;
use App\Models\Client as CP;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\EmailTemplate;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\OrderProductPrescription;
use App\Models\CartProduct;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderProductAddon;
use App\Models\Payment;
use App\Models\ClientCurrency;
use App\Models\OrderVendor;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\CartCoupon;
use App\Models\CartProductPrescription;
use App\Models\LoyaltyCard;
use App\Models\NotificationTemplate;
use App\Models\VendorOrderStatus;
use App\Models\OrderTax;
use App\Models\SubscriptionInvoicesUser;
use App\Models\UserDevice;
use App\Models\UserVendor;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\Page;
use App\Models\DriverRegistrationDocument;
use App\Models\LuxuryOption;
use App\Models\PaymentOption;
use App\Models\ProductVariantSet;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\AutoRejectOrderCron;
use App\Models\CartDeliveryFee;
use Redirect;
use App\Http\Controllers\Front\LalaMovesController;


class OrderController extends FrontController
{
    use ApiResponser;
    use \App\Http\Traits\OrderTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request, $domain = '')
    {
        $user = Auth::user();
        $currency_id = Session::get('customerCurrency');

        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $pastOrders = Order::with([
            'vendors' => function ($q) {
                $q->where('order_status_option_id', 6);
            },
            'vendors.dineInTable.translations' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendors.dineInTable.category', 'vendors.products', 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image', 'products.productRating', 'user', 'address'
        ])
            ->whereHas('vendors', function ($q) {
                $q->where('order_status_option_id', 6);
            })
            ->where(function ($q1) {
                $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                $q1->orWhere(function ($q2) {
                    $q2->where('payment_option_id', 1);
                });
            })
            ->where('orders.user_id', $user->id)
            ->orderBy('orders.id', 'DESC')->select('*', 'id as total_discount_calculate')->paginate(10);
        $activeOrders = Order::with([
            'vendors' => function ($q) {
                $q->where('order_status_option_id', '!=', 6);
                $q->where('order_status_option_id', '!=', 3);
            },
            'vendors.dineInTable.translations' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendors.dineInTable.category', 'vendors.products', 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image', 'user', 'address'
        ])
            ->whereHas('vendors', function ($q) {
                $q->where('order_status_option_id', '!=', 6);
                $q->where('order_status_option_id', '!=', 3);
            })
            ->where(function ($q1) {
                $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                $q1->orWhere(function ($q2) {
                    $q2->where('payment_option_id', 1);
                });
            })
            ->where('orders.user_id', $user->id)
            ->orderBy('orders.id', 'DESC')->select('*', 'id as total_discount_calculate')->paginate(10);
        foreach ($activeOrders as $order) {
            foreach ($order->vendors as $vendor) {
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();

                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';

                foreach ($vendor->products as $product) {
                    if ($product->pvariant->media->isNotEmpty()) {
                        $product->image_url =  $product->pvariant->media->first()->pimage->image->path['image_path'];
                    } elseif ($product->media->isNotEmpty()) {
                        $product->image_url = $product->media->first()->image->path['image_path'];
                    } else {
                        $product->image_url = ($product->image) ? $product->image['image_path'] : '';
                    }
                }
                if ($vendor->delivery_fee > 0) {
                    $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                    $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                    $ETA = $order_pre_time + $user_to_vendor_time;
                    // $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : convertDateTimeInTimeZone($vendor->created_at, $user->timezone, 'h:i A');
                    $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : dateTimeInUserTimeZone($vendor->created_at, $user->timezone);
                }
                if ($vendor->dineInTable) {
                    $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                    $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                    $vendor->dineInTableCategory = $vendor->dineInTable->category ? $vendor->dineInTable->category->title : '';
                }

                $vendor->vendor_dispatcher_status = VendorOrderDispatcherStatus::whereNotIn('dispatcher_status_option_id',[2])
                ->select('*','dispatcher_status_option_id as status_data')->where('order_id', $order->id)
                ->where('vendor_id', $vendor->vendor->id)
                ->get();
                $vendor->vendor_dispatcher_status_count = 6;
                $vendor->dispatcher_status_icons = [asset('assets/icons/driver_1_1.png'),asset('assets/icons/driver_2_1.png'),asset('assets/icons/driver_4_1.png'),asset('assets/icons/driver_3_1.png'),asset('assets/icons/driver_4_2.png'),asset('assets/icons/driver_5_1.png')];

            }
        }

        foreach ($pastOrders as $order) {
            foreach ($order->vendors as $vendor) {
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
                foreach ($vendor->products as $product) {
                    if ($product->pvariant->media->isNotEmpty()) {
                        $product->image_url =  $product->pvariant->media->first()->pimage->image->path['image_path'];
                    } elseif ($product->media->isNotEmpty()) {
                        $product->image_url =  $product->media->first()->image->path['image_path'];
                    } else {
                        $product->image_url = ($product->image) ? $product->image['image_path'] : '';
                    }
                }
                if ($vendor->dineInTable) {
                    $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                    $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                    $vendor->dineInTableCategory = $vendor->dineInTable->category ? $vendor->dineInTable->category->title : '';
                }
            }
        }
        $returnOrders = Order::with([
            'vendors.products.productReturn', 'products.productRating', 'user', 'address', 'products' => function ($q) {
                $q->whereHas('productReturn');
            }, 'vendors.dineInTable.translations' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendors.dineInTable.category', 'vendors.products' => function ($q) {
                $q->whereHas('productReturn');
            }, 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image',
            'vendors' => function ($q) {
                $q->whereHas('products.productReturn');
            }
        ])->whereHas('vendors.products.productReturn')
            ->where('orders.user_id', $user->id)->orderBy('orders.id', 'DESC')->paginate(20);
        foreach ($returnOrders as $order) {
            foreach ($order->vendors as $vendor) {
                foreach ($vendor->products as $product) {
                    if ($product->pvariant->media->isNotEmpty()) {
                        $product->image_url = $product->pvariant->media->first()->pimage->image->path['image_path'];
                    } elseif ($product->media->isNotEmpty()) {
                        $product->image_url = $product->media->first()->image->path['image_path'];
                    } else {
                        $product->image_url = ($product->image) ? $product->image['image_path'] : '';
                    }
                }
                if ($vendor->dineInTable) {
                    $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                    $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                    $vendor->dineInTableCategory = $vendor->dineInTable->category ? $vendor->dineInTable->category->title : '';
                }
            }
        }

        $rejectedOrders = Order::with([
            'vendors' => function ($q) {
                $q->where('order_status_option_id', 3);
            },
            'vendors.dineInTable.translations' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendors.dineInTable.category', 'vendors.products', 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image', 'products.productRating', 'user', 'address'
        ])
            ->whereHas('vendors', function ($q) {
                $q->where('order_status_option_id', 3);
            })
            ->where(function ($q1) {
                $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                $q1->orWhere(function ($q2) {
                    $q2->where('payment_option_id', 1);
                });
            })
            ->where('orders.user_id', $user->id)
            ->orderBy('orders.id', 'DESC')->select('*', 'id as total_discount_calculate')->paginate(10);

        foreach ($rejectedOrders as $order) {
            foreach ($order->vendors as $vendor) {
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
                foreach ($vendor->products as $product) {
                    if (isset($product->pvariant->media)) {
                        if ($product->pvariant->media->isNotEmpty()) {
                            $product->image_url = $product->pvariant->media->first()->pimage->image->path['image_path'];
                        } elseif ($product->media->isNotEmpty()) {
                            $product->image_url =  $product->media->first()->image->path['image_path'];
                        } else {
                            $product->image_url = ($product->image) ?  $product->image['image_path'] : '';
                        }
                    }
                }
                if ($vendor->dineInTable) {
                    $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                    $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                    $vendor->dineInTableCategory = $vendor->dineInTable->category ? $vendor->dineInTable->category->title : '';
                }
            }
        }
        // pr($rejectedOrders->toArray());
        // exit();

        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();

        if (empty($clientCurrency)) {
            $clientCurrency = ClientCurrency::where('is_primary', 1)->first();
        }

        $payments = PaymentOption::where('credentials', '!=', '')->where('status', 1)->count();

        //   dd($activeOrders->toArray());

        return view('frontend/account/orders')->with(['payments' => $payments, 'rejectedOrders' => $rejectedOrders, 'navCategories' => $navCategories, 'activeOrders' => $activeOrders, 'pastOrders' => $pastOrders, 'returnOrders' => $returnOrders, 'clientCurrency' => $clientCurrency]);
    }

    public function getOrderSuccessPage(Request $request)
    {
        $currency_id = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $order = Order::with(['products.pvariant.vset', 'products.pvariant.translation_one', 'address'])->findOrfail($request->order_id);
        // dd($order->toArray());


        $order_vendors =  OrderVendor::where('order_id', $request->order_id)->whereNotNull('dispatch_traking_url')->get();
        if (count($order_vendors)) {
            $home_service = ClientPreference::where('business_type', 'home_service')->where('id', '>', 0)->first();
            if ($home_service) {
                return Redirect::route('front.booking.details', $order->order_number);
            }
        }


        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        return view('frontend.order.success', compact('order', 'navCategories', 'clientCurrency'));
    }
    public function getOrderSuccessReturnPage(Request $request)
    {
        $currency_id = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        // $order = Order::with(['products.pvariant.vset', 'products.pvariant.translation_one', 'address'])->findOrfail($request->order_id);
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        return view('frontend.order.success-return', compact('navCategories', 'clientCurrency'));
    }
    public function sendSuccessEmail($request, $order, $vendor_id = '')
    {
        if ((isset($request->user_id)) && (!empty($request->user_id))) {
            $user = User::find($request->user_id);
        } elseif ((isset($request->auth_token)) && (!empty($request->auth_token))) {
            $user = User::where('auth_token', $request->auth_token)->first();
        } else {
            $user = Auth::user();
        }
        $client = CP::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from', 'admin_email')->where('id', '>', 0)->first();
        $message = __('An otp has been sent to your email. Please check.');
        $otp = mt_rand(100000, 999999);
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            if ($vendor_id == "") {
                $sendto =  $user->email;
            } else {
                $vendor = Vendor::where('id', $vendor_id)->first();
                if ($vendor) {
                    $sendto =  $vendor->email;
                }
            }
            $currSymbol = Session::has('currencySymbol') ? Session::get('currencySymbol') : '$';
            $client_name = 'Sales';
            $mail_from = $data->mail_from;
            try {
                $email_template_content = '';
                $email_template = EmailTemplate::where('id', 5)->first();
                $address = UserAddress::where('id', $request->address_id)->first();
                if ($user) {
                    $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
                } else {
                    $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
                }
                if ($cart) {
                    $cartDetails = $this->getCart($cart);
                }

                if ($email_template) {
                    $email_template_content = $email_template->content;
                    if ($vendor_id == "") {
                        $returnHTML = view('email.newOrderProducts')->with(['cartData' => $cartDetails, 'order' => $order, 'currencySymbol' => $currSymbol])->render();
                    } else {
                        $returnHTML = view('email.newOrderVendorProducts')->with(['cartData' => $cartDetails, 'id' => $vendor_id, 'currencySymbol' => $currSymbol])->render();
                    }
                    //pr($returnHTML);

                    $email_template_content = str_ireplace("{customer_name}", ucwords($user->name), $email_template_content);
                    $email_template_content = str_ireplace("{order_id}", $order->order_number, $email_template_content);
                    $email_template_content = str_ireplace("{products}", $returnHTML, $email_template_content);
                    $email_template_content = str_ireplace("{address}", $address->address . ', ' . $address->state . ', ' . $address->country . ', ' . $address->pincode, $email_template_content);
                }

                $email_data = [
                    'code' => $otp,
                    'link' => "link",
                    'email' => $sendto,
                    'mail_from' => $mail_from,
                    'client_name' => $client_name,
                    'logo' => $client->logo['original'],
                    'subject' => $email_template->subject,
                    'customer_name' => ucwords($user->name),
                    'email_template_content' => $email_template_content,
                    'cartData' => $cartDetails,
                    'user_address' => $address,
                ];
                if (!empty($data['admin_email'])) {
                    $email_data['admin_email'] = $data['admin_email'];
                }
                if ($vendor_id == "") {
                    $email_data['send_to_cc'] = 1;
                }else{
                    $email_data['send_to_cc'] = 0;
                }
                // $res = $this->testOrderMail($email_data);
                // dd($res);
                dispatch(new \App\Jobs\SendOrderSuccessEmailJob($email_data))->onQueue('verify_email');
                $notified = 1;
            } catch (\Exception $e) {
            }
        }
    }
    public function sendSuccessSMS($request, $order, $vendor_id = '')
    {
        try {
            $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();

            $currId = Session::get('customerCurrency');
            $currSymbol = Session::get('currencySymbol');
            $customerCurrency = ClientCurrency::where('currency_id', $currId)->first();
            $user = User::where('id', $order->user_id)->first();
            if ($user) {
                if ($user->dial_code == "971") {
                    $to = '+' . $user->dial_code . "0" . $user->phone_number;
                } else {
                    $to = '+' . $user->dial_code . $user->phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Hi " . $user->name . ", Your order of amount " . $currSymbol . $order->payable_amount . " for order number " . $order->order_number . " has been placed successfully.";
            //    if (!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)) {
                if (!empty($prefer->sms_provider)) {
                    $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                }
            }
        } catch (\Exception $ex) {
        }
    }
    /**
     * Get Cart Items
     *
     */
    public function getCart($cart, $address_id = 0)
    {
        $cart_id = $cart->id;
        $user = Auth::user();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $pharmacy = ClientPreference::first();
        $cart->pharmacy_check = $pharmacy->pharmacy_check;
        $customerCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $latitude = '';
        $longitude = '';
        if ($address_id > 0) {
            $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->first();
        } else {
            $address = UserAddress::where('user_id', $user->id)->where('is_primary', 1)->first();
            $address_id = ($address) ? $address->id : 0;
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';
        $cartData = CartProduct::with([
            'vendor', 'coupon' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            }, 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image',
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
                $qry->where('cart_id', $cart_id);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'luxury_option_id')->where('status', [0, 1])->where('cart_id', $cart_id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $subscription_features = array();
        if ($user) {
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                    if ($customerCurrency->is_primary != 1) {
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
        }
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        if ($cartData) {
            $delivery_status = 1;
            foreach ($cartData as $ven_key => $vendorData) {
                $payable_amount = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                foreach ($vendorData->vendorProducts as $ven_key => $prod) {
                    $quantity_price = 0;
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant->price / $divider;
                    $price_in_doller_compare = $price_in_currency * $customerCurrency->doller_compare;
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $prod->pvariant->price_in_cart = $prod->pvariant->price;
                    $prod->pvariant->price = $price_in_currency;
                    $prod->pvariant->media_one = $prod->pvariant->media ? $prod->pvariant->media->first() : [];
                    $prod->pvariant->media_second = $prod->product->media ? $prod->product->media->first() : [];
                    $prod->pvariant->multiplier = $customerCurrency->doller_compare;
                    $prod->pvariant->quantity_price = number_format($quantity_price, 2);
                    $payable_amount = $payable_amount + $quantity_price;
                    $taxData = array();
                    if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                        foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                            $rate = round($tax_value->tax_rate);
                            $tax_amount = ($price_in_doller_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            $taxData[$tckey]['rate'] = $rate;
                            $taxData[$tckey]['tax_amount'] = number_format($tax_amount, 2);
                            $taxData[$tckey]['product_tax'] = number_format($product_tax, 2);
                            $taxable_amount = $taxable_amount + $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                        unset($prod->product->taxCategory);
                    }
                    $prod->taxdata = $taxData;
                    foreach ($prod->addon as $ck => $addons) {
                        $opt_price_in_currency = $addons->option->price / $divider;
                        $opt_price_in_doller_compare = $opt_price_in_currency * $customerCurrency->doller_compare;
                        $opt_quantity_price = number_format($opt_price_in_doller_compare * $prod->quantity, 2);
                        $addons->option->price_in_cart = $addons->option->price;
                        $addons->option->price = $opt_price_in_currency;
                        $addons->option->multiplier = $customerCurrency->doller_compare;
                        $addons->option->quantity_price = $opt_quantity_price;
                        $payable_amount = $payable_amount + $opt_quantity_price;
                    }
                    if (isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)) {
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    } else {
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }
                    if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {
                        $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id, $user->id);
                        if (!empty($deliver_charge) && $delivery_count == 0) {
                            $delivery_count = 1;
                            $prod->deliver_charge = number_format($deliver_charge, 2);
                            $payable_amount = $payable_amount + $deliver_charge;
                            $delivery_fee_charges = $deliver_charge;
                        }
                    }
                }
                if ($vendorData->coupon) {
                    if ($vendorData->coupon->promo->promo_type_id == 2) {
                        $total_discount_percent = $vendorData->coupon->promo->amount;
                        $payable_amount -= $total_discount_percent;
                    } else {
                        $gross_amount = number_format(($payable_amount - $taxable_amount), 2);
                        $percentage_amount = ($gross_amount * $vendorData->coupon->promo->amount / 100);
                        $payable_amount -= $percentage_amount;
                    }
                }
                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $delivery_fee_charges;
                }
                if (isset($serviceArea)) {
                    if ($serviceArea->isEmpty()) {
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    } else {
                        $vendorData->isDeliverable = 1;
                    }
                }
                $vendorData->delivery_fee_charges = number_format($delivery_fee_charges, 2);
                $vendorData->payable_amount = number_format($payable_amount, 2);
                $vendorData->discount_amount = number_format($discount_amount, 2);
                $vendorData->discount_percent = number_format($discount_percent, 2);
                $vendorData->taxable_amount = number_format($taxable_amount, 2);
                $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount), 2);
                if (!empty($subscription_features)) {
                    $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount - $subscription_discount), 2);
                }
                $total_payable_amount = $total_payable_amount + $payable_amount;
                $total_taxable_amount = $total_taxable_amount + $taxable_amount;
                $total_discount_amount = $total_discount_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;
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
                $amount_value = $amount_value * $customerCurrency->doller_compare;
                $total_discount_amount = $total_discount_amount + $amount_value;
            }
            if (!empty($subscription_features)) {
                $total_discount_amount = $total_discount_amount + $total_subscription_discount;
                $cart->total_subscription_discount = number_format($total_subscription_discount, 2);
            }
            $total_payable_amount = $total_payable_amount - $total_discount_amount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $total_payable_amount) {
                    $loyalty_amount_saved =  $total_payable_amount;
                }
                $total_payable_amount = $total_payable_amount - $loyalty_amount_saved;
            }
            $cart->loyalty_amount = number_format($loyalty_amount_saved, 2);
            $cart->gross_amount = number_format(($total_payable_amount + $total_discount_amount + $loyalty_amount_saved - $total_taxable_amount), 2);
            $cart->new_gross_amount = number_format(($total_payable_amount + $total_discount_amount), 2);
            $cart->total_payable_amount = number_format($total_payable_amount, 2);
            $cart->total_discount_amount = number_format($total_discount_amount, 2);
            $cart->total_taxable_amount = number_format($total_taxable_amount, 2);
            $cart->tip_5_percent = number_format((0.05 * $total_payable_amount), 2);
            $cart->tip_10_percent = number_format((0.1 * $total_payable_amount), 2);
            $cart->tip_15_percent = number_format((0.15 * $total_payable_amount), 2);
            $cart->deliver_status = $delivery_status;
            $cart->products = $cartData->toArray();
        }
        return $cart;
    }

    public function placeOrder(Request $request, $domain = '')
    {

        //$stock = $this->ProductVariantStock('18');

        // dd($request->all());
        // if ($request->input("payment-group") == '1') {
        //     $langId = Session::get('customerLanguage');
        //     $navCategories = $this->categoryNav($langId);
        //     return view('frontend/orderPayment')->with(['navCategories' => $navCategories, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email_address' => $request->email_address, 'phone' => $request->phone, 'total_amount' => $request->total_amount, 'address_id' => $request->address_id]);
        // }

        $order_response = $this->orderSave($request, "1");
        $response = $order_response->getData();
        if ($response->status == 'Success') {
            # if payment type cash on delivery or payment status is 'Paid'
            if (($response->data->payment_option_id == 1) || (($response->data->payment_option_id != 1) && ($response->data->payment_status == 1))) {
                # if vendor selected auto accept
                $autoaccept = $this->autoAcceptOrderIfOn($response->data->id);
            }
            return $this->successResponse($response->data, 'Order placed successfully.', 201);
        } else {
            return $this->errorResponse($response->message, 400);
        }
    }
    public function orderSave($request, $paymentStatus)
    {
        try {
            DB::beginTransaction();
            $preferences = ClientPreference::select('is_hyperlocal', 'Default_latitude', 'Default_longitude', 'distance_unit_for_time', 'distance_to_time_multiplier', 'client_code')->first();
            $action = (Session::has('vendorType')) ? Session::get('vendorType') : 'delivery';
            $luxury_option = LuxuryOption::where('title', $action)->first();
            $delivery_on_vendors = array();
            if ((isset($request->user_id)) && (!empty($request->user_id))) {
                $user = User::find($request->user_id);
            } elseif ((isset($request->auth_token)) && (!empty($request->auth_token))) {
                $user = User::whereHas('device', function ($qu) use ($request) {
                    $qu->where('access_token', $request->auth_token);
                })->first();
            } else {
                $user = Auth::user();
            }
            if (($request->payment_option_id != 1) && ($request->payment_option_id != 2) && ($request->has('transaction_id')) && (!empty($request->transaction_id))) {
                $saved_transaction = Payment::where('transaction_id', $request->transaction_id)->first();
                if ($saved_transaction) {
                    return $this->errorResponse('Transaction has already been done', 400);
                }
            }
            $loyalty_amount_saved = 0;
            $redeem_points_per_primary_currency = '';
            $loyalty_card = LoyaltyCard::where('status', '0')->first();
            if ($loyalty_card) {
                $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
            }
            $currency_id = Session::get('customerCurrency');
            $language_id = Session::get('customerLanguage');
            $cart = Cart::where('user_id', $user->id)->first();
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                }
            }
            $customerCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $clientCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
            $order = new Order;
            $order->user_id = $user->id;
            $order->order_number = generateOrderNo();
            if (($request->has('address_id')) && ($request->address_id > 0)) {
                $order->address_id = $request->address_id;
            }
            $order->payment_option_id = $request->payment_option_id;
            $order->comment_for_pickup_driver = $cart->comment_for_pickup_driver ?? null;
            $order->comment_for_dropoff_driver = $cart->comment_for_dropoff_driver ?? null;
            $order->comment_for_vendor = $cart->comment_for_vendor ?? null;
            $order->schedule_pickup = $cart->schedule_pickup ?? null;
            $order->schedule_dropoff = $cart->schedule_dropoff ?? null;
            $order->specific_instructions = $cart->specific_instructions ?? null;
            $order->is_gift = $request->is_gift ?? 0;
            $order->save();
            $cart_prescriptions = CartProductPrescription::where('cart_id', $cart->id)->get();
            foreach ($cart_prescriptions as $cart_prescription) {
                $order_prescription = new OrderProductPrescription();
                $order_prescription->order_id = $order->id;
                $order_prescription->vendor_id = $cart_prescription->vendor_id;
                $order_prescription->product_id = $cart_prescription->product_id;
                $order_prescription->prescription = $cart_prescription->getRawOriginal('prescription');
                $order_prescription->save();
            }
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
            $cart_products = CartProduct::select('*')->with(['vendor', 'product.pimage', 'product.variants', 'product.taxCategory.taxRate', 'coupon' => function ($query) use ($cart) {
                $query->where('cart_id', $cart->id);
            }, 'coupon.promo', 'product.addon'])->where('cart_id', $cart->id)->where('status', [0, 1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
            $tax_category_ids = [];
            $vendor_ids = [];
            $total_service_fee = 0;
            $total_delivery_fee = 0;
            $total_subscription_discount = 0;
            foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                $vendor_ids[] = $vendor_id;
                $delivery_fee = 0;
                $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                $vendor_payable_amount = 0;
                $vendor_discount_amount = 0;
                $product_taxable_amount = 0;
                $vendor_products_total_amount = 0;
                $vendor_taxable_amount = 0;
                $OrderVendor = new OrderVendor();
                $OrderVendor->status = 0;
                $OrderVendor->user_id = $user->id;
                $OrderVendor->order_id = $order->id;
                $OrderVendor->vendor_id = $vendor_id;
                $OrderVendor->vendor_dinein_table_id = $vendor_cart_products->unique('vendor_dinein_table_id')->first()->vendor_dinein_table_id;
                $OrderVendor->save();
                $vendorProductIds = array();
                foreach ($vendor_cart_products as $vendor_cart_product) {
                    $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                    $quantity_price = 0;
                    $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
                    $price_in_currency = $variant->price / $divider;
                    $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
                    $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                    $payable_amount = $payable_amount + $quantity_price;
                    $vendor_products_total_amount = $vendor_products_total_amount + $quantity_price;
                    $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                    if (isset($vendor_cart_product->product->taxCategory)) {
                        foreach ($vendor_cart_product->product->taxCategory->taxRate as $tax_rate_detail) {
                            if (!in_array($tax_rate_detail->id, $tax_category_ids)) {
                                $tax_category_ids[] = $tax_rate_detail->id;
                            }
                            $rate = round($tax_rate_detail->tax_rate);
                            $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $product_taxable_amount += $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                    }

                    if ($action == 'delivery') {
                        $deliv = CartDeliveryFee::where('cart_id',$vendor_cart_product->cart_id)
                        ->where('vendor_id',$vendor_cart_product->vendor_id);
                        if($deliv->count() > 0){

                        $deliver_fee_data = $deliv->first();
                        }else{
                        $d = new CartDeliveryFee;
                        $d->cart_id = $vendor_cart_product->cart_id;
                        $d->vendor_id = $vendor_cart_product->vendor_id;
                        $d->shipping_delivery_type = 'test';
                        $d->save();
                        $deliver_fee_data = $d;
                    }
                        // dd($vendor_cart_product);
                        if (((!empty($vendor_cart_product->product->Requires_last_mile)) && ($vendor_cart_product->product->Requires_last_mile == 1)) || isset($deliver_fee_data)) {
                            $OrderVendor->shipping_delivery_type = $deliver_fee_data->shipping_delivery_type;
                            $OrderVendor->courier_id = $deliver_fee_data->courier_id;
                            
                            //Add here Delivery option Lalamove and dispatcher

                            // if($delType=='L'){
                            //     $lala = new LalaMovesController();
                            //     $delivery_fee = $lala->getDeliveryFeeLalamove($vendor_cart_product->vendor_id);
                            // }else{
                            //     $delivery_fee = $this->getDeliveryFeeDispatcher($vendor_cart_product->vendor_id, $user->id);
                            // }


                            if($deliver_fee_data)
                            $delivery_fee  = $deliver_fee_data->delivery_fee??0.00;

                            if (!empty($delivery_fee) && $delivery_count == 0) {
                                $delivery_count = 1;
                                $vendor_cart_product->delivery_fee = number_format($delivery_fee, 2);
                                // $payable_amount = $payable_amount + $delivery_fee;
                                $delivery_fee_charges = $delivery_fee;

                                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                                    $latitude = Session::get('latitude');
                                    $longitude = Session::get('longitude');
                                    $vendor_cart_product->vendor = $this->getVendorDistanceWithTime($latitude, $longitude, $vendor_cart_product->vendor, $preferences);
                                    $OrderVendor->order_pre_time = ($vendor_cart_product->vendor->order_pre_time > 0) ? $vendor_cart_product->vendor->order_pre_time : 0;
                                    $timeofLineOfSightDistance = ($vendor_cart_product->vendor->timeofLineOfSightDistance > 0) ? $vendor_cart_product->vendor->timeofLineOfSightDistance : 0;
                                    if ($vendor_cart_product->vendor->timeofLineOfSightDistance > 0) {
                                        $OrderVendor->user_to_vendor_time = intval($timeofLineOfSightDistance) - intval($OrderVendor->order_pre_time);
                                    }
                                }
                            }

                        }
                    }

                    $taxable_amount += $product_taxable_amount;
                    $vendor_taxable_amount += $taxable_amount;
                    $total_amount += $vendor_cart_product->quantity * $variant->price;
                    $order_product = new OrderProduct;
                    $order_product->order_id = $order->id;
                    $order_product->price = $variant->price;
                    $order_product->order_vendor_id = $OrderVendor->id;
                    $order_product->taxable_amount = $product_taxable_amount;
                    $order_product->quantity = $vendor_cart_product->quantity;
                    $order_product->vendor_id = $vendor_cart_product->vendor_id;
                    $order_product->product_id = $vendor_cart_product->product_id;
                    $product_category = Product::where('id', $vendor_cart_product->product_id)->first();
                    if ($product_category) {
                        $order_product->category_id = $product_category->category_id;
                    }
                    $order_product->created_by = $vendor_cart_product->created_by;
                    $order_product->variant_id = $vendor_cart_product->variant_id;
                    $product_variant_sets = '';
                    if (isset($vendor_cart_product->variant_id) && !empty($vendor_cart_product->variant_id)) {
                        $var_sets = ProductVariantSet::where('product_variant_id', $vendor_cart_product->variant_id)->where('product_id', $vendor_cart_product->product->id)
                            ->with([
                                'variantDetail.trans' => function ($qry) use ($language_id) {
                                    $qry->where('language_id', $language_id);
                                },
                                'optionData.trans' => function ($qry) use ($language_id) {
                                    $qry->where('language_id', $language_id);
                                }
                            ])->get();
                        if (count($var_sets)) {
                            foreach ($var_sets as $set) {
                                if (isset($set->variantDetail) && !empty($set->variantDetail)) {
                                    $product_variant_set = @$set->variantDetail->trans->title . ":" . @$set->optionData->trans->title . ", ";
                                    $product_variant_sets .= $product_variant_set;
                                }
                            }
                        }
                    }

                    $order_product->product_variant_sets = $product_variant_sets;
                    if (!empty($vendor_cart_product->product->title)) {
                        $vendor_cart_product->product->title = $vendor_cart_product->product->title;
                    } elseif (empty($vendor_cart_product->product->title)  && !empty($vendor_cart_product->product->translation)) {
                        $vendor_cart_product->product->title = $vendor_cart_product->product->translation[0]->title;
                    } else {
                        $vendor_cart_product->product->title = $vendor_cart_product->product->sku;
                    }



                    $order_product->product_name = $vendor_cart_product->product->title ?? $vendor_cart_product->product->sku;

                    $order_product->product_dispatcher_tag = $vendor_cart_product->product->tags;
                    $order_product->schedule_type = $vendor_cart_product->schedule_type ?? null;
                    $order_product->scheduled_date_time = $vendor_cart_product->schedule_type == 'schedule' ? $vendor_cart_product->scheduled_date_time : null;
                    if ($vendor_cart_product->product->pimage) {
                        $order_product->image = $vendor_cart_product->product->pimage->first() ? $vendor_cart_product->product->pimage->first()->path : '';
                    }
                    $order_product->save();
                    if (!empty($vendor_cart_product->addon)) {
                        foreach ($vendor_cart_product->addon as $ck => $addon) {
                            $opt_quantity_price = 0;
                            $opt_price_in_currency = $addon->option->price;
                            $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                            $opt_quantity_price = $opt_price_in_doller_compare * $order_product->quantity;
                            $total_amount = $total_amount + $opt_quantity_price;
                            $payable_amount = $payable_amount + $opt_quantity_price;
                            $vendor_payable_amount = $vendor_payable_amount + $opt_quantity_price;
                        }
                    }
                    $cart_addons = CartAddon::where('cart_product_id', $vendor_cart_product->id)->get();
                    if ($cart_addons) {
                        foreach ($cart_addons as $cart_addon) {
                            $orderAddon = new OrderProductAddon;
                            $orderAddon->addon_id = $cart_addon->addon_id;
                            $orderAddon->option_id = $cart_addon->option_id;
                            $orderAddon->order_product_id = $order_product->id;
                            $orderAddon->save();
                        }
                     //   CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
                    }
                }
                $coupon_id = null;
                $coupon_name = null;
                $actual_amount = $vendor_payable_amount;
                if ($vendor_cart_product->coupon) {
                    $coupon_id = $vendor_cart_product->coupon->promo->id;
                    $coupon_name = $vendor_cart_product->coupon->promo->name;
                    if ($vendor_cart_product->coupon->promo->promo_type_id == 2) {
                        $amount = round($vendor_cart_product->coupon->promo->amount);
                        $total_discount += $amount;
                        $vendor_payable_amount -= $amount;
                        $vendor_discount_amount += $amount;
                    } else {
                        $gross_amount = number_format(($payable_amount - $taxable_amount), 2);
                        $percentage_amount = ($gross_amount * $vendor_cart_product->coupon->promo->amount / 100);
                        $total_discount += $percentage_amount;
                        $vendor_payable_amount -= $percentage_amount;
                        $vendor_discount_amount += $percentage_amount;
                    }
                }
                //Start applying service fee on vendor products total
                $vendor_service_fee_percentage_amount = 0;
                if ($vendor_cart_product->vendor->service_fee_percent > 0) {
                    $vendor_service_fee_percentage_amount = ($vendor_products_total_amount * $vendor_cart_product->vendor->service_fee_percent) / 100;
                    $vendor_payable_amount += $vendor_service_fee_percentage_amount;
                    $payable_amount += $vendor_service_fee_percentage_amount;
                }
                //End applying service fee on vendor products total
                $total_service_fee = $total_service_fee + $vendor_service_fee_percentage_amount;
                $OrderVendor->service_fee_percentage_amount = $vendor_service_fee_percentage_amount;

                $total_delivery_fee += $delivery_fee;
                $vendor_payable_amount += $delivery_fee;
                $vendor_payable_amount += $vendor_taxable_amount;

                $OrderVendor->coupon_id = $coupon_id;
                $OrderVendor->coupon_code = $coupon_name;
                $OrderVendor->order_status_option_id = 1;
                $OrderVendor->delivery_fee = $delivery_fee;
                $OrderVendor->subtotal_amount = $actual_amount;
                $OrderVendor->discount_amount = $vendor_discount_amount;
                $OrderVendor->taxable_amount   = $vendor_taxable_amount;
                $OrderVendor->payment_option_id = $request->payment_option_id;
                $OrderVendor->payable_amount = $vendor_payable_amount;
                $vendor_info = Vendor::where('id', $vendor_id)->first();
                if ($vendor_info) {
                    if (($vendor_info->commission_percent) != null && $vendor_payable_amount > 0) {
                        $OrderVendor->admin_commission_percentage_amount = round($vendor_info->commission_percent * ($vendor_payable_amount / 100), 2);
                    }
                    if (($vendor_info->commission_fixed_per_order) != null && $vendor_payable_amount > 0) {
                        $OrderVendor->admin_commission_fixed_amount = $vendor_info->commission_fixed_per_order;
                    }
                }
                $OrderVendor->save();
                $order_status = new VendorOrderStatus();
                $order_status->order_id = $order->id;
                $order_status->vendor_id = $vendor_id;
                $order_status->order_vendor_id = $OrderVendor->id;
                $order_status->order_status_option_id = 1;
                $order_status->save();
            }
            $loyalty_points_earned = LoyaltyCard::getLoyaltyPoint($loyalty_points_used, $payable_amount);
            if (in_array(1, $subscription_features)) {
                $total_subscription_discount = $total_subscription_discount + $total_delivery_fee;
            }
            $total_discount = $total_discount + $total_subscription_discount;
            $order->total_amount = $total_amount;
            $order->total_discount = $total_discount;
            $order->taxable_amount = $taxable_amount;
            $payable_amount = $payable_amount + $total_delivery_fee - $total_discount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $payable_amount) {
                    $loyalty_amount_saved = $payable_amount;
                    $loyalty_points_used = $payable_amount * $redeem_points_per_primary_currency;
                }
            }
            $payable_amount = $payable_amount - $loyalty_amount_saved;
            $wallet_amount_used = 0;
            if ($user) {
                if ($user->balanceFloat > 0) {
                    $wallet = $user->wallet;
                    $wallet_amount_used = $user->balanceFloat;
                    if ($wallet_amount_used > $payable_amount) {
                        $wallet_amount_used = $payable_amount;
                    }
                    $order->wallet_amount_used = $wallet_amount_used;
                    if ($wallet_amount_used > 0) {
                        $wallet->withdrawFloat($order->wallet_amount_used, ['Wallet has been <b>debited</b> for order number <b>' . $order->order_number . '</b>']);
                    }
                }
            }
            $payable_amount = $payable_amount - $wallet_amount_used;
            $tip_amount = 0;
            if ((isset($request->tip)) && ($request->tip != '') && ($request->tip > 0)) {
                $tip_amount = $request->tip;
                $tip_amount = ($tip_amount / $customerCurrency->doller_compare) * $clientCurrency->doller_compare;
                $order->tip_amount = $tip_amount;
            }
            $payable_amount = $payable_amount + $tip_amount;
            $order->total_service_fee = $total_service_fee;
            $order->total_delivery_fee = $total_delivery_fee;
            $order->loyalty_points_used = $loyalty_points_used;
            $order->loyalty_amount_saved = $loyalty_amount_saved;
            $order->subscription_discount = $total_subscription_discount;
            $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
            $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];


            $order->scheduled_date_time = $cart->schedule_type == 'schedule' ? $cart->scheduled_date_time : null;


            $order->scheduled_slot = (($cart->scheduled_slot)?$cart->scheduled_slot:null);
            $order->luxury_option_id = $luxury_option->id;
            $order->payable_amount = $payable_amount;
            if (($payable_amount == 0) || (($request->has('transaction_id')) && (!empty($request->transaction_id)))) {
                $order->payment_status = 1;
            }
            $order->save();
            // foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
            //     $this->sendSuccessEmail($request, $order, $vendor_id);
            // }
            // $this->sendOrderNotification($user->id, $vendor_ids);
            //$this->sendSuccessEmail($request, $order);
            $ex_gateways = [7, 8, 9, 10, 17]; //  mobbex, yoco, pointcheckout, razorpay, checkout
            if (!in_array($request->payment_option_id, $ex_gateways)) {
                Cart::where('id', $cart->id)->update([
                    'schedule_type' => null, 'scheduled_date_time' => null,
                    'comment_for_pickup_driver' => null, 'comment_for_dropoff_driver' => null, 'comment_for_vendor' => null, 'schedule_pickup' => null, 'schedule_dropoff' => null, 'specific_instructions' => null
                ]);
                CartAddon::where('cart_id', $cart->id)->delete();
                CartCoupon::where('cart_id', $cart->id)->delete();
                CartProduct::where('cart_id', $cart->id)->delete();
                CartProductPrescription::where('cart_id', $cart->id)->delete();
            }
            if (count($tax_category_ids)) {
                foreach ($tax_category_ids as $tax_category_id) {
                    $order_tax = new OrderTax();
                    $order_tax->order_id = $order->id;
                    $order_tax->tax_category_id = $tax_category_id;
                    $order_tax->save();
                }
            }
            if (($request->payment_option_id != 1) && ($request->payment_option_id != 2) && ($request->has('transaction_id')) && (!empty($request->transaction_id))) {
                Payment::insert([
                    'date' => date('Y-m-d'),
                    'order_id' => $order->id,
                    'transaction_id' => $request->transaction_id,
                    'balance_transaction' => $order->payable_amount,
                    'type' => 'cart'
                ]);
            }
            $order = $order->with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id', 'vendors.vendor'])->where('order_number', $order->order_number)->first();
            if (!in_array($request->payment_option_id, $ex_gateways)) {
                if (!empty($order->vendors)) {
                    foreach ($order->vendors as $vendor_value) {
                        $vendorDetail = $vendor_value->vendor;
                        if ($vendorDetail->auto_accept_order == 0 && $vendorDetail->auto_reject_time > 0) {
                            $clientDetail = CP::on('mysql')->where(['code' => $preferences->client_code])->first();
                            AutoRejectOrderCron::on('mysql')->create(['database_host' => $clientDetail->database_path, 'database_name' => $clientDetail->database_name, 'database_username' => $clientDetail->database_username, 'database_password' => $clientDetail->database_password, 'order_vendor_id' => $vendor_value->id, 'auto_reject_time' => Carbon::now()->addMinute($vendorDetail->auto_reject_time)]);
                        }
                        $vendor_order_detail = $this->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                        $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                        $this->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                    }
                }
                $vendor_order_detail = $this->minimize_orderDetails_for_notification($order->id);
                $super_admin = User::where('is_superadmin', 1)->pluck('id');
                $this->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);
                // $user_admins = User::where(function ($query) {
                //     $query->where(['is_superadmin' => 1]);
                // })->pluck('id')->toArray();
                // $user_vendors = [];
                // if (!empty($order->user_vendor) && count($order->user_vendor) > 0) {
                //     $user_vendors = $order->user_vendor->pluck('user_id')->toArray();
                // }
                // $order->admins = array_unique(array_merge($user_admins, $user_vendors));
                // $this->sendOrderPushNotificationVendors($order->admins, ['id' => $order->id]);
            }
            DB::commit();
            // $this->sendSuccessSMS($request, $order);

            return $this->successResponse($order);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage, 402);
        }
    }

    public function sendOrderNotification($id, $vendorIds)
    {
        $super_admin = User::where('is_superadmin', 1)->pluck('id');
        $user_vendors = UserVendor::whereIn('vendor_id', $vendorIds)->pluck('user_id');
        $devices = UserDevice::whereNotNull('device_token')->where('user_id', $id)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_vendors)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $super_admin)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $token[] = "d4SQZU1QTMyMaENeZXL3r6:APA91bHoHsQ-rnxsFaidTq5fPse0k78qOTo7ZiPTASiH69eodqxGoMnRu2x5xnX44WfRhrVJSQg2FIjdfhwCyfpnZKL2bHb5doCiIxxpaduAUp4MUVIj8Q43SB3dvvvBkM1Qc1ThGtEM";
        $from = env('FIREBASE_SERVER_KEY');
        $notification_content = NotificationTemplate::where('id', 1)->first();
        if ($notification_content) {
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
            $result = curl_exec($ch);
            // dd($result);
            curl_close($ch);
        }
    }

    public function sendOrderPushNotificationVendors($user_ids, $orderData)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();
        //    Log::info($devices);
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            $notification_content = NotificationTemplate::where('id', 4)->first();
            if ($notification_content) {
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        'title' => $notification_content->subject,
                        'body'  => $notification_content->content,
                        'sound' => "notification.wav",
                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['image_path'] : '',
                        'click_action' => route('order.index'),
                        "android_channel_id" => "sound-channel-id"
                    ],
                    "data" => [
                        'title' => $notification_content->subject,
                        'body'  => $notification_content->content,
                        'data' => $orderData,
                        'type' => "order_created"
                    ],
                    "priority" => "high"
                ];
                //    Log::info(json_encode($data));
                $dataString = $data;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                $result = curl_exec($ch);
                //    Log::info($result);
                curl_close($ch);
            }
        }
    }

    public function makePayment(Request $request)
    {
        $token = $request->stripeToken;
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey('sk_test_51IhpwhSFHEA938FwRPiQSAH5xF6DcjO5GCASiud9cGMJ0v8UJyRfCb7IQAMbXbuPMe7JphA1izxZOsIclvmOgqUV00Zpk85xfl');
        $formData = [
            'number' => $request->card_num,
            'description' => $request->first_name,
            'expiryMonth' => $request->exp_month,
            'expiryYear' => $request->exp_year,
            'cvv' => $request->cvc
        ];
        $response = $gateway->purchase(
            [
                'amount' => $request->amount,
                'currency' => 'INR',
                'card' => $formData,
                'token' => $token,
            ]
        )->send();
        if ($response->isSuccessful()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
            $payment = new Payment();
            $payment->amount = $request->amount;
            $payment->transaction_id = $response->getData()['id'];
            $payment->balance_transaction = $response->getData()['balance_transaction'];
            $payment->type = "card";
            $payment->cart_id = $cart->id;
            $payment->save();
            $this->orderSave($request, "2", "1");
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            exit($response->getMessage());
        }
    }
    public function getDeliveryFeeDispatcher($vendor_id, $user_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find($user_id);
                $cus_address = UserAddress::where('user_id', $user_id)->orderBy('is_primary', 'desc')->first();
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
                    $client = new GCLIENT([
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
            // print_r($e->getMessage());
            //  die;
        }
    }
    # check if last mile delivery on
    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url)) {
            return $preference;
        } else {
            return false;
        }
    }

    public function postPaymentPlaceOrder(Request $request, $domain = '')
    {
        if ((isset($request->auth_token)) && (!empty($request->auth_token))) {
            return $this->placeOrder($request);
        } else {
            return $this->errorResponse('Invalid User', 402);
        }
    }

    # if vendor selected auto accepted order
    public function autoAcceptOrderIfOn($order_id)
    {
        $order_vendors = OrderVendor::where('order_id', $order_id)->whereHas('vendor', function ($q) {
            $q->where('auto_accept_order', 1);
        })->get();
        $orderData = Order::find($order_id);
        //  Log::info($order_vendors);
        foreach ($order_vendors as $ov) {
            //     Log::info($ov);
            //      Log::info($ov->order_id);
            $request = $ov;

            DB::beginTransaction();
            //try {

            $request->order_id = $ov->order_id;
            //       Log::info($ov->order_id);
            //       Log::info($request->order_id);
            $request->vendor_id = $ov->vendor_id;
            $request->order_vendor_id = $ov->id;
            $request->status_option_id = 2;
            // $timezone = Auth::user()->timezone;
            //  Log::info(Auth::user());
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            //       Log::info($vendor_order_status_check);
            if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->order_vendor_id = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();

                if ($request->status_option_id == 2) {

                    if ($orderData->shipping_delivery_type=='D') {
                    //             Log::info($request->status_option_id);
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                    if ($order_dispatch && $order_dispatch == 1) {
                        $stats = $this->insertInVendorOrderDispatchStatus($request);
                    }
                   }elseif($orderData->shipping_delivery_type=='L'){
                        //Create Shipping place order request for Lalamove
                        $order_lalamove = $this->placeOrderRequestlalamove($request);
                    }

                }
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id]);
                $this->ProductVariantStock($order_id);
                DB::commit();
                $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
            }
            // } catch(\Exception $e){
            // DB::rollback();
            // Log::info($e->getMessage());
            // }
        }
    }


    /// ******************  check If any Product Last Mile on   ************************ ///////////////


    public function placeOrderRequestlalamove($request)
    {

        $lala = new LalaMovesController();
        //Create Shipping place order request for Lalamove
        $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
        $checkOrder = Order::findOrFail($request->order_id);
            if ($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00){
            $order_lalamove = $lala->placeOrderToLalamoveDev($request->vendor_id,$checkOrder->user_id,$checkOrder->id);
            }

            if ($order_lalamove->totalFee >0){
                $up_web_hook_code = OrderVendor::where(['order_id' => $checkOrder->id, 'vendor_id' => $request->vendor_id])
                ->update(['web_hook_code' => $order_lalamove->orderRef]);

                return 1;
            }

        return 2;
    }


    public function checkIfanyProductLastMileon($request)
    {
        $order_dispatchs = 2;
        $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            if ($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00) {
                $order_dispatchs = $this->placeRequestToDispatch($request->order_id, $request->vendor_id, $dispatch_domain);
            }


            if ($order_dispatchs && $order_dispatchs == 1) {
                return 1;
            }
        }


        $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
        if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false) {
            $ondemand = 0;
            //    Log::info($dispatch_domain_ondemand);
            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if (isset($prod->product_dispatcher_tag) && !empty($prod->product_dispatcher_tag) && $prod->product->category->categoryDetail->type_id == 8) {
                    $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
                    if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false && $ondemand == 0  && $checkdeliveryFeeAdded->delivery_fee <= 0.00) {
                        $order_dispatchs = $this->placeRequestToDispatchOnDemand($request->order_id, $request->vendor_id, $dispatch_domain_ondemand);
                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $ondemand = 1;
                            return 1;
                        }
                    }
                }
            }
        }

        /////////////// **************** for laundry accept order *************** ////////////////
        $dispatch_domain_laundry = $this->getDispatchLaundryDomain();

        if ($dispatch_domain_laundry && $dispatch_domain_laundry != false) {
            $laundry = 0;

            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if ($prod->product->category->categoryDetail->type_id == 9) {     ///////// if product from laundry
                    $dispatch_domain_laundry = $this->getDispatchLaundryDomain();
                    if ($dispatch_domain_laundry && $dispatch_domain_laundry != false && $laundry == 0) {
                        for ($x = 1; $x <= 2; $x++) {
                            if ($x == 1) {
                                $team_tag = $dispatch_domain_laundry->laundry_pickup_team ?? null;
                                $colm = $x;
                            }

                            if ($x == 2) {
                                $team_tag = $dispatch_domain_laundry->laundry_dropoff_team ?? null;
                                $colm = $x;
                            }



                            $order_dispatchs = $this->placeRequestToDispatchLaundry($request->order_id, $request->vendor_id, $dispatch_domain_laundry, $team_tag, $colm);
                        }

                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $laundry = 1;
                            return 1;
                        }
                    }
                }
            }
        }

        return 2;
    }


    // place Request To Dispatch
    public function placeRequestToDispatch($order, $vendor, $dispatch_domain)
    {
        try {
            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name',  'phone_no', 'email', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $team_tag = null;
            if (!empty($dispatch_domain->last_mile_team)) {
                $team_tag = $dispatch_domain->last_mile_team;
            }

            if (isset($order->scheduled_date_time) && !empty($order->scheduled_date_time)) {
                $task_type = 'schedule';
                $schedule_time = $order->scheduled_date_time ?? null;
            } else {
                $task_type = 'now';
            }

            $tasks[] = array(
                'task_type_id' => 1,
                'latitude'    => $vendor_details->latitude ?? '',
                'longitude'   => $vendor_details->longitude ?? '',
                'short_name'  => '',
                'address'     => $vendor_details->address ?? '',
                'post_code'   => '',
                'barcode'     => '',
                'flat_no'     => null,
                'email'       => $vendor_details->email ?? null,
                'phone_number' => $vendor_details->phone_no ?? null,
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude'    => $cus_address->latitude ?? '',
                'longitude'   => $cus_address->longitude ?? '',
                'short_name'  => '',
                'address'     => $cus_address->address ?? '',
                'post_code'   => $cus_address->pincode ?? '',
                'barcode'     => '',
                'flat_no'     => $cus_address->house_number ?? null,
                'email'       => $customer->email ?? null,
                'phone_number' => ($customer->dial_code . $customer->phone_number ) ?? null,
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => $task_type,
                'schedule_time' => $schedule_time ?? null,
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new GCLIENT([
                'headers' => [
                    'personaltoken' => $dispatch_domain->delivery_service_key,
                    'shortcode' => $dispatch_domain->delivery_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->delivery_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);
                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // place Request To Dispatch for On Demand
    public function placeRequestToDispatchOnDemand($order, $vendor, $dispatch_domain)
    {
        try {
            //    Log::info($order);
            //    Log::info($vendor);
            //    Log::info($dispatch_domain);
            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'phone_no', 'email', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            $team_tag = $unique . "_" . $vendor;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude'     => $vendor_details->latitude ?? '',
                'longitude'    => $vendor_details->longitude ?? '',
                'short_name'   => '',
                'address'      => $vendor_details->address ?? '',
                'post_code'    => '',
                'barcode'      => '',
                'flat_no'      => null,
                'email'        => $vendor_details->email ?? null,
                'phone_number' => $vendor_details->phone_no ?? null,
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude'    => $cus_address->latitude ?? '',
                'longitude'   => $cus_address->longitude ?? '',
                'short_name'  => '',
                'address'     => $cus_address->address ?? '',
                'post_code'   => $cus_address->pincode ?? '',
                'barcode'     => '',
                'flat_no'     => $cus_address->house_number ?? null,
                'email'       => $customer->email ?? null,
                'phone_number' => ($customer->dial_code . $customer->phone_number)  ?? null,
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new GCLIENT([
                'headers' => [
                    'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                    'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->dispacher_home_other_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);


                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // place Request To Dispatch for Laundry
    public function placeRequestToDispatchLaundry($order, $vendor, $dispatch_domain, $team_tag, $colm)
    {
        try {
            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }


            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'phone_no', 'email', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            if ($colm == 1) {     # 1 for pickup from customer drop to vendor
                $desc = $order->comment_for_pickup_driver ?? null;
                $tasks[] = array(
                    'task_type_id' => 1,
                    'latitude'    => $cus_address->latitude ?? '',
                    'longitude'   => $cus_address->longitude ?? '',
                    'short_name'  => '',
                    'address'     => $cus_address->address ?? '',
                    'post_code'   => $cus_address->pincode ?? '',
                    'barcode'     => '',
                    'flat_no'     => $cus_address->house_number ?? '',
                    'email'       => $customer->email ?? '',
                    'phone_number' => $customer->dial_code . $customer->phone_number  ?? ''

                );
                $tasks[] = array(
                    'task_type_id' => 2,
                    'latitude'    => $vendor_details->latitude ?? '',
                    'longitude'   => $vendor_details->longitude ?? '',
                    'short_name'  => '',
                    'address'     => $vendor_details->address ?? '',
                    'post_code'   => '',
                    'barcode'     => '',
                    'flat_no'     => null,
                    'email'       => $vendor_details->email ?? null,
                    'phone_number' => $vendor_details->phone_no ?? null
                 );

                if (isset($order->schedule_pickup) && !empty($order->schedule_pickup)) {
                    $task_type = 'schedule';
                    $schedule_time = $order->schedule_pickup ?? null;
                } else {
                    $task_type = 'now';
                }
            }


            if ($colm == 2) { # 1 for pickup from vendor drop to customer
                $desc = $order->comment_for_dropoff_driver ?? null;
                $tasks[] = array(
                    'task_type_id' => 1,
                    'latitude'    => $vendor_details->latitude ?? '',
                    'longitude'   => $vendor_details->longitude ?? '',
                    'short_name'  => '',
                    'address'     => $vendor_details->address ?? '',
                    'post_code'   => '',
                    'barcode'     => '',
                    'flat_no'     => null,
                    'email'       => $vendor_details->email ?? null,
                    'phone_number' => $vendor_details->phone_no ?? null,
                );


                $tasks[] = array(
                    'task_type_id' => 2,
                    'latitude'    => $cus_address->latitude ?? '',
                    'longitude'   => $cus_address->longitude ?? '',
                    'short_name'  => '',
                    'address'     => $cus_address->address ?? '',
                    'post_code'   => $cus_address->pincode ?? '',
                    'barcode'     => '',
                    'flat_no'     => $cus_address->house_number ?? null,
                    'email'       => $customer->email ?? null,
                    'phone_number' => ($customer->dial_code . $customer->phone_number)  ?? null,
                );


                if (isset($order->schedule_dropoff) && !empty($order->schedule_dropoff)) {
                    $task_type = 'schedule';
                    $schedule_time = $order->schedule_dropoff ?? null;
                } else {
                    $task_type = 'now';
                }
            }




            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => $desc ?? null,
                'allocation_type' => 'a',
                'task_type' => $task_type,
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'schedule_time' => $schedule_time ?? null,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->laundry_service_key,
                    'shortcode' => $dispatch_domain->laundry_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->laundry_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata
                )]
            );
            $response = json_decode($res->getBody(), true);

            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);

                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    # get prefereance if last mile on or off and all details updated in config
    public function getDispatchDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url)) {
            return $preference;
        } else {
            return false;
        }
    }


    # get prefereance if on demand on in config
    public function getDispatchOnDemandDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url)) {
            return $preference;
        } else {
            return false;
        }
    }

    # get prefereance if laundry in config
    public function getDispatchLaundryDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_laundry_service == 1 && !empty($preference->laundry_service_key) && !empty($preference->laundry_service_key_code) && !empty($preference->laundry_service_key_url)) {
            return $preference;
        } else {
            return false;
        }
    }



    public function sendSuccessNotification($id, $vendorId)
    {
        $super_admin = User::where('is_superadmin', 1)->pluck('id');
        $user_vendors = UserVendor::where('vendor_id', $vendorId)->pluck('user_id');
        $devices = UserDevice::whereNotNull('device_token')->where('user_id', $id)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_vendors)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $super_admin)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $token[] = "d4SQZU1QTMyMaENeZXL3r6:APA91bHoHsQ-rnxsFaidTq5fPse0k78qOTo7ZiPTASiH69eodqxGoMnRu2x5xnX44WfRhrVJSQg2FIjdfhwCyfpnZKL2bHb5doCiIxxpaduAUp4MUVIj8Q43SB3dvvvBkM1Qc1ThGtEM";
        // dd($token);

        $from = env('FIREBASE_SERVER_KEY');

        $notification_content = NotificationTemplate::where('id', 2)->first();
        if ($notification_content) {
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
            $result = curl_exec($ch);
            // dd($result);
            curl_close($ch);
        }
    }

    /// ******************   insert In Vendor Order Dispatch Status   ************************ ///////////////
    public function insertInVendorOrderDispatchStatus($request)
    {
        $update = VendorOrderDispatcherStatus::updateOrCreate([
            'dispatcher_id' => null,
            'order_id' =>  $request->order_id,
            'dispatcher_status_option_id' => 1,
            'vendor_id' =>  $request->vendor_id
        ]);
    }

    public function checkIfLastMileDeliveryOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url)) {
            return $preference;
        } else {
            return false;
        }
    }

    public function driverDocuments()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();
            $url = $dispatch_domain->delivery_service_key_url;
            $endpoint = $url . "/api/send-documents";
            // $dispatch_domain->delivery_service_key_code = '649a9a';
            // $dispatch_domain->delivery_service_key = 'icDerSAVT4Fd795DgPsPfONXahhTOA';
            $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key, 'shortcode' => $dispatch_domain->delivery_service_key_code]]);

            $response = $client->post($endpoint);
            $response = json_decode($response->getBody(), true);

            return json_encode($response['data']);
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }

    public function driverSignup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone_number' => 'required',
                'type' => 'required',
                'team' => 'required',
            ], [
                "name.required" => __('The name field is required.'),
                "phone_number.required" => __('The phone number field is required.'),
                "type.required" => __('The type field is required.'),
                "team.required" => __('The team field is required.')
            ]);
            if($validator->fails()){
                return $this->errorResponse($validator->errors(), 422);
            }
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();
            if ($dispatch_domain && $dispatch_domain != false) {

                $data = json_decode($this->driverDocuments());
                $driver_registration_documents = $data->documents;

                $rules_array = [
                    'name' => 'required',
                    'phone_number' => 'required',
                    'type' => 'required',
                    'team' => 'required'
                ];
                foreach ($driver_registration_documents as $driver_registration_document) {
                    if($driver_registration_document->is_required == 1){
                        $name = str_replace(" ", "_", $driver_registration_document->name);
                        $rules_array[$name] = 'required';
                    }
                }
                $validator = Validator::make($request->all(), $rules_array, [
                    "name.required" => __('The name field is required.'),
                    "phone_number.required" => __('The phone number field is required.'),
                    "type.required" => __('The type field is required.'),
                    "vehicle_type_id.required" => __('The transport type is required.'),
                    "make_model.required" => __('The transport details field is required.'),
                    "uid.required" => __('The UID field is required.'),
                    "plate_number.required" => __('The licence plate field is required.'),
                    "color.required" => __('The color field is required.'),
                    "team.required" => __('The team field is required.')
                ]);
                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 422);
                }

                $files = [];
                if ($driver_registration_documents != null) {
                    foreach ($driver_registration_documents as $key => $driver_registration_document) {
                        $driver_registration_document_file_type[$key] = $driver_registration_document->file_type;
                        $files[$key]['file_type'] = $driver_registration_document_file_type[$key];
                        $driver_registration_document_id[$key] = $driver_registration_document->id;
                        $files[$key]['id'] = $driver_registration_document_id[$key];
                        $driver_registration_document_name[$key] = $driver_registration_document->name;
                        $files[$key]['name'] = $driver_registration_document_name[$key];
                        $name = $driver_registration_document->name;
                        $arr = explode(' ', $name);
                        $name = implode('_', $arr);
                        $driver_registration_document_file_name[$key] = $request[$name];
                        $files[$key]['file_name'] =  $driver_registration_document_file_name[$key];
                    }
                }
                // $dispatch_domain->delivery_service_key_code = '649a9a';
                //  $dispatch_domain->delivery_service_key = 'icDerSAVT4Fd795DgPsPfONXahhTOA';
                $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key, 'shortcode' => $dispatch_domain->delivery_service_key_code]]);
                $url = $dispatch_domain->delivery_service_key_url;
                $key1 = 0;
                $key2 = 0;
                $filedata = [];
                $other = [];
                $abc = [];
                foreach ($files as $file) {
                    if ($file['file_name'] != null) {
                        if ($file['file_type'] != "Text") {
                            $file_path          = $file['file_name']->getPathname();
                            $file_mime          = $file['file_name']->getMimeType('image');
                            $file_uploaded_name = $file['file_name']->getClientOriginalName();
                            $filedata[$key2] =  [
                                'Content-type' => 'multipart/form-data',
                                'name' => 'uploaded_file[]',
                                'file_type' => $file['file_type'],
                                'id' => $file['id'],
                                'filename' => $file_uploaded_name,
                                'contents' => fopen($file_path, 'r'),

                            ];
                            $other[$key2] = [
                                'filename1' => $file['name'],
                                'file_type' => $file['file_type'],
                                'id' => $file['id'],
                            ];
                            $key2++;
                        } else {
                            $abc[$key1] =  [
                                'file_type' => $file['file_type'],
                                'id' => $file['id'],
                                'contents' => $file['file_name'],
                                'label_name' => $file['name']
                            ];
                            $key1++;
                        }
                    }
                }
                $profile_photo = [];
                if ($request->hasFile('upload_photo')) {
                    $profile_photo =
                        [
                            'Content-type' => 'multipart/form-data',
                            'name' => 'upload_photo',
                            'filename' => $request->upload_photo->getClientOriginalName(),
                            'Mime-Type' => $request->upload_photo->getMimeType('image'),
                            'contents' =>  fopen($request->upload_photo, 'r'),
                        ];
                }
                if ($profile_photo == null) {
                    $profile_photo = ['name' => 'profile_photo[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(0, $filedata)) {
                    $filedata[0] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(1, $filedata)) {
                    $filedata[1] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(2, $filedata)) {
                    $filedata[2] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(3, $filedata)) {
                    $filedata[3] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(4, $filedata)) {
                    $filedata[4] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(5, $filedata)) {
                    $filedata[5] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(6, $filedata)) {
                    $filedata[6] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(7, $filedata)) {
                    $filedata[7] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(8, $filedata)) {
                    $filedata[8] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }
                if (!array_key_exists(9, $filedata)) {
                    $filedata[9] = ['name' => 'uploaded_file[]', 'contents' => 'abc'];
                }

                $tags = '';
                if ($request->has('tags') && !empty($request->get('tags'))) {
                    $tagsArray = $request->get('tags');
                    $tags = implode(',', $tagsArray);
                }

                $res = $client->post($url . '/api/agent/create', [

                    'multipart' => [
                        $filedata[0],
                        $profile_photo,
                        $filedata[1],
                        $filedata[2],
                        $filedata[3],
                        $filedata[4],
                        $filedata[5],
                        $filedata[6],
                        $filedata[7],
                        $filedata[8],
                        $filedata[9],
                        [
                            'name' => 'other',
                            'contents' => json_encode($other)
                        ],
                        [
                            'name' => 'files_text',
                            'contents' => json_encode($abc)
                        ],

                        [
                            'name' => 'count',
                            'contents' => count($files)
                        ],
                        [
                            'name' => 'name',
                            'contents' => $request->name
                        ],
                        [
                            'name' => 'phone_number',
                            'contents' => $request->phone_number
                        ],
                        [
                            'name' => 'country_code',
                            'contents' => $request->country_code
                        ],
                        [
                            'name' => 'type',
                            'contents' => $request->type
                        ],
                        [
                            'name' => 'vehicle_type_id',
                            'contents' => $request->vehicle_type_id??null
                        ],
                        [
                            'name' => 'make_model',
                            'contents' => $request->make_model??null
                        ],
                        [
                            'name' => 'uid',
                            'contents' => $request->uid??null
                        ],
                        [
                            'name' => 'plate_number',
                            'contents' => $request->plate_number??null
                        ],
                        [
                            'name' => 'color',
                            'contents' => $request->color??null
                        ],
                        [
                            'name' => 'team_id',
                            'contents' => $request->team
                        ],
                        [
                            'name' => 'tags',
                            'contents' => $tags
                        ],
                    ]

                ]);
                $response = json_decode($res->getBody(), true);
                return $response;
            }
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }

    public function minimize_orderDetails_for_notification($order_id, $vendor_id = "")
    {
        $user = Auth::user();
        $order = Order::with(['vendors.vendor:id,name,auto_accept_order,logo'])->select('id', 'order_number', 'payable_amount', 'payment_option_id', 'user_id', 'address_id', 'loyalty_amount_saved', 'total_discount', 'total_delivery_fee', 'total_amount', 'taxable_amount', 'created_at');
        $order = $order->whereHas('vendors', function ($query) use ($vendor_id) {
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        })->with('vendors', function ($query) use ($vendor_id) {
            $query->select('id', 'order_id', 'vendor_id');
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        });
        $order = $order->find($order_id);
        return $order;
    }

    public function orderDetails_for_notification($order_id, $vendor_id = "")
    {
        $user = Auth::user();
        $language_id = (!empty(Session::get('customerLanguage'))) ? Session::get('customerLanguage') : 1;
        $order = Order::with([
            'vendors.products:id,product_name,product_id,order_id,order_vendor_id,variant_id,quantity,price', 'vendors.vendor:id,name,auto_accept_order,logo', 'vendors.products.addon:id,order_product_id,addon_id,option_id', 'vendors.products.pvariant:id,sku,product_id,title,quantity', 'user:id,name,timezone,dial_code,phone_number', 'address:id,user_id,address', 'vendors.products.addon.option:addon_options.id,addon_options.title,addon_id,price', 'vendors.products.addon.set:addon_sets.id,addon_sets.title', 'vendors.products.translation' => function ($q) use ($language_id) {
                $q->select('id', 'product_id', 'title');
                $q->where('language_id', $language_id);
            },
            'vendors.products.addon.option.translation_one' => function ($q) use ($language_id) {
                $q->select('id', 'addon_opt_id', 'title');
                $q->where('language_id', $language_id);
            },
            'vendors.products.addon.set.translation_one' => function ($q) use ($language_id) {
                $q->select('id', 'addon_id', 'title');
                $q->where('language_id', $language_id);
            }
        ])->select('id', 'order_number', 'payable_amount', 'payment_option_id', 'user_id', 'address_id', 'loyalty_amount_saved', 'total_discount', 'total_delivery_fee', 'total_amount', 'taxable_amount', 'wallet_amount_used', 'created_at');
        $order = $order->whereHas('vendors', function ($query) use ($vendor_id) {
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        })->with('vendors', function ($query) use ($vendor_id) {
            $query->select('id', 'order_id', 'vendor_id');
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        });
        $order = $order->find($order_id);
        $order_item_count = 0;
        $order->payment_option_title = $order->paymentOption->title;
        $order->item_count = $order_item_count;
        foreach ($order->products as $product) {
            $order_item_count += $product->quantity;
        }
        $order->item_count = $order_item_count;
        unset($order->products);
        unset($order->paymentOption);
        return $order;
    }



    /**
     * Credit Money Into order tip
     *
     * @return \Illuminate\Http\Response
     */
    public function tipAfterOrder(Request $request, $domain = '')
    {
        if( (isset($request->user_id)) && (!empty($request->user_id)) ){
            $user = User::find($request->user_id);
        }else{
            $user = Auth::user();
        }

        if ($user) {
            $order_number = $request->order_number;
            if ($order_number > 0) {
                $order = Order::select('id', 'tip_amount')->where('order_number', $order_number)->first();
                if (($order->tip_amount == 0) || empty($order->tip_amount)) {
                    $tip = Order::where('order_number', $order_number)->update(['tip_amount' => $request->tip_amount]);
                    $payment = new Payment();
                    $payment->date = date('Y-m-d');
                    $payment->order_id = $order->id;
                    $payment->transaction_id = $request->transaction_id;
                    $payment->balance_transaction = $request->tip_amount;
                    $payment->type = 'tip';
                    $payment->save();
                }
                $message = 'Tip has been submitted successfully';
                $response['tip_amount'] = $request->tip_amount;
                Session::put('success', $message);
                return $this->successResponse($response, $message, 200);
            } else {
                return $this->errorResponse('Amount is not sufficient', 400);
            }
        } else {
            return $this->errorResponse('Invalid User', 400);
        }
    }
}
