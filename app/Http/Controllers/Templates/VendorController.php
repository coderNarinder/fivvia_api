<?php

namespace App\Http\Controllers\Templates;

use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner,Tag ,Category, Brand, Product, ProductCategory, ClientLanguage, Vendor, VendorCategory, ClientCurrency, ProductVariantSet,CabBookingLayout};

class VendorController extends FrontController
{
    use ApiResponser;
    private $field_status = 2;
    public $client_id =0;
    public function __construct()
    {
        $this->client_id = getWebClientID();
    }

    public function viewAll(){
        $langId = Session::get('customerLanguage');
        $vendorType = Session::get('vendorType');
        $preferences = (object)Session::get('preferences');
        $navCategories = $this->categoryNav($langId);
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 30;
        $ses_vendors = $this->getServiceAreaVendors();


        $vendors = Vendor::with('products')->select('id', 'name', 'banner', 'address', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude','client_id')
        ->where('status', 1)
        ->where('client_id',$this->client_id)
        ->where($vendorType, 1);

        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $latitude = Session::get('latitude') ?? $preferences->Default_latitude;
            $longitude = Session::get('longitude') ?? $preferences->Default_longitude;
            $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
            //3961 for miles and 6371 for kilometers
            $calc_value = ($distance_unit == 'mile') ? 3961 : 6371;
            $vendors = $vendors->select('*', DB::raw(' ( ' .$calc_value. ' * acos( cos( radians(' . $latitude . ') ) *
                    cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) +
                    sin( radians(' . $latitude . ') ) *
                    sin( radians( latitude ) ) ) )  AS vendorToUserDistance'))->orderBy('vendorToUserDistance', 'ASC');
            $vendors = $vendors->whereIn('id', $ses_vendors);
        }

        $vendors = $vendors->paginate($pagiNate);

