<?php

namespace App\Http\Controllers\Front;

use Session;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Contracts\Session\Session as SessionSession;
use App\Models\{Currency, Banner,FaqTranslations, Category, Brand, Product, ClientLanguage, Vendor, VendorCategory, ClientCurrency,Client, ClientPreference, DriverRegistrationDocument, HomePageLabel, Page, VendorRegistrationDocument, Language, OnboardSetting, CabBookingLayout, WebStylingOption, SubscriptionInvoicesVendor, Order, VendorOrderStatus,CabBookingLayoutTranslation};
use Illuminate\Contracts\View\View;
use Illuminate\View\View as ViewView;
use Redirect;
use DB;
use App\Providers\Custom\ClientCacheClass;
class UserhomeController extends FrontController
{
    use ApiResponser;
    private $field_status = 2;
    public $client_id =0;
    public $ClientPreferencs=[];
    public function __construct()
    {

        $obj = new ClientCacheClass;
        $this->client_id = $obj->client_id;
        $this->ClientPreferencs = $obj->ClientPreferencs;
    }


    public function setTheme(Request $request)
    {
        if ($request->theme_color == "dark") {
            Session::put('config_theme', $request->theme_color);
        } else {
            Session::forget('config_theme');
        }
    }
    public function getConfig()
    {
        $client_preferences =$this->ClientPreferencs;
        return response()->json(['success' => true, 'client_preferences' => $client_preferences]);

    }

