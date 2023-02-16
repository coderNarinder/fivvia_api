<?php

namespace App\Http\Controllers\Tours\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Tours\Backend\BaseController;
use App\Models\Vendor;
use App\Models\BusinessCategory;
class DashboardController extends BaseController
{
   

#-----------------------------------------------------------------------------------------------------------------
# dashboard
#-----------------------------------------------------------------------------------------------------------------

public function dashboard($value='')
{
	 
	return view('backend.index');
}


#-----------------------------------------------------------------------------------------------------------------
# dashboard
#-----------------------------------------------------------------------------------------------------------------

public function themeMode(Request $request)
{
	$u = \Auth::user();
	$u->themeMode =  $request->type;
	$u->save(); 
}



public function getTours($value='')
{

  
	


 

 $on_sale_product_details = vendorProducts('is_new');
         $on_sale_products = [];
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





return $on_sale_products;

}

}
