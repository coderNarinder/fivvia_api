<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
class CartProduct extends Model{

    use HasFactory;

     

 public static function boot()
{
    parent::boot();

    static::saving(function ($model) {
        $model->client_id =getWebClientID();
    });

 
}

    protected $fillable = ['cart_id','product_id', 'vendor_id', 'vendor_dinein_table_id', 'quantity', 'status', 'variant_id', 'is_tax_applied', 'tax_rate_id', 'currency_id', 'tax_category_id', 'luxury_option_id','schedule_type','scheduled_date_time'];

    protected $touches = ['cart'];

    public function cart(){
        return $this->belongsTo('App\Models\Cart');
    }

    public function addon(){
       return $this->hasMany('App\Models\CartAddon', 'cart_product_id', 'id')->select('cart_product_id', 'addon_id', 'option_id');
    }


 	public function product(){
    	return $this->belongsTo('App\Models\Product')->select('id','title', 'sku', 'url_slug', 'is_live', 'weight', 'weight_unit', 'averageRating', 'brand_id', 'tax_category_id','Requires_last_mile','pharmacy_check','tags','mode_of_service','delay_order_hrs','delay_order_min','delay_order_hrs_for_dine_in','delay_order_min_for_dine_in','delay_order_hrs_for_takeway','delay_order_min_for_takeway','pickup_delay_order_hrs','pickup_delay_order_min','dropoff_delay_order_hrs','dropoff_delay_order_min','id as delay_hrs_min','id as pickup_delay_hrs_min','id as dropoff_delay_hrs_min','id as delay_order_time','sell_when_out_of_stock','minimum_order_count','batch_count','has_inventory');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->select('id', 'name', 'desc', 'logo', 'banner', 'latitude', 'longitude', 'order_pre_time', 'auto_reject_time', 'order_min_amount', 'show_slot', 'dine_in', 'delivery', 'takeaway', 'service_fee_percent','address','order_amount_for_delivery_fee','delivery_fee_minimum','delivery_fee_maximum','closed_store_order_scheduled');
    }

    public function slotCounts(){
        return $this->hasMany('App\Models\VendorSlot', 'vendor_id', 'id');
     }

    public function pvariant(){
    	return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'tax_category_id', 'barcode');
    }

    public function coupon(){
      return $this->hasOne('App\Models\CartCoupon', 'vendor_id', 'vendor_id')->select("cart_id", "coupon_id", 'vendor_id');
    }

    public function currency(){
      return $this->hasOne('App\Models\Currency', 'vendor_id', 'vendor_id');
    }

    public function vendorProducts(){
      return $this->hasMany(CartProduct::class, 'vendor_id', 'vendor_id')->leftjoin('client_currencies as cc', 'cc.currency_id', 'cart_products.currency_id')
      ->select('cart_products.id', 'cart_products.cart_id', 'cart_products.product_id', 'cart_products.quantity', 'cart_products.variant_id', 'cart_products.is_tax_applied', 'cart_products.tax_category_id', 'cart_products.currency_id', 'cc.doller_compare', 'cart_products.vendor_id', 'cart_products.scheduled_date_time')
     
      ->orderBy('cart_products.created_at', 'asc')
      ->orderBy('cart_products.vendor_id', 'asc');
    }


    public function getAddOnSetAndOptionAttribute()
    {
       $cart_product_id = $this->attributes['id'];
       $langId = Session::has('customerLanguage') ? Session::get('customerLanguage') : 1;
        $cart_addons = \App\Models\CartAddon::where('cart_product_id',$cart_product_id)->pluck('addon_id');
        $addoset = [];
        if($cart_addons){
          $cart_options = \App\Models\CartAddon::where('cart_product_id',$cart_product_id)->pluck('option_id');
          $addoset =  \App\Models\AddonSet::with(['translation' => function ($qry) use ($langId) {
                $qry->where('addon_set_translations.language_id', $langId);
            }])->whereIn('id',$cart_addons)->get();
          foreach($addoset as $key => $setsection)
          {
              $add_options = \App\Models\AddonOption::with(['translation' => function ($qry) use ($langId) {
                    $qry->where('addon_option_translations.language_id', $langId);
                }])->where('addon_id',$setsection->id)->whereIn('id',$cart_options)->get();
              $setsection->options = $add_options;
          }
        }
        return $addoset;
    }
}
