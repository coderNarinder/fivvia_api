<?php

function getAdminAndBusinessDetail($value='')
{
    # code...
}

function setCurrencyInSesion(){
		$currency_id = Session::get('customerCurrency');
		if(isset($currency_id) && !empty($currency_id)){
            $all = \App\Models\ClientCurrency::orderBy('is_primary','desc')->where('currency_id',$currency_id)->first();
            if(empty($all)){
                $primaryCurrency = \App\Models\ClientCurrency::where('is_primary','=', 1)->first();
                \Session::put('customerCurrency',$primaryCurrency->currency_id);
				\Session::put('currencySymbol',$primaryCurrency->currency->symbol);
                $currency_id = $primaryCurrency->currency_id ;
            }else{
                $currency_id = (int)$currency_id;
                \Session::put('customerCurrency',$currency_id);
            }
        }
        else{
            $primaryCurrency = \App\Models\ClientCurrency::where('is_primary','=', 1)->first();
            \Session::put('customerCurrency',$primaryCurrency->currency_id);
			\Session::put('currencySymbol',$primaryCurrency->currency->symbol);
            $currency_id = $primaryCurrency->currency_id ;
        }

		return  $currency_id;
	}




function calulateDistanceLineOfSight($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtolower($unit);

          if ($unit == "kilometer") {
            return ($miles * 1.609344);
          } else if ($unit == "nautical mile") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
    }



    function getVendorDistanceWithTime($userLat='', $userLong='', $vendor, $preferences){


        if(($preferences) && ($preferences->is_hyperlocal == 1)){
            if( (empty($userLat)) && (empty($userLong)) ){
                $userLat = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
                $userLong = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
            }

            $lat1   = $userLat;
            $long1  = $userLong;
            $lat2   = $vendor->latitude;
            $long2  = $vendor->longitude;
            if($lat1 && $long1 && $lat2 && $long2){
                $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
                $unit_abbreviation = ($distance_unit == 'mile') ? 'miles' : 'km';
                $distance_to_time_multiplier = ($preferences->distance_to_time_multiplier > 0) ? $preferences->distance_to_time_multiplier : 2;
                $distance = calulateDistanceLineOfSight($lat1, $long1, $lat2, $long2, $distance_unit);
                $vendor->lineOfSightDistance = number_format($distance, 1, '.', '') .' '. $unit_abbreviation;

                $vendor->timeofLineOfSightDistance = number_format(floatval($vendor->order_pre_time), 0, '.', '') + number_format(($distance * $distance_to_time_multiplier), 0, '.', ''); // distance is multiplied by distance time multiplier to calculate travel time
                $pretime = getEvenOddTime($vendor->timeofLineOfSightDistance);
                if($pretime >= 60){

                    $vendor->timeofLineOfSightDistance =  '~ '.$this->vendorTime($pretime) .' '. __('hour');
                }else{
                    $vendor->timeofLineOfSightDistance = $pretime . '-' . (intval($pretime) + 5).' '. __('min');
                }

            }else{
                $vendor->lineOfSightDistance = 0;
                $vendor->timeofLineOfSightDistance = 0;
            }
        }
        return $vendor;
    }


