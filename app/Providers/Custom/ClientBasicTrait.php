<?php
namespace App\Providers\Custom;

trait ClientBasicTrait{

	#---------------------------------------------------------------------------------------------------------------
	# Client Currencies Cache
	#---------------------------------------------------------------------------------------------------------------
	function getPages()
	{
	    $domain = getDomainName();
	    $latitude = \Session::has('latitude') ? \Session::get('latitude') : '_';
	    $longitude = \Session::has('longitude') ? \Session::get('longitude') : '_';
	    $customerLanguage = \Session::has('customerLanguage') ? \Session::get('customerLanguage') : 1;
	    $vendor_id_cache = $domain.$latitude.$longitude.'_customPagess'; 
	    $client_id = $this->client_id;
	      // \Cache::forget($vendor_id_cache);
	    return \Cache::remember($vendor_id_cache,360000,function() use($customerLanguage,$client_id){
                 return $pages = \App\Models\Page::with(['translations' => function($q) use($customerLanguage){
        	       $q->where('language_id', $customerLanguage);
        	     }])
				 ->whereHas('translations', function($q) use($customerLanguage){
					 $q->where([
					 	'is_published' => 1,
					 	'language_id' => $customerLanguage
					 ]);
				 })
				 ->orderBy('order_by','ASC')
				 ->get();
	    });
	}



	#---------------------------------------------------------------------------------------------------------------
	# Client Currencies Cache
	#---------------------------------------------------------------------------------------------------------------

	#---------------------------------------------------------------------------------------------------------------
	# Client Currencies Cache
	#---------------------------------------------------------------------------------------------------------------
	function ClientCurrencies()
	{
	    $domain = getDomainName();
	    $latitude = \Session::has('latitude') ? \Session::get('latitude') : '_';
	    $longitude = \Session::has('longitude') ? \Session::get('longitude') : '_';
	    $vendor_id_cache = $domain.$latitude.$longitude.'_ClientCurrencies';
	    return \Cache::remember($vendor_id_cache,360000,function(){
	                return \App\Models\ClientCurrency::with('currency')
	                            ->where('client_id',$this->client_id)
	                            ->orderBy('is_primary','desc')
	                            ->get();
	    });
	}



	#---------------------------------------------------------------------------------------------------------------
	# Client Currencies Cache
	#---------------------------------------------------------------------------------------------------------------
	function ClientLanguage()
	{
	    $domain = $this->_domain;
	    $latitude = \Session::has('latitude') ? \Session::get('latitude') : '_';
	    $longitude = \Session::has('longitude') ? \Session::get('longitude') : '_';
	    $vendor_id_cache = $domain.'_ClientLangs';
	     if(!\Session::has('customerLanguage')){
                         \Cache::forget($vendor_id_cache);
         }
	    return \Cache::remember($vendor_id_cache,360000,function(){
	                  $laguages = \App\Models\ClientLanguage::with('language')
	                            ->where('client_code',$this->client_id)
	                            ->orderBy('is_primary','desc');
                    if( $laguages->count() > 0){
                         $lang = \App\Models\ClientLanguage::with('language')
	                            ->where('client_code',$this->client_id)
	                            ->orderBy('is_primary','desc')->first();
                         if(!\Session::has('customerLanguage')){
                          \Session::put('customerLanguage',$lang->language_id);
                     	 }

                          return $laguages->get();
                     }else{
                     	$l = new  \App\Models\ClientLanguage;
                     	$l->client_code = $this->client_id;
                     	$l->language_id = 1;
                     	$l->is_primary = 1;
                     	$l->save();
                     	\Session::put('customerLanguage',1);
                     	return $l;
                    }


	    });
	}

}




?>