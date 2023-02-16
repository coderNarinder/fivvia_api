<?php

namespace App\Http\Controllers\Api\Fivvia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;
use App\Models\Vendor;
use App\Models\Client;
class FilterDataController extends Controller
{



public function getBusinessCategory($value='')
{
	 $data = BusinessCategory::where('status',1)->get();

	 $records = $data->map(function($d){
          $d->image = url('/').$d->image;
          $d->link = route('api.getProducts',$d->id);
          return $d;
	 });

	 return response()->json(['data'=>$records],200);
}



public function getProducts($BusinessCategory_id)
{
        $client_ids = Client::where('business_category_id',$BusinessCategory_id)->pluck('id')->toArray();
        $venderIds =  Vendor::whereIn('client_id',$client_ids)->where('status',1)->pluck('id')->toArray();
        $type = \Session::get('vendorType');
        $langId =1;// \Session::get('customerLanguage');
        $currency_id = 1;//\Session::get('customerCurrency');

        return $products = \App\Models\Product::all();

        $products = \App\Models\Product::with(['category.categoryDetail.translation' => function ($q) use ($langId) {
                //$q->where('category_translations.language_id', $langId);
            },
            'vendor' => function ($q) use ($type) {
              //  $q->where($type, 1);
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
       
       
        // if (is_array($venderIds)) {
        //     $products = $products->whereIn('vendor_id', $venderIds);
        // }
        
        $products = $products
        // ->where('is_live', 1)
        ->take(10)->inRandomOrder()->get();
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
}


}