    public function getLastMileTeams()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function getAgentTags()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function checkIfLastMileDeliveryOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }

    public function driverDocuments()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();
            $url = $dispatch_domain->delivery_service_key_url;
            $endpoint =$url . "/api/send-documents";
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

    public function driverSignup()
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::where('client_id',$this->client_id)->first();
        $navCategories = $this->categoryNav($language_id);
        $client = Auth::user();
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', 'driver-registration')->firstOrFail();
        $last_mile_teams = [];

        $tag = [];

        $showTag = implode(',', $tag);
        $driver_registration_documents = json_decode($this->driverDocuments());
        return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents'));
    }

    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::where('client_id',$this->client_id)->first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getExtraPage(Request $request)
    {
        $user = null;
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::where('client_id',$this->client_id)->first();;
        $navCategories = [];//$this->categoryNav($language_id);
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])
        ->where('slug', $request->slug)
        ->firstOrFail();
        $primary = \App\Models\PageTranslation::where('page_id',$page_detail->id)->first();
        
        if ($primary->type_of_form != 2) {
            if($primary->type_of_form == 3){
             $faq =   FaqTranslations::where('page_id',$page_detail->id)
                     ->where('language_id', session()->get('customerLanguage'))
                     ->get();
                     $page_detail->faqs_details = $faq;
            }
            $vendor_registration_documents = VendorRegistrationDocument::with(['primary','options','options.translation' => function($query) use($language_id) {
                $query->where('language_id', session()->get('customerLanguage'));
            }])
            ->get();
                 
            $client = \App\Models\Client::find($this->client_id);
            $business_type = !empty($client->business_types) ? json_decode($client->business_types) : [];
            $business_types = \App\Models\BusinessType::where('parent',0)
                                                       ->whereIn('id',$business_type)
                                                       ->orderBy('title','ASC')
                                                       ->paginate(20);


            return view('frontend.extrapage', compact('page_detail', 'navCategories', 'client_preferences', 'user', 'vendor_registration_documents','business_types'));
            } else {
                $tag = [];
                $showTag = implode(',', $tag);
                $client = Client::with('country')->first();
                $driverDocs = json_decode($this->driverDocuments());
                $driver_registration_documents = $driverDocs->documents;
                foreach ($driverDocs->documents as $key => $doc) {
                    $name = str_replace(" ", "_", $doc->name);
                    $doc->slug = $name;
                }
                $teams = $driverDocs->all_teams;
                $tags = $driverDocs->agent_tags;
                return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents','client', 'teams', 'tags'));
            }
    }
    public function vendorRegistration(Request $request)
    {
         
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::where('client_id',getWebClientID())->first();
        $navCategories = $this->categoryNav($language_id);
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', $request->slug)
        ->firstOrFail();

         return $page_detail->primary->type_of_form;
        if ($page_detail->primary->type_of_form != 2) {
            if($page_detail->primary->type_of_form == 3){
             $faq =   FaqTranslations::where('page_id',$page_detail->id)
                     ->where('language_id', session()->get('customerLanguage'))
                     ->get();
                     $page_detail->faqs_details = $faq;
            }
            $vendor_registration_documents = VendorRegistrationDocument::with(['primary','options','options.translation' => function($query) use($language_id) {
                $query->where('language_id', session()->get('customerLanguage'));
            }])->get();

            return view('frontend.extrapage', compact('page_detail', 'navCategories', 'client_preferences', 'user', 'vendor_registration_documents'));
            } else {
                $tag = [];
                $showTag = implode(',', $tag);
                $client = Client::with('country')->first();
                $driverDocs = json_decode($this->driverDocuments());
                $driver_registration_documents = $driverDocs->documents;
                foreach ($driverDocs->documents as $key => $doc) {
                    $name = str_replace(" ", "_", $doc->name);
                    $doc->slug = $name;
                }
                $teams = $driverDocs->all_teams;
                $tags = $driverDocs->agent_tags;
                return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents','client', 'teams', 'tags'));
            }
    }
    public function index(Request $request)
    {




        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
           
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            // if ($preferences) {
            //     if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
            //         $selectedAddress = $preferences->Default_location_name;
            //         $latitude = $preferences->Default_latitude;
            //         $longitude = $preferences->Default_longitude;
            //         Session::put('latitude', $latitude);
            //         Session::put('longitude', $longitude);
            //         Session::put('selectedAddress', $selectedAddress);
            //     }
            // }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                        ->where(function ($q) {
                            $q->whereNull('start_date_time')->orWhere(function ($q2) {
                                $q2->whereDate('start_date_time', '<=', Carbon::now())
                                    ->whereDate('end_date_time', '>=', Carbon::now());
                            });
                        })
                        ->where('client_id',$this->client_id)
                        ->orderBy('sorting', 'asc')
                        ->with('category')
                        ->with('vendor')
                        ->get();


            $home_page_labels = CabBookingLayout::where('is_active', 1)->where('for_no_product_found_html',0)->orderBy('order_by');

            if (isset($langId) && !empty($langId))
                $home_page_labels = $home_page_labels->with(['translations' => function ($q) use ($langId) {
                    $q->where('language_id', $langId);
                }]);

            $home_page_labels = $home_page_labels->get();

            if (count($home_page_labels) == 0)
                $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();


            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');

            $home_page_pickup_labels = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',0)->orderBy('order_by')->get();

            $set_template = WebStylingOption::where('web_styling_id', 1)->where('is_selected', 1)->first();

            $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get(); 

            // $last_mile = $this->checkIfLastMileDeliveryOn();
            if (isset($set_template)  && $set_template->template_id == 1)
                return view('frontend.home-template-one')->with(['home' => $home,  
                    'count' => $count, 
                    'for_no_product_found_html' => $for_no_product_found_html,
                    'homePagePickupLabels' => $home_page_pickup_labels, 
                    'homePageLabels' => $home_page_labels, 
                    'clientPreferences' => $clientPreferences, 
                    'banners' => $banners, 
                    'navCategories' => $navCategories, 
                    'selectedAddress' => $selectedAddress, 
                    'latitude' => $latitude, 
                    'longitude' => $longitude
                ]);
            if (isset($set_template)  && $set_template->template_id == 2)
                return view('frontend.home')->with([
                    'home' => $home, 
                    'count' => $count, 
                    'for_no_product_found_html' => $for_no_product_found_html,
                    'homePagePickupLabels' => $home_page_pickup_labels, 
                    'homePageLabels' => $home_page_labels, 
                    'clientPreferences' => $clientPreferences, 
                    'banners' => $banners, 
                    'navCategories' => $navCategories, 
                    'selectedAddress' => $selectedAddress, 
                    'latitude' => $latitude, 
                    'longitude' => $longitude]);
            else
            return view('frontend.home-template-one')->with([
                'home' => $home,  
                'count' => $count, 
                'for_no_product_found_html' => $for_no_product_found_html,
                'homePagePickupLabels' => $home_page_pickup_labels, 
                'homePageLabels' => $home_page_labels, 
                'clientPreferences' => $clientPreferences, 
                'banners' => $banners, 
                'navCategories' => $navCategories, 
                'selectedAddress' => $selectedAddress, 
                'latitude' => $latitude, 
                'longitude' => $longitude
            ]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    } 



    public function home2(Request $request)
    {

        // return ClientCurrencies();

        try {
            $home = array();
            
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
             
            $clientPreferences = clientSettings();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                        ->where(function ($q) {
                            $q->whereNull('start_date_time')->orWhere(function ($q2) {
                                $q2->whereDate('start_date_time', '<=', Carbon::now())
                                    ->whereDate('end_date_time', '>=', Carbon::now());
                            });
                        })
                        ->where('client_id',$this->client_id)
                        ->orderBy('sorting', 'asc')
                        ->with('category')
                        ->with('vendor')
                        ->get();


             
            // $last_mile = $this->checkIfLastMileDeliveryOn();
            
               return view('templates.modules.home')->with([
                    'clientPreferences' => $clientPreferences, 
                    'headerClass' => 'fixed inset-x-0 top-0 w-full z-20',
                    'banners' => $banners, 
                ]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }
    public function postHomePageData(Request $request)
    {

         // $client = \App\Models\Client::first();

         // dd($client->timezone_data);
        $vendor_ids = [];
        $new_products = [];
        $feature_products = [];
        $on_sale_products = [];
        if ($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        } else {
            $latitude = Session::get('latitude');
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        } else {
            $longitude = Session::get('longitude');
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        $preferences = (object)Session::get('preferences');
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');
      
        $currency_id = $this->setCurrencyInSesion();

        $featured_products_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','featured_products');})
        ->value('title');

        $vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','vendors');})
        ->value('title');

        $new_products_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','new_products');})
        ->value('title');

        $on_sale_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','on_sale');})
        ->value('title');

        $brands_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','brands');})
        ->value('title');

        $best_sellers_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','best_sellers');})
        ->value('title');

        $trending_vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','trending');})
        ->value('title');

        $recent_orders_title = CabBookingLayoutTranslation::where('language_id',$language_id)
        ->whereHas('layout',function($q){$q->where('slug','recent_orders');})
        ->value('title');
        


        $brands = Brand::select('id', 'image', 'title')->with(['translation' => function ($q) use ($language_id) {
            $q->where('language_id', $language_id);
        }])
        ->where('status', '!=', $this->field_status)
        ->orderBy('position', 'asc')
        ->get();
        foreach ($brands as $brand) {
            $brand->redirect_url = route('brandDetail', $brand->id);
            $brand->translation_title = $brand->translation->first() ? $brand->translation->first()->title : $brand->title;
        }
        Session::forget('vendorType');
        Session::put('vendorType', $request->type);
        $vendors = Vendor::with('products')
        ->with('slot.day', 'slotDate')
        ->select('id', 'name', 'banner', 'address', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude','show_slot')
        ->where('client_id',$this->client_id)
        ->where($request->type, 1);
        if ($preferences) {
            if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude;
                $longitude = $preferences->Default_longitude;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            } else {
                if (($latitude == $preferences->Default_latitude) && ($longitude == $preferences->Default_longitude)) {
                    Session::put('selectedAddress', $preferences->Default_location_name);
                }
            }
            if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {

                if (!empty($latitude) && !empty($longitude)) {
                    $vendors = $vendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                        $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                    });
                }
            }
        }
        $vendors = $vendors->where('status', 1)->inRandomOrder()->get();
        // dd($vendors);

        foreach ($vendors as $key => $value) {
            $vendor_ids[] = $value->id;
            $value->vendorRating = $this->vendorRating($value->products);
            // $value->name = Str::limit($value->name, 15, '..');
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
            }
            $vendorCategories = VendorCategory::with('category.translation_one')
            ->where('vendor_id', $value->id)
            ->where('status', 1)
            ->get();
            $categoriesList = '';
            foreach ($vendorCategories as $key => $category) {
                if ($category->category) {
                    $categoriesList = $categoriesList . @$category->category->translation_one->name ?? '';
                    if ($key !=  $vendorCategories->count() - 1) {
                        $categoriesList = $categoriesList . ', ';
                    }
                }
            }
            $value->categoriesList = $categoriesList;
            $value->type_title = $categoriesList;

            $value->is_vendor_closed = 0;
            if($value->show_slot == 0){
                if( ($value->slotDate->isEmpty()) && ($value->slot->isEmpty()) ){
                    $value->is_vendor_closed = 1;
                }else{
                    $value->is_vendor_closed = 0;
                    if($value->slotDate->isNotEmpty()){
                        $value->opening_time = Carbon::parse($value->slotDate->first()->start_time)->format('g:i A');
                        $value->closing_time = Carbon::parse($value->slotDate->first()->end_time)->format('g:i A');
                    }elseif($value->slot->isNotEmpty()){
                        $value->opening_time = Carbon::parse($value->slot->first()->start_time)->format('g:i A');
                        $value->closing_time = Carbon::parse($value->slot->first()->end_time)->format('g:i A');
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $vendors = $vendors->sortBy('lineOfSightDistance')->values()->all();
        }
        $now = Carbon::now()->toDateTimeString();
        $subscribed_vendors_for_trending = SubscriptionInvoicesVendor::with('features')->whereHas('features', function ($query) {
                $query->where(['subscription_invoice_features_vendor.feature_id' => 1]);
            })
            ->select('id', 'vendor_id', 'subscription_id')
            ->where('end_date', '>=', $now)
            ->whereIn('subscription_invoices_vendor.vendor_id', $vendor_ids)
            ->pluck('vendor_id')
            ->toArray();

        if (($latitude) && ($longitude)) {
            Session::put('vendors', $vendor_ids);
        }

        $trendingVendors = Vendor::with('slot.day', 'slotDate')
        ->where('client_id',$this->client_id)
        ->whereIn('id', $subscribed_vendors_for_trending)
        ->where('status', 1)
        ->inRandomOrder()->get();

        if ((!empty($trendingVendors) && count($trendingVendors) > 0)) {
            foreach ($trendingVendors as $key => $value) {
                $value->tag_title = $trending_vendors_title??'0';
                $value->vendorRating = $this->vendorRating($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;
                $value->is_vendor_closed = 0;
                if($value->show_slot == 0){
                    if( ($value->slotDate->isEmpty()) && ($value->slot->isEmpty()) ){
                        $value->is_vendor_closed = 1;
                    }else{
                        $value->is_vendor_closed = 0;
                        if($value->slotDate->isNotEmpty()){
                            $value->opening_time = Carbon::parse($value->slotDate->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slotDate->first()->end_time)->format('g:i A');
                        }elseif($value->slot->isNotEmpty()){
                            $value->opening_time = Carbon::parse($value->slot->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $trendingVendors = $trendingVendors->sortBy('lineOfSightDistance')->values()->all();
        }
        $mostSellingVendors = Vendor::with('slot.day', 'slotDate')->select('vendors.*',DB::raw('count(vendor_id) as max_sales'))->join('order_vendors','vendors.id','=','order_vendors.vendor_id')
        ->whereIn('vendors.id',$vendor_ids) 
        ->where('vendors.status', 1)->groupBy('order_vendors.vendor_id')->orderBy(DB::raw('count(vendor_id)'),'desc')->get();
        if ((!empty($mostSellingVendors) && count($mostSellingVendors) > 0)) {
            foreach ($mostSellingVendors as $key => $value) {
                $value->vendorRating = $this->vendorRating($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;

                $value->is_vendor_closed = 0;
                if($value->show_slot == 0){
                    if( ($value->slotDate->isEmpty()) && ($value->slot->isEmpty()) ){
                        $value->is_vendor_closed = 1;
                    }else{
                        $value->is_vendor_closed = 0;
                        if($value->slotDate->isNotEmpty()){
                            $value->opening_time = Carbon::parse($value->slotDate->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slotDate->first()->end_time)->format('g:i A');
                        }elseif($value->slot->isNotEmpty()){
                            $value->opening_time = Carbon::parse($value->slot->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $mostSellingVendors = $mostSellingVendors->sortBy('lineOfSightDistance')->values()->all();
        }

        $on_sale_product_details = $this->vendorProducts($vendor_ids, $language_id, 'USD', '', $request->type);
        $new_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_new', $request->type);
        $feature_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_featured', $request->type);
        foreach ($new_product_details as  $new_product_detail) {
            $multiply = $new_product_detail->variant->first() ? $new_product_detail->variant->first()->multiplier : 1;
            $title = $new_product_detail->translation->first() ? $new_product_detail->translation->first()->title : $new_product_detail->sku;
            $image_url = $new_product_detail->media->first() ? $new_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
            $new_products[] = array(
                'tag_title' => $new_products_title??0,
                'image_url' => $image_url,
                'sku' => $new_product_detail->sku,
                'title' =>  $title,
                'url_slug' => $new_product_detail->url_slug,
                'averageRating' => number_format($new_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $new_product_detail->inquiry_only,
                'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                'vendor' => $new_product_detail->vendor,
                'price' => Session::get('currencySymbol') . ' ' . (number_format($new_product_detail->variant->first()->price??0 * $multiply, 2)),
                'category' => (@$new_product_detail->category->categoryDetail->translation) ? @$new_product_detail->category->categoryDetail->translation->first()->name : @$new_product_detail->category->categoryDetail->slug
            );
        }
        foreach ($feature_product_details as  $feature_product_detail) {
            $multiply = $feature_product_detail->variant->first() ? $feature_product_detail->variant->first()->multiplier : 1;
            $title = $feature_product_detail->translation->first() ? $feature_product_detail->translation->first()->title : $feature_product_detail->sku;
            $image_url = $feature_product_detail->media->first() ? $feature_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
            $feature_products[] = array(
                'tag_title' => $featured_products_title??'0',
                'image_url' => $image_url,
                'sku' => $feature_product_detail->sku,
                'title' => $title,
                'url_slug' => $feature_product_detail->url_slug,
                'averageRating' => number_format($feature_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $feature_product_detail->inquiry_only,
                'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                'vendor' => $feature_product_detail->vendor,
                'price' => Session::get('currencySymbol') . ' ' . (number_format($feature_product_detail->variant->first()->price * $multiply, 2)),
                'category' => (@$feature_product_detail->category->categoryDetail->translation) ? @$feature_product_detail->category->categoryDetail->translation->first()->name : @$feature_product_detail->category->categoryDetail->slug
            );
        }
        foreach ($on_sale_product_details as  $on_sale_product_detail) {
            $multiply = $on_sale_product_detail->variant->first() ? $on_sale_product_detail->variant->first()->multiplier : 1;
            $title = $on_sale_product_detail->translation->first() ? $on_sale_product_detail->translation->first()->title : $on_sale_product_detail->sku;
            $image_url = $on_sale_product_detail->media->first() ? $on_sale_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
            $on_sale_products[] = array(
                'tag_title' => $on_sale_title??'0',
                'image_url' => $image_url,
                'sku' => $on_sale_product_detail->sku,
                'title' => $title,
                'url_slug' => $on_sale_product_detail->url_slug,
                'averageRating' => number_format($on_sale_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $on_sale_product_detail->inquiry_only,
                'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                'vendor' => $on_sale_product_detail->vendor,
                'price' => Session::get('currencySymbol') . ' ' . (number_format($on_sale_product_detail->variant->first()->price??0 * $multiply, 2)),
                'category' => ($on_sale_product_detail->category->categoryDetail->translation) ? ( $on_sale_product_detail->category->categoryDetail->translation->first()->name ?? $on_sale_product_detail->category->categoryDetail->slug): $on_sale_product_detail->category->categoryDetail->slug??''
            );
        }
        $home_page_labels = HomePageLabel::with('translations')->get();

        $activeOrders = [];



       if(Auth::check()){
        $user = Auth::user();

        if ($user) {
            $activeOrders = Order::with([
                'vendors' => function ($q) {
                    $q->where('order_status_option_id', '!=', 6);
                },
                'vendors.dineInTable.translations' => function ($qry) use ($language_id) {
                    $qry->where('language_id', $language_id);
                }, 'vendors.dineInTable.category', 'vendors.products', 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image', 'user', 'address'
            ])->whereHas('vendors', function ($q) {
                    $q->where('order_status_option_id', '!=', 6);
                })
                ->where('orders.user_id', $user->id)
                ->take(10)
                ->orderBy('orders.id', 'DESC')->get();
            foreach ($activeOrders as $order) {
                foreach ($order->vendors as $vendor) {
                    // dd($vendor->toArray());
                    $vendor->tag_title = $vendor_title??'0';
                    $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                    $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
                    foreach ($vendor->products as $product) {
                        if (isset($product->pvariant) && $product->pvariant->media->isNotEmpty()) {
                            $product->image_url = $product->pvariant->media->first()->pimage->image->path['image_path'];
                        } elseif ($product->media->isNotEmpty()) {
                            $product->image_url = $product->media->first()->image->path['image_path'];
                        } else {
                            $product->image_url = ($product->image) ? $product->image['image_path'] : '';
                        }
                        $product->pricedoller_compare = 1;
                    }
                    if ($vendor->delivery_fee > 0) {
                        $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                        $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                        $ETA = $order_pre_time + $user_to_vendor_time;
                        $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : dateTimeInUserTimeZone($vendor->created_at, $user->timezone);
                    }
                    if ($vendor->dineInTable) {
                        $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                        $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                        $vendor->dineInTableCategory = $vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
                    }


                }
                $order->converted_scheduled_date_time = dateTimeInUserTimeZone($order->scheduled_date_time, $user->timezone);
            }
        }

    }

        $data = [
            'brands' => $brands,
            'vendors' => $vendors,
            'new_products' => $new_products,
            'homePageLabels' => $home_page_labels,
            'feature_products' => $feature_products,
            'on_sale_products' => $on_sale_products,
            'trending_vendors' => (!empty($trendingVendors) && count($trendingVendors) > 0)?$trendingVendors:$mostSellingVendors,
            'active_orders' => $activeOrders
        ];
        return $this->successResponse($data);
    }

    public function vendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type)
    {
        $products = Product::with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor' => function ($q) use ($type) {
                $q->where($type, 1);
            },
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        $pndCategories = Category::where('type_id', 7)->pluck('id');
        if (is_array($venderIds)) {
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        if ($pndCategories) {
            $products = $products->whereNotIn('category_id', $pndCategories);
        }
        $products = $products->where('is_live', 1)->take(10)->inRandomOrder()->get();
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
    }

    public function changePrimaryData(Request $request)
    {
        if ($request->has('type') && $request->type == 'language') {
            $clientLanguage = ClientLanguage::where('language_id', $request->value1)->first();
            if ($clientLanguage) {
                $lang_detail = Language::where('id', $request->value1)->first();
                App::setLocale($lang_detail->sort_code);
                session()->put('locale', $lang_detail->sort_code);
                Session::put('customerLanguage', $request->value1);
            }
        }
        if ($request->has('type') && $request->type == 'currency') {
            $clientCurrency = ClientCurrency::where('currency_id', $request->value1)->first();
            if ($clientCurrency) {
                $currency_detail = Currency::where('id', $request->value1)->first();
                Session::put('currencySymbol', $request->value2);
                Session::put('customerCurrency', $request->value1);
                Session::put('iso_code', $currency_detail->iso_code);
                Session::put('currencyMultiplier', $clientCurrency->doller_compare);
            }
        }
        $data['customerLanguage'] = Session::get('customerLanguage');
        $data['customerCurrency'] = Session::get('customerCurrency');
        $data['currencySymbol'] = Session::get('currencySymbol');
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $data]);
    }

    public function changePaginate(Request $request)
    {
        $perPage = 12;
        if ($request->has('itemPerPage')) {
            $perPage = $request->itemPerPage;
        }
        Session::put('cus_paginate', $perPage);
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $perPage]);
    }

    public function getClientPreferences(Request $request)
    {
        $clientPreferences = ClientPreference::first();
        if ($clientPreferences) {
            $dinein_check = $clientPreferences->dinein_check;
            $delivery_check = $clientPreferences->delivery_check;
            $takeaway_check = $clientPreferences->takeaway_check;
            $age_restriction = $clientPreferences->age_restriction;
            return response()->json(["age_restriction" => $age_restriction, "dinein_check" => $dinein_check, "delivery_check" => $delivery_check, "takeaway_check" => $takeaway_check]);
        }
    }


    /////    new home page
    public function indexTemplateOne(Request $request)
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            // if ($preferences) {
            //     if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
            //         $selectedAddress = $preferences->Default_location_name;
            //         $latitude = $preferences->Default_latitude;
            //         $longitude = $preferences->Default_longitude;
            //         Session::put('latitude', $latitude);
            //         Session::put('longitude', $longitude);
            //         Session::put('selectedAddress', $selectedAddress);
            //     }
            // }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->with('category')->with('vendor')->get();
            $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();

            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');
            $home_page_pickup_labels = CabBookingLayout::with(['translations' => function ($q) use ($langId) {
                $q->where('language_id', $langId);
            }])->where('is_active', 1)->orderBy('order_by')->where('for_no_product_found_html',0)->get();
            $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get();

            $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get();

            return view('frontend.home-template-one')->with(['home' => $home, 'count' => $count, 'for_no_product_found_html' => $for_no_product_found_html,'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }

    # category menu 

    public function homePageDataCategoryMenu(Request $request)
    {
          if ($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        } else {
            $latitude = Session::get('latitude');
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        } else {
            $longitude = Session::get('longitude');
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        $preferences = Session::get('preferences');
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');
     
        $currency_id = $this->setCurrencyInSesion();

    
        Session::forget('vendorType');
        Session::put('vendorType', $request->type);
       
        $now = Carbon::now()->toDateTimeString();
     
        Session::forget('navCategories');

         $navCategories = categoryNav(); //$this->categoryNav($language_id);
        Session::put('navCategories', $navCategories);
      
        $user = Auth::user();
     

        $data = [
           'navCategories' => $navCategories,
             ];
        return $this->successResponse($data);
    }
}