        foreach ($vendors as $key => $value) {
            $value = $this->getLineOfSightDistanceAndTime($value, $preferences);
            $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
            $categoriesList = '';
            foreach($vendorCategories as $key => $category){
                if($category->category){
                    $categoriesList = $categoriesList . $category->category->translation_one->name??'';
                    if( $key !=  $vendorCategories->count()-1 ){
                        $categoriesList = $categoriesList . ', ';
                    }
                }
            }
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $latitude = Session::get('latitude');
                $longitude = Session::get('longitude');
                $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
            }
            $value->categoriesList = $categoriesList;
            $value->vendorRating = $this->vendorRating($value->products);
        }
        $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get();
        // dd($for_no_product_found_html);
        return view('frontend/vendor-all')
        ->with(['navCategories' => $navCategories,
            'for_no_product_found_html' => $for_no_product_found_html,
            'vendors' => $vendors]);
    }
    /**
     * Display product By Vendor
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorProducts(Request $request,$slug = 0){
 
 
        if($request->ajax())
        {
          $returnHTML = $this->vendorFilters($request,'',$slug);
          return response()->json(array('success' => true, 'html'=>$returnHTML));
        }
        $tag_id = $request->has('tag') && $request->tag ? $request->tag : null;
        $preferences = Session::get('preferences');
        $vendor = Vendor::with('slot.day', 'slotDate')
                 ->select('id','email', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id', 'is_show_vendor_details', 'website', 'show_slot','closed_store_order_scheduled','client_id')
            ->where('slug', $slug)
            ->where('client_id',$this->client_id)
            ->where('status', 1)
            ->firstOrFail();
        $vendor->is_vendor_closed = 0;
        if($vendor->show_slot == 0){ 
            if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                $vendor->is_vendor_closed = 1;
            }else{
                $vendor->is_vendor_closed = 0;
                if($vendor->slotDate->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                }elseif($vendor->slot->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                }
            }
        }
        // dd($vendor->toArray());
        if( $request->has('table') ){
            if(!Auth::user()){
                session(['url.intended' => url()->full()]);
                return redirect()->route('customer.login');
            }else{
                if(!Session::has('vendorTable')){
                    Session::put('vendorTable', $request->table);
                    Session::put('vendorTableVendorId', $vendor->id);
                    Session::put('vendorType', 'dine_in');
                }
            }
        }

        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->where('client_id',$this->client_id)->first();
        $brands = Product::with(['brand.translation'=> function($q) use($langId){
                    $q->select('title', 'brand_id')->where('brand_translations.language_id', $langId);
                 }])->select('brand_id')
                ->where('vendor_id', $vendor->id)
                ->where('brand_id', '>', 0)
                ->groupBy('brand_id')->get();
        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                            $zx->select('variant_options.*', 'vt.title');
                            $zx->where('vt.language_id', $langId);
                        },
                        'variantDetail.varcategory.cate'
                    ]
                )->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                    ->join('variant_translations as vt','vt.variant_id','vr.id')
                    ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                    ->where('vt.language_id', $langId)
                    ->whereIn('product_id', function($qry) use($vendor){
                        $qry->select('id')->from('products')
                            ->where('vendor_id', $vendor->id);
                    })->groupBy('product_variant_sets.variant_type_id')->get();
        $navCategories = $this->categoryNav($langId);
        $vendorIds[] = $vendor->id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        $listData = $this->listData($langId, $vendor->id, $vendor->vendor_templete_id,'',$tag_id);
        $inqury_count = 0;
        foreach($listData as $ld){
            if($ld->inquiry_only == 1){
                $inqury_count++;
            }
        }
        if($listData->count() == $inqury_count){
            $show_range = 0;
        }
        else{
            $show_range = 1;
        }
        $range_products = Product::join('product_variants', 'product_variants.product_id', '=', 'products.id')->orderBy('product_variants.price', 'desc')->select('*')->where('is_live', 1)->where('vendor_id', $vendor->id)->get();

        if($vendor->vendor_templete_id == 2){
            $page = 'categories';
        }elseif($vendor->vendor_templete_id == 5){
            $page = 'products-with-categories';
            $products = Product::select('averageRating')->where('is_live', 1)->where('vendor_id', $vendor->id)->get();
            $vendor->vendorRating = $this->vendorRating($products);
        }else{
            $page = 'products';
        }
 
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
                if(!in_array($vendor->id, $vendors)){
                    $listData =collect();

                     $page = getTemplateLayoutPath('modules').'vendor-'.$page;
                    return view($page)->with(['show_range' => $show_range, 'range_products' => $range_products, 'vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands]);
                //  return view('frontend.vendor-not-in-location')->with(['show_range' => $show_range, 'range_products' => $range_products, 'vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands]);
                    abort(404);
                }
            }else{
                // abort(404);
            }
        }
 
        $is_vendor_closed = 0;
        if($vendor->show_slot == 0){
            if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                $is_vendor_closed = 1;
            }else{
                $is_vendor_closed = 0;
            }
        }

        $tags = Tag::with('primary')->get();  
         //`dd($page);
       // dd( $is_vendor_closed );

        $page = getTemplateLayoutPath('modules').'vendor-'.$page;

        // $page = ($vendor->vendor_templete_id == 2) ? 'categories' : 'products';
        return view($page)->with(['show_range' => $show_range,'tags' => $tags, 'range_products' => $range_products, 'vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands,'is_vendor_closed'=>$is_vendor_closed]);
    }

    /**
     * Display product By Vendor Category
     * vendor -> category -> product
     * @return \Illuminate\Http\Response
     */
    public function vendorCategoryProducts(Request $request, $slug1 = 0, $slug2 = 0){
        // slug1 =>vendor slug
        // slug2 =>category slug

        $tag_id = $request->has('tag') && $request->tag ? $request->tag : null;
        $preferences = Session::get('preferences');
        $vendor = Vendor::select('id','email', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id', 'is_show_vendor_details', 'website', 'show_slot','closed_store_order_scheduled')->where('slug', $slug1)->where('status', 1)->firstOrFail();
        $category = Category::select('id')->where('slug', $slug2)->firstOrFail();
        $vendor_categories = VendorCategory::where('vendor_id', $vendor->id)->where('category_id', $category->id)->where('status', 1)->first();
        $vendor->is_vendor_closed = 0;
        if($vendor->show_slot == 0){
            if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                $vendor->is_vendor_closed = 1;
            }else{
                $vendor->is_vendor_closed = 0;
                if($vendor->slotDate->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                }elseif($vendor->slot->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                }
            }
        }

        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();

        $brands = Product::with(['brand.translation'=> function($q) use($langId){
                    $q->select('title', 'brand_id')->where('brand_translations.language_id', $langId);
                }])->select('brand_id')->where('vendor_id', $vendor->id)
                ->where('brand_id', '>', 0)->groupBy('brand_id')->get();
                $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                    $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                    $zx->select('variant_options.*', 'vt.title');
                    $zx->where('vt.language_id', $langId);
                }
                ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                ->join('variant_translations as vt','vt.variant_id','vr.id')
                ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                ->where('vt.language_id', $langId)
                ->whereIn('product_id', function($qry) use($vendor){
                    $qry->select('id')->from('products')
                    ->where('vendor_id', $vendor->id);
                })->groupBy('product_variant_sets.variant_type_id')->get();
        $navCategories = $this->categoryNav($langId);
        $vendorIds[] = $vendor->id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        if(!empty($slug2) && ($vendor->vendor_templete_id == 2)){
            $vendor->vendor_templete_id = '';
        }
        $listData = $this->listData($langId, $vendor->id, $vendor->vendor_templete_id, $slug2,$tag_id);
        $inqury_count = 0;
        foreach($listData as $ld){
            if($ld->inquiry_only == 1){
                $inqury_count++;
            }
        }
        if($listData->count() == $inqury_count){
            $show_range = 0;
        }
        else{
            $show_range = 1;
        }
        if($vendor->vendor_templete_id == 2){
            $page = 'categories';
        }elseif($vendor->vendor_templete_id == 5){
            $page = 'products-with-categories';
            $products = Product::select('averageRating')->where('is_live', 1)->where('vendor_id', $vendor->id)->get();
            $vendor->vendorRating = $this->vendorRating($products);
        }else{
            $page = 'products';
        }
        // $page = ($vendor->vendor_templete_id == 2) ? 'categories' : 'products';
        $range_products = Product::join('product_variants', 'product_variants.product_id', '=', 'products.id')->orderBy('product_variants.price', 'desc')->select('*')->where('is_live', 1)->where('vendor_id', $vendor->id)->get();

        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
                if(!in_array($vendor->id, $vendors)){
                    $listData = collect();
                    return view('frontend/vendor-'.$page)->with(['vendor' => $vendor, 'show_range' => $show_range, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands, 'range_products' => $range_products, 'vendor_category' => $slug2]);

                //    return view('frontend.vendor-not-in-location')->with(['vendor' => $vendor, 'show_range' => $show_range, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands, 'range_products' => $range_products, 'vendor_category' => $slug2]);
                    abort(404);
                }
            }else{
                // abort(404);
            }
        }


        return view('frontend/vendor-'.$page)->with(['vendor' => $vendor, 'show_range' => $show_range, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands, 'range_products' => $range_products, 'vendor_category' => $slug2]);
    }

    public function listData($langId, $vid, $type = '', $categorySlug = '',$tag_id=''){
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        if($type == 2){
            // display categories
            $product_query = Product::select('category_id')->distinct()->where('vendor_id', $vid)->where('is_live', 1);
            if($tag_id){
               // $product_query
            }
            $products =  $product_query->get();
            $vendor_categories = array();
            foreach($products as $key => $product ){
                $vendor_categories[] = $product->category_id;
            }
            $categoryData = Category::select('id', 'icon', 'slug', 'type_id', 'image')
                ->whereIn('id', $vendor_categories);
            $categoryData = $categoryData->paginate($pagiNate);
            foreach ($categoryData as $key => $value) {
                $value->translation_name = ($value->translation->first()) ? $value->translation->first()->name : 'NA';
            }
            return $categoryData;
        }
        elseif($type == 5){
            // listing category with products
            $user = Auth::user();
            if ($user) {
                $column = 'user_id';
                $value = $user->id;
            } else {
                $column = 'unique_identifier';
                $value = session()->get('_token');
            }
            $cur_ids = Session::get('customerCurrency');
            if(isset($cur_ids) && !empty( $cur_ids))
            $clientCurrency = ClientCurrency::where('currency_id','=', $cur_ids)->first();
            else
            {
                $primaryCurrency = ClientCurrency::where('is_primary','=', 1)->first();
                $cur_ids = $primaryCurrency->currency_id;
                $clientCurrency = ClientCurrency::where('currency_id','=', $cur_ids)->first();
            }

            $vendor_categories = VendorCategory::with(['category.translation' => function($q) use($langId){
                $q->where('category_translations.language_id', $langId);
            }])
            ->whereHas('category')
            ->where('vendor_id', $vid);
            if($categorySlug != ''){
                $vendor_categories = $vendor_categories->whereHas('category', function($query) use($categorySlug) {
                    $query->where('slug', $categorySlug);
                });
                $vendor_categories = $vendor_categories->where('status', 1)->get();
                foreach($vendor_categories as $category){
                    $childs = $this->getChildCategoriesForVendor($category->category_id, $langId, $vid);
                    foreach($childs as $child){
                        $vendor_categories->push($child);
                    }
                }
            }
            else{
                // $vendor_categories = $vendor_categories->whereHas('category', function($query) {
                //     $query->whereIn('type_id', [1]);
                // });
                $vendor_categories = $vendor_categories->where('status', 1)->get();
            }

            foreach($vendor_categories as $ckey => $category) {
                $products = Product::with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId,$column,$value){
                            $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price')->orderBy('quantity', 'desc');
                            // $q->groupBy('product_id');
                        },'variant.checkIfInCart.addon',
                        'addOn' => function ($q1) use ($langId) {
                            $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                            $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                            $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                            $q1->where('set.status', 1)->where('ast.language_id', $langId);
                        },
                        'addOn.setoptions' => function ($q2) use ($langId) {
                            $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                            $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                            $q2->where('apt.language_id', $langId);
                        }
                    ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only','minimum_order_count','batch_count');
                $products = $products->where('is_live', 1)->where('category_id', $category->category_id)->where('vendor_id', $vid)->get();

                if(!empty($products)){
                    foreach ($products as $key => $value) {
                        foreach ($value->addOn as $key => $val) {
                            foreach ($val->setoptions as $k => $v) {
                                if($v->price == 0){
                                    $v->is_free = true;
                                }else{
                                    $v->is_free = false;
                                }
                                $v->multiplier = $clientCurrency->doller_compare??1;
                            }
                        }

                        $p_id = $value->id;
                        $variantData = $value->with(['variantSet' => function ($z) use ($langId, $p_id) {
                            $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                            $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                            $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                            $z->where('vt.language_id', $langId);
                            $z->where('product_variant_sets.product_id', $p_id)->where('vr.status', 1)->orderBy('product_variant_sets.variant_type_id', 'asc');
                        },'variantSet.option2'=> function ($zx) use ($langId, $p_id) {
                            $zx->where('vt.language_id', $langId)
                            ->where('product_variant_sets.product_id', $p_id);
                        }])->where('id', $p_id)->first();
                        $value->variantSet = $variantData->variantSet;
                        $value->product_image = ($value->media->isNotEmpty()) ? $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                        $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                        $value->translation_description = ($value->translation->isNotEmpty()) ? html_entity_decode(strip_tags($value->translation->first()->body_html)) : '';
                        $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                        $value->variant_price = ($value->variant->isNotEmpty()) ? $value->variant->first()->price : 0;
                        $value->variant_id = ($value->variant->isNotEmpty()) ? $value->variant->first()->id : 0;
                        $value->variant_quantity = ($value->variant->isNotEmpty()) ? $value->variant->first()->quantity : 0;
                    }
                }
                if($products->count() > 0){
                    $category->products = $products;
                    $category->products_count = $products->count();
                }else{
                    // if($categorySlug != ''){
                    //     $category->products_count = $products->count();
                    // }
                    // else{
                        unset($vendor_categories[$ckey]);
                    // }
                }
            }
            //  dd($vendor_categories->toArray());
            $listData = $vendor_categories;
            return $listData;
        }
        else{
            $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
            $products = Product::with(['category.categoryDetail.translation' => function($q) use($langId){
                            $q->where('category_translations.language_id', $langId);
                        },
                        'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
            if(!empty($categorySlug)){
                $category = Category::select('id')->where('slug', $categorySlug)->firstOrFail();
                $products = $products->where('category_id', $category->id);
            }
            $products = $products->where('is_live', 1)->where('vendor_id', $vid)->paginate($pagiNate);
            if(!empty($products)){
                foreach ($products as $key => $value) {
                    $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                    $value->translation_description = ($value->translation->isNotEmpty()) ? html_entity_decode(strip_tags($value->translation->first()->body_html)) : '';
                    $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                    $value->category_name = ($value->category->categoryDetail->translation->first()) ? $value->category->categoryDetail->translation->first()->name : $value->category->categoryDetail->slug;
                    $value->image_url = $value->media->first() ? $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                    // foreach ($value->variant as $k => $v) {
                    //     $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    // }
                }
            }
            $listData = $products;
            return $listData;
        }
    }

    public function vendorProductAddons(Request $request){
        $langId = Session::get('customerLanguage');
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $variant_id = ($request->has('variant')) ? $request->variant : 0;
        $AddonData = Product::with(['media.image', 'translation' => function($q) use($langId){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                },
                'variant' => function($q) use($langId, $variant_id){
                    $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price');
                    $q->where('id', $variant_id);
                    // $q->groupBy('product_id');
                },'variant.media.pimage.image','variant.checkIfInCart',
                'addOn' => function ($q1) use ($langId) {
                    $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                    $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                    $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                    $q1->where('set.status', 1)->where('ast.language_id', $langId);
                },
                'addOn.setoptions' => function ($q2) use ($langId) {
                    $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                    $q2->select('addon_options.id', 'addon_options.price', 'apt.title', 'addon_options.addon_id', 'apt.language_id');
                    $q2->where('apt.language_id', $langId)->groupBy(['addon_options.id', 'apt.language_id']);
                }
            ])->where('is_live', 1)->where('url_slug', $request->slug)->first();
        if(!empty($AddonData)){
            if($AddonData->variant->first()->media->isNotEmpty()){
                $image_fit = $AddonData->variant->first()->media->first()->pimage->image->path['image_fit'];
                $image_path = $AddonData->variant->first()->media->first()->pimage->image->path['image_path'];
            }else{
                $image_fit = ($AddonData->media->isNotEmpty()) ? $AddonData->media->first()->image->path['image_fit'] : '';
                $image_path = ($AddonData->media->isNotEmpty()) ? $AddonData->media->first()->image->path['image_path'] : '';
            }
            $AddonData->product_image =  $image_path;
            $AddonData->translation_title = ($AddonData->translation->isNotEmpty()) ? $AddonData->translation->first()->title : $AddonData->title;
            $AddonData->translation_description = ($AddonData->translation->isNotEmpty()) ? strip_tags($AddonData->translation->first()->body_html) : '';
            $AddonData->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
            $variant_price = ($AddonData->variant->isNotEmpty()) ? $AddonData->variant->first()->price : 0;
            $AddonData->variant_price = number_format(($variant_price * $AddonData->variant_multiplier), 2, '.', '');
        }
            // dd($AddonData);
        return response()->json(array('status' => 'Success', 'data' => $AddonData));
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function vendorFilters(Request $request, $domain = '', $slug = 0){
        $setArray = $optionArray = array();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        if($request->has('variants') && !empty($request->variants)){
            $setArray = array_unique($request->variants);
        }
        $startRange = 0; $endRange = 20000;
        if($request->has('range') && !empty($request->range)){
            $range = explode(';', $request->range);
            $clientCurrency->doller_compare;
            $startRange = $range[0] / $clientCurrency->doller_compare;
            $endRange = $range[1] / $clientCurrency->doller_compare;
        }
        $multiArray = array();
        if($request->has('options') && !empty($request->options)){
            foreach ($request->options as $key => $value) {
                $multiArray[$request->variants[$key]][] = $value;
            }
        }
        $variantIds = $productIds = array();
        if(!empty($multiArray)){
            foreach ($multiArray as $key => $value) {
                $new_pIds = $new_vIds = array();
                $vResult = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id')->select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
                    ->where('product_variant_sets.variant_type_id', $key)
                    ->whereIn('product_variant_sets.variant_option_id', $value);

                if(!empty($variantIds)){
                    $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
                }
                $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();
                if($vResult){
                    foreach ($vResult as $key => $value) {
                        $new_vIds[] = $value->product_variant_id;
                        $new_pIds[] = $value->product_id;
                    }
                }
                $variantIds = $new_vIds;
                $productIds = $new_pIds;
            }
        }
        $order_type = $request->has('order_type') ? $request->order_type : '';
        $products = Product::with(['media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            if(!empty($order_type) && $order_type == 'low_to_high'){
                                $q->orderBy('price', 'asc');
                            }
                            if(!empty($order_type) && $order_type == 'high_to_low'){
                                $q->orderBy('price', 'desc');
                            }
                            $q->groupBy('product_id');
                        },'category.categoryDetail.translation' => function($q) use($langId){
                            $q->where('category_translations.language_id', $langId);
                        },
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    // ->where('vendor_id', $vid)
                    ->whereHas('vendor', function($query) use($slug){
                        $query->where('slug',$slug);
                    })
                    ->where('is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){
                        $qr->select('product_id')->from('product_variants')
                            ->where('status', 1)
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
                        });
        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        if($request->has('brands') && !empty($request->brands)){
            $products = $products->whereIn('brand_id', $request->brands);
        }
        if(!empty($order_type) && $request->order_type == 'rating'){
            $products = $products->orderBy('averageRating', 'desc');
        }
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        $products = $products->paginate($pagiNate);
        if(!empty($products)){
            foreach ($products as $key => $value) {
                $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                $value->image_url = $value->media->first() ? $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $value->category_name = ($value->category->categoryDetail->translation->first()) ? $value->category->categoryDetail->translation->first()->name : $value->category->categoryDetail->slug;
                // foreach ($value->variant as $k => $v) {
                //     $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                // }
            }
        }
        $listData = $products;
        $returnHTML = view('frontend.ajax.productList')->with(['listData' => $listData])->render(); 
        return $returnHTML;
        // return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    public function vendorProductsSearchResults(Request $request)
    {
        $response = [];
        $tagId = $request->input('tag_id');
        $keyword = $request->input('keyword');
        $vid = $request->input('vendor');
        $vCat = $request->input('vendor_category');
        $langId = Session::get('customerLanguage');
        $preferences = Session::get('preferences');

        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();

        $vendor = Vendor::with('slot.day', 'slotDate')
            ->select('id','email', 'name', 'show_slot')->where('id', $vid)->where('status', 1)->firstOrFail();
        $vendor->is_vendor_closed = 0;
        if($vendor->show_slot == 0){
            if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                $vendor->is_vendor_closed = 1;
            }else{
                $vendor->is_vendor_closed = 0;
                if($vendor->slotDate->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                }elseif($vendor->slot->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                }
            }
        }

        $vendorCategory = [];
        if($vCat != ''){
            $category = Category::select('id')->where('slug', $vCat)->firstOrFail();
            array_push($vendorCategory, $category->id);
            $childs = $this->getChildCategoriesForVendor($category->id, $langId, $vid);
            foreach($childs as $child){
                array_push($vendorCategory, $child->category->id);
            }
        }

        $products = Product::with(['media.image',
                'translation' => function($q) use($langId, $keyword){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    if($keyword){
                        $q->where(function ($q1) use ($keyword) {
                            $q1->where('title', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('body_html', 'LIKE', '%' . $keyword . '%');
                        });
                    }

                },
                'variant' => function($q) use($langId){
                    $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price');
                    // $q->groupBy('product_id');
                },'variant.checkIfInCart',
                'addOn' => function ($q1) use ($langId) {
                    $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                    $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                    $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                    $q1->where('set.status', 1)->where('ast.language_id', $langId);
                },
                'addOn.setoptions' => function ($q2) use ($langId) {
                    $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                    $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                    $q2->where('apt.language_id', $langId);
                }
            ])
            ->select('id', 'sku', 'description', 'category_id', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
            if($keyword){
                $products->where(function ($q) use ($keyword, $langId) {
                    $q->where(function ($q1) use ($keyword) {
                        $q1->where('sku', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('url_slug', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('title', 'LIKE', '%' . $keyword . '%');
                    });
                    $q->orWhereHas('translation', function ($q1) use ($keyword, $langId) {
                        $q1->where(function ($q2) use ($keyword) {
                            $q2->where('title', 'LIKE', '%' . $keyword . '%');
                        });
                    });
                });                
            }
            if($tagId){
                $products->whereHas('tags',function($query) use ($tagId){
                    $query->whereIn('tag_id',$tagId);
                 });
            }
        if(count($vendorCategory) > 0){
            $products = $products->whereIn('category_id', $vendorCategory);
        }
        $products = $products->where('is_live', 1)->where('vendor_id', $vid)->get();

        $vendor_categories = collect();
        $category_list = [];
        if($products->isNotEmpty()){
            foreach($products as $k => $value) {
                foreach ($value->addOn as $key => $val) {
                    foreach ($val->setoptions as $k => $v) {
                        if($v->price == 0){
                            $v->is_free = true;
                        }else{
                            $v->is_free = false;
                        }
                        $v->multiplier = $clientCurrency->doller_compare;
                    }
                }

                $p_id = $value->id;
                $variantData = $value->with(['variantSet' => function ($z) use ($langId, $p_id) {
                    $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                    $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                    $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                    $z->where('vt.language_id', $langId);
                    $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                },'variantSet.option2'=> function ($zx) use ($langId, $p_id) {
                    $zx->where('vt.language_id', $langId)
                    ->where('product_variant_sets.product_id', $p_id);
                }])->where('id', $p_id)->first();
                $value->variantSet = $variantData->variantSet;
                $value->product_image = ($value->media->isNotEmpty()) ? $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                $value->translation_description = ($value->translation->isNotEmpty()) ? strip_tags($value->translation->first()->body_html) : '';
                $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                $value->variant_price = ($value->variant->isNotEmpty()) ? $value->variant->first()->price : 0;
                $value->variant_quantity = ($value->variant->isNotEmpty()) ? $value->variant->first()->quantity : 0;

                $cid = $value->category_id;

                if(!in_array($cid, $category_list)){
                    $category_list[] = $cid;
                    $vendor_category = VendorCategory::with(['category.translation' => function($q) use($langId){
                        $q->where('category_translations.language_id', $langId)->groupBy('category_translations.language_id');
                    }]);
                    // ->whereHas('category');
                    if(count($vendorCategory) < 1){ // if user is not coming to vendor by selecting a category
                        // $vendor_category = $vendor_category->whereHas('category', function($query) {
                        //     $query->whereIn('categories.type_id', [1]);
                        // });
                    }
                    $vendor_category = $vendor_category->where('status', 1)->where('vendor_id', $vid)->where('category_id', $cid)->first();
                    if($vendor_categories){
                        $vendorProducts = $products->where('category_id', $cid);
                        if($vendor_category){
                            $vendor_category->category->translation_title = $vendor_category->category->translation->first() ? $vendor_category->category->translation->first()->name : '';
                            $vendor_category->products = $vendorProducts;
                            $vendor_category->products_count = $vendorProducts->count();
                            $vendor_categories->push($vendor_category);
                        }
                    }
                }
            }
        }
        $tags = Tag::with('primary')->get();
        // dd($vendor_categories->toArray());

        $listData = $vendor_categories;
        $returnHTML = view('frontend.vendor-search-products')->with(['vendor'=> $vendor,'tags'=>$tags,'tag_id'=> $tagId, 'listData'=>$listData])->render();
        return response()->json(array('status'=>'Success', 'html'=>$returnHTML));
    }


    // product search for edit order
    public function vendorProductsSearchResultsForEditOrder(Request $request)
    {
        $response = [];
        // $tagId = $request->input('tag_id');
        $keyword = $request->input('keyword');
        $vid = $request->input('vendor');
        // $vCat = $request->input('vendor_category');
        // $langId = Session::get('customerLanguage');
        // $preferences = Session::get('preferences');

        // $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();

        $clientLanguage = ClientLanguage::where('is_primary', 1)->first();
        $langId = $clientLanguage ? $clientLanguage->language_id : 1;

        $vendor = Vendor::with('slot.day', 'slotDate')
            ->select('id','email', 'name', 'show_slot')->where('id', $vid)->where('status', 1)->firstOrFail();
        $vendor->is_vendor_closed = 0;
        if($vendor->show_slot == 0){
            if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                $vendor->is_vendor_closed = 1;
            }else{
                $vendor->is_vendor_closed = 0;
                if($vendor->slotDate->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                }elseif($vendor->slot->isNotEmpty()){
                    $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                    $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                }
            }
        }

        // $vendorCategory = [];
        // if($vCat != ''){
        //     $category = Category::select('id')->where('slug', $vCat)->firstOrFail();
        //     array_push($vendorCategory, $category->id);
        //     $childs = $this->getChildCategoriesForVendor($category->id, $langId, $vid);
        //     foreach($childs as $child){
        //         array_push($vendorCategory, $child->category->id);
        //     }
        // }

        $products = Product::with(['media.image',
                'translation' => function($q) use($langId, $keyword){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    if($keyword){
                        $q->where(function ($q1) use ($keyword) {
                            $q1->where('title', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('body_html', 'LIKE', '%' . $keyword . '%');
                        });
                    }
                    $q->groupBy('product_id');
                },
                'variant' => function($q) use($langId){
                    $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price');
                    // $q->groupBy('product_id');
                },
                'addOn' => function ($q1) use ($langId) {
                    $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                    $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                    $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                    $q1->where('set.status', 1)->where('ast.language_id', $langId);
                },
                'addOn.setoptions' => function ($q2) use ($langId) {
                    $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                    $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                    $q2->where('apt.language_id', $langId);
                }
            ])
            ->select('id', 'sku', 'description', 'category_id', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
            if($keyword){
                $products->where(function ($q) use ($keyword, $langId) {
                    $q->where(function ($q1) use ($keyword) {
                        $q1->where('sku', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('url_slug', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('title', 'LIKE', '%' . $keyword . '%');
                    });
                    $q->orWhereHas('translation', function ($q1) use ($keyword, $langId) {
                        $q1->where(function ($q2) use ($keyword) {
                            $q2->where('title', 'LIKE', '%' . $keyword . '%');
                        });
                    });
                });                
            }
            // if($tagId){
            //     $products->whereHas('tags',function($query) use ($tagId){
            //         $query->whereIn('tag_id',$tagId);
            //      });
            // }
        // if(count($vendorCategory) > 0){
        //     $products = $products->whereIn('category_id', $vendorCategory);
        // }
        $products = $products->where('is_live', 1)->where('vendor_id', $vid)->get();

        $vendor_categories = collect();
        $category_list = [];
        if($products->isNotEmpty()){
            foreach($products as $k => $value) {
                foreach ($value->addOn as $key => $val) {
                    foreach ($val->setoptions as $k => $v) {
                        if($v->price == 0){
                            $v->is_free = true;
                        }else{
                            $v->is_free = false;
                        }
                        // $v->multiplier = $clientCurrency->doller_compare;
                    }
                }

                $p_id = $value->id;
                $variantData = $value->with(['variantSet' => function ($z) use ($langId, $p_id) {
                    $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                    $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                    $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                    $z->where('vt.language_id', $langId);
                    $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                },'variantSet.option2'=> function ($zx) use ($langId, $p_id) {
                    $zx->where('vt.language_id', $langId)
                    ->where('product_variant_sets.product_id', $p_id);
                }])->where('id', $p_id)->first();
                $value->variantSet = $variantData->variantSet;
                $value->product_image = ($value->media->isNotEmpty()) ? $value->media->first()->image->path['image_fit'] . '300/300' . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                $value->translation_description = ($value->translation->isNotEmpty()) ? strip_tags($value->translation->first()->body_html) : '';
                // $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                $value->variant_price = ($value->variant->isNotEmpty()) ? $value->variant->first()->price : 0;
                $value->variant_quantity = ($value->variant->isNotEmpty()) ? $value->variant->first()->quantity : 0;

                $cid = $value->category_id;

                if(!in_array($cid, $category_list)){
                    $category_list[] = $cid;
                    $vendor_category = VendorCategory::with(['category.translation' => function($q) use($langId){
                        $q->where('category_translations.language_id', $langId)->groupBy('category_translations.language_id');
                    }]);
                    
                    $vendor_category = $vendor_category->where('status', 1)->where('vendor_id', $vid)->where('category_id', $cid)->first();
                    if($vendor_categories){
                        $vendorProducts = $products->where('category_id', $cid);
                        if($vendor_category){
                            $vendor_category->category->translation_title = $vendor_category->category->translation->first() ? $vendor_category->category->translation->first()->name : '';
                            $vendor_category->products = array_values($vendorProducts->toArray());
                            $vendor_category->products_count = $vendorProducts->count();
                            $vendor_categories->push($vendor_category);
                        }
                    }
                }
            }
        }
        // $tags = Tag::with('primary')->get();
        // dd($vendor_categories->toArray());

        $listData = $vendor_categories;
        $data['vendor'] = $vendor;
        $data['listData'] = $listData;

        // $returnHTML = view('frontend.vendor-search-products')->with(['vendor'=> $vendor,'tags'=>$tags,'tag_id'=> $tagId, 'listData'=>$listData])->render();
        // return response()->json(array('status'=>'Success', 'html'=>$returnHTML));
        return $this->successResponse($data, '', 200);
    }
}