function getEvenOddTime($time) {
        return ($time % 5 === 0) ? $time : ($time - ($time % 5));
}


 /* Get vendor rating from its products rating */
 function vendorProducts($where = '')
 {
        $venderIds = getVendorIds();
    	$type = \Session::get('vendorType');
    	$langId = \Session::get('customerLanguage');
    	$currency_id = \Session::get('customerCurrency');

        $products = \App\Models\Product::with(['category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor' => function ($q) use ($type) {
                $q->where($type, 1);
            },
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')
                ->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        $pndCategories = \App\Models\Category::where('type_id', 7)->pluck('id');
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



/* Get vendor rating from its products rating */
 function vendorProductApi($where = '')
 {
        $venderIds = getVendorIds();
        $type = \Session::get('vendorType');
        $langId = \Session::get('customerLanguage');
        $currency_id = \Session::get('customerCurrency');

        $products = \App\Models\Product::with(['category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor' => function ($q) use ($type) {
                $q->where($type, 1);
            },
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')
                ->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        $pndCategories = \App\Models\Category::where('type_id', 7)->pluck('id');
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

    function getVendorIds($value='')
    {
         
    	 return getServiceAreaVendors();
    }

function categoryNav()
{
        $obj = new \App\Providers\Custom\ClientCacheClass;
        return $obj->categoryNav();

        $client_id = getWebClientID();
        $type = \Session::get('vendorType');
        $lang_id = \Session::get('customerLanguage');
        $currency_id = \Session::get('customerCurrency');
        $category_type = getAppType();
        $preferences = \Session::get('preferences');
        $primary = \App\Models\ClientLanguage::where('client_code',$client_id)
                                             ->orderBy('is_primary','desc')
                                             ->first();
        $categories = \App\Models\Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                                           ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name','categories.category_type')
                                           ->where('categories.category_type',$category_type)
                                           ->distinct('categories.id');
        $status = 2;
        if ($preferences) {
            if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                 $vendors =  getServiceAreaVendors();
                $categories = $categories->leftJoin('vendor_categories as vct', 'categories.id', 'vct.category_id')
                    ->where(function ($q1) use ($vendors, $status, $lang_id) {
                        $q1->whereIn('vct.vendor_id', $vendors)
                            ->where('vct.status', 1)
                            ->orWhere(function ($q2) {
                                $q2->whereIn('categories.type_id', [4,5,8]);
                            });
                    });
            }
        }
         $categories = $categories->where('categories.id', '>', '1')
            ->whereNotNull('categories.type_id')
            ->whereNotIn('categories.type_id', [7])
            ->where('categories.is_visible', 1)
            ->where('categories.status', '!=', $status)
            ->where('cts.language_id', $lang_id)
            ->where(function ($qrt) use($lang_id,$primary){
                $qrt->where('cts.language_id', $lang_id)
                ->orWhere('cts.language_id',$primary->language_id);
             })
            ->whereNull('categories.vendor_id')
            ->orderBy('categories.position', 'asc')
            ->orderBy('categories.parent_id', 'asc')
            ->groupBy('id')
            ->get() ;
        if ($categories) {
            return $categories = buildTree($categories->toArray());
        }
      return $categories;
        // return \App\Models\Category::whereIn('id',$cate);
    }

function buildTree($elements, $parentId = 1)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children =  buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }



function ClientCurrencies()
{
    $domain = getDomainName();
    $latitude = \Session::get('latitude');
    $longitude = \Session::get('longitude');
    $vendor_id_cache = $domain.$latitude.$longitude.'_ClientCurrencies';
    return \Cache::remember($vendor_id_cache,3600,function(){
                return \App\Models\ClientCurrency::with('currency')
                            ->where('client_id',getWebClientID())
                            ->orderBy('is_primary','desc')
                            ->get();
    });
}


function ClientPages()
{
        $domain = getDomainName();
        $latitude = \Session::get('latitude');
        $longitude = \Session::get('longitude');
        $vendor_id_cache = $domain.$latitude.$longitude.'_ClientPages';
 return \Cache::remember($vendor_id_cache,3600,function(){
                return $pages = \App\Models\Page::with([
                        'translations' => function ($q) {
                            $q->where('language_id', session()->get('customerLanguage') ?? 1);
                        },
                    ])
                    ->whereHas('translations', function ($q) {
                        $q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ?? 1]);
                    })
                    ->orderBy('order_by', 'ASC')
                    ->get();
    });
}


function ClientLanguages()
{
        $domain = getDomainName();
        $latitude = \Session::get('latitude');
        $longitude = \Session::get('longitude');
        $vendor_id_cache = $domain.$latitude.$longitude.'_ClientLanguages';
 return \Cache::remember($vendor_id_cache,3600,function(){
                return $languageList = \App\Models\ClientLanguage::with('language')
                ->where('is_active', 1)
                ->where('client_code',getWebClientID())
                ->orderBy('is_primary', 'desc')
                ->get();
    });
}




function getServiceAreaVendorApi(){
         $domain = getDomainName();
          $latitude = \Session::get('latitude');
          $longitude = \Session::get('longitude');
          $vendor_id_cache = $domain.$latitude.$longitude.'_VendorIDs';

    return \Cache::remember($vendor_id_cache,3600,function(){
                $latitude = \Session::get('latitude');
                $longitude = \Session::get('longitude');
                $vendorType = \Session::get('vendorType');
                $preferences = clientSettings();
                $serviceAreaVendors = \App\Models\Vendor::select('id','client_id')
                                                        ->where('client_id',getWebClientID());
                $vendors = [];
                if($vendorType){
                    $serviceAreaVendors = $serviceAreaVendors->where($vendorType, 1);
                }
                if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){

                    if (!empty($latitude) && !empty($longitude)) {
                        $serviceAreaVendors = $serviceAreaVendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                            $query->select('vendor_id')
                            ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                        });
                    }
                }
                $serviceAreaVendors = $serviceAreaVendors->where('status', 1)->get();
                if($serviceAreaVendors->isNotEmpty()){
                    foreach($serviceAreaVendors as $value){
                        $vendors[] = $value->id;
                    }
                }
                \Session::put('vendors', $vendors);
                return $vendors;
        });

    }



function getServiceAreaVendors(){
         $domain = getDomainName();
          $latitude = \Session::get('latitude');
         $longitude = \Session::get('longitude');
         $vendor_id_cache = $domain.$latitude.$longitude.'_VendorIDs';

    return \Cache::remember($vendor_id_cache,3600,function(){
                $latitude = \Session::get('latitude');
                $longitude = \Session::get('longitude');
                $vendorType = \Session::get('vendorType');
                $preferences = clientSettings();
                $serviceAreaVendors = \App\Models\Vendor::select('id','client_id')
                                                        ->where('client_id',getWebClientID());
                $vendors = [];
                if($vendorType){
                    $serviceAreaVendors = $serviceAreaVendors->where($vendorType, 1);
                }
                if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){

                    if (!empty($latitude) && !empty($longitude)) {
                        $serviceAreaVendors = $serviceAreaVendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                            $query->select('vendor_id')
                            ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                        });
                    }
                }
                $serviceAreaVendors = $serviceAreaVendors->where('status', 1)->get();
                if($serviceAreaVendors->isNotEmpty()){
                    foreach($serviceAreaVendors as $value){
                        $vendors[] = $value->id;
                    }
                }
                \Session::put('vendors', $vendors);
                return $vendors;
        });



    }

?>