<?php

namespace App\Http\Livewire\Template\Home;

use Livewire\Component;
use Session;
use App\Models\CabBookingLayoutTranslation;
use App\Models\VendorCategory;
use App\Models\Vendor;
class VendorList extends Component
{
	public $vendor_ids = [];
    public $latitude = '';
    public $longitude = '';
    public $initData = true;
    public $currency_id = 0;
    public $client_id = 0;
    public $type = 'delivery';
    public $language_id = 0;
    public $vendors_title = 'Vendors';
    private $preferences = [];
    public $vendor_list = [];

    public function mount()
    {
    	$this->latitude = Session::get('latitude');
    	$this->longitude = Session::get('longitude');
    	$this->language_id = $language_id = Session::get('customerLanguage');
    	$this->vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)  
    	                  ->whereHas('layout',function($q){
    	                  	$q->where('slug','vendors');
    	                  })
    	                  ->value('title');
        $this->preferences = clientSettings();   
        $this->client_id = getWebClientID();    
                 
    }

    public function updated()
    {
    	$this->latitude = Session::get('latitude');
    	$this->longitude = Session::get('longitude');
    	$this->currency_id = setCurrencyInSesion();
    	$this->vendor_list = $this->getVendors();
    }

    public function render()
    {
    	$this->vendor_list = $this->getVendors();
       return view('livewire.template.home.vendor-list',[
            'preferences' => $this->preferences
       ]);
    }

    public function initLoad()
    {
    	 $this->initData = true;
    }


    public function getVendors()
    {
    	if($this->initData){
        $preferences = $this->preferences;
        $type = $this->type;
        $client_id = $this->client_id;

          // $this->escorts = \Cache::remember('get_home_page_fresh_escorts',3600,function() use($q,$type,$preferences,$client_id){
          
          // });
       
        $vendors = Vendor::with('products')
                         ->with('slot.day', 'slotDate')
                         ->select('id', 
                         	'name', 
                         	'banner', 
                         	'address', 
                         	'order_pre_time', 
                         	'order_min_amount', 
                         	'logo', 
                         	'slug', 
                         	'latitude', 
                         	'longitude',
                         	'show_slot')
					        ->where('client_id',$client_id)
					        ->where($type, 1);
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

        $data = $vendors;

         $domain = getDomainName();
	     $latitude = \Session::get('latitude');
    	 $longitude = \Session::get('longitude');
    	 $vendor_id_cache = $domain.$latitude.$longitude.'_VendorIDs';
    	 $vendor_obj = \Cache::remember($vendor_id_cache,3600,function() use($data){
             return $data->where('status', 1)->pluck('id')->toArray();
         });
       
         Session::put('vendorType', $this->type);
       return $vendors = $vendors->where('status', 1)->inRandomOrder()->get();
         
    }else{
       
        $vendors = [];
    }

    return $vendors;
   }
}
