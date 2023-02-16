<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class Client extends Authenticatable
{
    use Notifiable;
    protected $guard = 'client';
    protected $fillable = [
        'name', 'email', 'password', 'encpass', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain','status', 'code', 'country_id', 'timezone', 'is_deleted', 'is_blocked','sub_domain'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    /**
     * Get Clientpreference
    */

    public function getPreference()
    {
      return $this->hasOne('App\Models\ClientPreference','client_code','code');
    }

    public function timezone_data()
    {
      return $this->belongsTo('App\Models\Timezone','timezone','timezone');
    }

    public function businessCategory()
    {
      return $this->belongsTo('App\Models\BusinessCategory','business_category_id');
    }

    public function shippingOptions($value='')
    {
         $paymentOptions = \App\Models\CountryMetaData::where('country_id',$this->country_id)
                                                      ->where('type','shipping_options');
         $options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
         return $shipping_options = \App\Models\ShippingType::whereIn('id',$options)->get();
    }

    public function paymentOptions($value='')
    {
       $paymentOptions = \App\Models\CountryMetaData::where('country_id',$this->country_id)->where('type','payments');
         $options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
         return $payment_options = \App\Models\PaymentOption::whereIn('code',$options)->where('client_id',0)->get();
    }



 


public function checkoutInfo($value='')
{ 
        $client = $this;
        $ClientCheckoutInformation = $this->client_billing_details;

        $package = $client->package;
        $members = $client->members;
        $domain_rate = 0;
        $rate = $package->price + ($members * $package->extra_per_member_rate);

        

        $country = $client->country;
        $tax = $country->tax > 0 ? (($rate / 100) * $country->tax) : 0;
       
        $total = $tax + $rate;

        // domain rate + package price
        $subtotal = $rate + $domain_rate;

         $extra_members = [];
         if($client->members > 0){
            $extra_members = [
              'members' => $client->members,
              'price' => ($package->extra_per_member_rate * $client->members)
             ];
          }
                                                                         

        $package_details = [
           'title' => $package->title,
           'price' => $package->price,
           'duration' => $package->duration,
           'default_member' => $package->default_member,
           'extra_per_member_rate' => $package->extra_per_member_rate,
           'extra_members' => $extra_members
        ];

        $domain_details = [
          'domain' => $client->custom_domain,
          'price'=> 'free'
        ];
         $trial_end_date = date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))));
         
         return [
              'package' => $package,
              'rate' => $rate,
              'tax' => $tax,
              'members' => $members,
              'total' => $total,
              'country' => $country,
              'checkout' => $ClientCheckoutInformation,
          ];
}












    public function businessType()
    {
      return $this->belongsTo('App\Models\BusinessType','business_type');
    }

    public function package()
    {
      return $this->belongsTo('App\Models\SubscriptionPackages','package_id');
    }

    public function client_billing_details()
    {
      return $this->hasOne('App\Models\ClientCheckoutInformation','client_id');
    }


    /**
     * Get Clientpreference
    */
    public function preferences()
    {
      return $this->hasOne('App\Models\ClientPreference', 'client_code', 'code')->select('business_type','theme_admin', 'client_code', 'distance_unit', 'currency_id', 'dinein_check', 'takeaway_check', 'delivery_check', 'date_format', 'time_format', 'fb_login', 'twitter_login', 'google_login', 'apple_login', 'map_provider', 'app_template_id', 'is_hyperlocal', 'verify_email', 'verify_phone', 'primary_color', 'secondary_color', 'map_key', 'pharmacy_check','celebrity_check','enquire_mode','subscription_mode','site_top_header_color', 'tip_before_order', 'tip_after_order', 'off_scheduling_at_cart','delay_order','product_order_form', 'gifting', 'pickup_delivery_service_area', 'customer_support', 'customer_support_key', 'customer_support_application_id','android_app_link','ios_link','minimum_order_batch','static_delivey_fee');
    }

    public function getEncpassAttribute($value)
    {
      $value1 = $value;
      if(!empty($value)){
        $value1 = Crypt::decryptString($value);
      }
      return $value1;
    }

    public function setEncpassAttribute($value)
    {
        $this->attributes['encpass'] = Crypt::encryptString($value);
    }

    /**
     * Get Allocation Rules
    */
    public function getAllocation()
    {
      return $this->hasOne('App\Model\AllocationRule','client_id','code');
    }

    public function rules($id = ''){
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required',
            //'database_path' => 'required',
            //'database_username' => 'required|max:50',
            //'database_password' => 'required|max:50',
            'company_name' => 'required',
            'company_address' => 'required',
            'sub_domain' => 'required',
        );

        if(empty($id)){
            $rules['email'] = 'required|email|max:60|unique:clients';
            $rules['encpass'] = 'required|string|max:60|min:6';
            //$rules['database_name'] = 'required|max:60|unique:clients';
        }

        if(!empty($id)){
            $rule['email'] = 'email|max:60|unique:clients,email,'.$id;
           // $rule['database_name'] = 'max:60|unique:clients,database_name,'.$id;
        }
        return $rules;
    }

    public function getLogoAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
 
      $ex = checkImageExtension($img);
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      // $values['image_path'] = \Config::get('app.IMG_URL2').'/'.urlOfImage($img).$ex;
      $values['image_path'] = urlOfImage($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      $values['original'] = urlOfImage($img);
      $values['logo_db_value'] = $value;

      return $values;
    }


    public function getCodeAttribute($value)
    {
      if(!empty($this->attributes['id'])){
        $value = str_replace($this->attributes['id']."_",'',$value);
      }
      return $value;
    }

    public function country()
    {
      return $this->belongsTo('App\Models\Country','country_id','id');
    }
    public function getClient()
    {
      return self::latest()->first();
    }


    public function SocialMedias($value='')
    {
        return $this->hasMany(\App\Models\SocialMedia::class,'client_id');
    }

    public function getPaymentOptions($value='')
    {
        return $this->hasMany(\App\Models\PaymentOption::class,'client_id');
    }

    public function getClientCategories($value='')
    {
        return $this->hasMany(\App\Models\Fivvia\ClientCategory::class,'client_id');
    }

    public function ClientPreferencs($value='')
    {
        return $this->hasOne(\App\Models\ClientPreference::class,'client_id');
    }

}
