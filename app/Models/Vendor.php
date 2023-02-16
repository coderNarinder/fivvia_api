<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class Vendor extends Model{
  //use Searchable;
    protected $fillable = ['name','slug','desc','logo','banner','address','email','website','phone_no','latitude','longitude','order_min_amount','order_pre_time','auto_reject_time','commission_percent','commission_fixed_per_order','commission_monthly','dine_in','takeaway','delivery','status','add_category','setting','show_slot','vendor_templete_id','auto_accept_order', 'service_fee_percent','order_amount_for_delivery_fee','delivery_fee_minimum','delivery_fee_maximum','slot_minutes','closed_store_order_scheduled','pincode'];

    public function serviceArea(){
       return $this->hasMany('App\Models\ServiceArea')->select('vendor_id', 'geo_array', 'name');
    }

    public function products(){
      return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
    }

    public function slot(){
      $client = Client::first();
      $mytime = Carbon::now()->setTimezone($client->timezone_data->timezone);
      $current_time = $mytime->toTimeString();
      return $this->hasMany('App\Models\VendorSlot', 'vendor_id', 'id')->has('day')->where('start_time', '<', $current_time)->where('end_time', '>', $current_time);
    }

    public function slots(){
      return $this->hasMany('App\Models\VendorSlot', 'vendor_id', 'id');
    }
    public function slotDates(){
      return $this->hasMany('App\Models\VendorSlotDate', 'vendor_id', 'id');
    }
    public function dineinCategories(){
      return $this->hasMany('App\Models\VendorDineinCategory', 'vendor_id', 'id');
    }

    public function slotDate(){
      $client = Client::first();
      $mytime = Carbon::now()->setTimezone($client->timezone_data->timezone);
      $current_date = $mytime->toDateString();
      $current_time = $mytime->toTimeString();
      return $this->hasMany('App\Models\VendorSlotDate', 'vendor_id', 'id')->where('specific_date', '=', $current_date)->where('start_time', '<', $current_time)->where('end_time', '>', $current_time);
    }

    public function avgRating(){
      return $this->hasMany('App\Models\Product', 'vendor_id', 'id')->avg('averageRating');
    }

    public function getLogoAttribute($value){
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $ex = checkImageExtension($img);
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      if (substr($img, 0, 7) == "http://" || substr($img, 0, 8) == "https://"){
        $values['image_path'] =urlOfImage($img);//\Config::get('app.IMG_URL2').'/'.$img;
      } else {
        // $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).$ex;
        $values['image_path'] = urlOfImage($img);//\Storage::disk('s3')->url($img);
      }
        $values['image_path'] =urlOfImage($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      return $values;
    }

    public function getBannerAttribute($value){
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $ex = checkImageExtension($img);
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      if (substr($img, 0, 7) == "http://" || substr($img, 0, 8) == "https://"){
        $values['image_path'] = urlOfImage($img);//\Config::get('app.IMG_URL2').'/'.$img;
      } else {
        // $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).$ex;
        $values['image_path'] = urlOfImage($img);//\Storage::disk('s3')->url($img);
      }
      $values['image_fit'] = \Config::get('app.FIT_URl');
      return $values;
    }
    public static function getNameById($vendor_id){
      $result = Vendor::where('id', $vendor_id)->first();
      return $result->name;
    }

    public function orders(){
       return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id');
    }

    public function activeOrders(){
       return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id')->select('id', 'vendor_id')
              ->where('status', '!=', 3);
    }

    public function permissionToUser(){
      return $this->hasMany('App\Models\UserVendor');
    }


    public function product(){
      return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
    }

    public function currentlyWorkingOrders(){
      return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id')->select('id', 'vendor_id')
             ->whereIn('order_status_option_id',[2,4,5]);
   }


  public function getAllCategory(){
    return $this->hasMany('App\Models\VendorCategory');
  }

  public function getById($id){
    return self::where('id',$id)->first();
  }

 

  public function getRating($value='')
  {
        $vendor_rating = 0;
        if(!empty($product_rating = $this->product->where('averageRating','>',0)->sum('averageRating'))){
            $product_count = $this->product->where('averageRating','>',0)->count();
            
            if($product_count > 0){
                $vendor_rating = $product_rating / $product_count;
            }
         }
        return number_format($vendor_rating, 1, '.', '');
  }

  public function vendorCates($value='')
  {
              $vendorCategories = \App\Models\VendorCategory::with('category.translation_one')
              ->where('vendor_id', $this->id)
              ->where('status', 1)
              ->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
        return $categoriesList;
  }


  function getVendorDistanceWithTime($userLat='', $userLong=''){

        $preferences = clientSettings();
        $vendor = $this;
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


                $lineOfSightDistance = number_format($distance, 1, '.', '') .' '. $unit_abbreviation;

                $timeofLineOfSightDistance = number_format(floatval($vendor->order_pre_time), 0, '.', '') + number_format(($distance * $distance_to_time_multiplier), 0, '.', ''); // distance is multiplied by distance time multiplier to calculate travel time
                $pretime = getEvenOddTime($vendor->timeofLineOfSightDistance);
                if($pretime >= 60){
                    $timeofLineOfSightDistance =  '~ '.$this->vendorTime($pretime) .' '. __('hour');
                }else{
                    $timeofLineOfSightDistance = $pretime . '-' . (intval($pretime) + 5).' '. __('min');
                }

            }else{
                $lineOfSightDistance = 0;
                $timeofLineOfSightDistance = 0;
            }
        }else{
                $lineOfSightDistance = 0;
                $timeofLineOfSightDistance = 0;
        }

        return [
            'lineOfSightDistance' => $lineOfSightDistance,
            'timeofLineOfSightDistance' => $timeofLineOfSightDistance
        ];
 }


 

}
