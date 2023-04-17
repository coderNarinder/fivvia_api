<?php

namespace App\Http\Controllers\ApiRoutes\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use App\Http\Controllers\ApiRoutes\M1\BaseController; 
use Illuminate\Support\Str;
use DB;
class ProductController extends BaseController
{
  

#------------------------------------------------------------------------------------------------
# get all products
#------------------------------------------------------------------------------------------------

  public function getTypeProducts(Request $request)
  {



$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to=NGN&from=USD&amount=1",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: text/plain",
    "apikey: ckqnkZo2f8HeJvac28ywN85vOC82KZ7E"
  ),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET"
));

$response = curl_exec($curl);
curl_close($curl);
 







          $lang = $request->header('lang');//Request::header('currency');
          $currency = $request->header('currency');//Request::header('currency');
      
           $skip = !empty($request->skip) ? $request->skip : 0;
          $limit = !empty($request->limit) ? $request->limit : 20;
          $u = $users = Product::with('translation_one','product_image','country')->where('is_live',1)
          ->where(function($t) use($request){
              if($request->type == "featured"){
                $t->where('is_featured',1);
              }
               if($request->type == "new"){
                $t->where('is_new',1);
              }
          })
          ->where('client_id',$this->client_id);
           // $this->WEB_CURRENCY = Request::header('CURRENCY');
            //$this->WEB_LANG = Request::header('LANG');
           return response()->json([
               'data' => [
                   'count' => $u->count(),
                   'currency' => $response,
                   'headers' => $request->header('lang'),
                   'listing' => $users->skip($skip)->limit($limit)->get()
               ]
           ]);
  }

#------------------------------------------------------------------------------------------------
# create
#------------------------------------------------------------------------------------------------

  public function createProduct(Request $request,$slug)
  {
     try{
        \DB::beginTransaction(); 
      $v =\Validator::make($request->all(),[
         'category_id' => 'required',
         'name' => 'required',
         'country_id' => 'required',
         'city_id' => 'required',
         'state_id' => 'required',
         'pincode' => 'required',
         'location' => 'required',
         'language_id' => 'required',
         'description' => 'required',
         'latitude' => 'required',
         'longitude' => 'required',
         'actual_price' => 'required',
         'price' => 'required',
         'remarks' => 'required',
         // 'pickup_latitude' => 'required',
         // 'pickup_longitude' => 'required',
         // 'pickup_pincode' => 'required',
         // 'pickup_location' => 'required',
         'tags' => 'required',
         'amenities' => 'required',
         'status' => 'required',
         'is_featured' => 'required',
         'is_new' => 'required',
         'image' => 'required',
         'pickup_address'=> 'required'
      ]);

      if($v->fails()){
         $data = ['errors'=> $v->errors(),'status'=> 2];
      }else{

           $v = Vendor::where('slug', $slug)->where('client_id',$this->client_id);
      
          $vendor = $v->first();
          $p = new Product;
          // $p->type_id = $request->type_id;
          // $p->category_id = $request->category;
          $p->vendor_id = $vendor->id; 
          $p->client_id = $this->client_id;
          $p->title = $request->name;
          $p->country_id = $request->country_id;
          $p->city_id = $request->city_id;
          $p->state_id = $request->state_id;
          $p->pincode = $request->pincode;
          $p->location = $request->location;
          $p->latitude = $request->latitude;
          $p->longitude = $request->longitude;
          $p->description = $request->description;
          $p->actual_price = $request->actual_price;
          $p->price = $request->price;
          $p->child_price = $request->child_price;
          $p->people_limit = $request->people_limit;
          $p->tour_for = $request->tour_for;
       
          $p->tags = json_encode($request->tags);
          $p->amenities = json_encode($request->amenities);
          $p->remarks = json_encode($request->remarks);
          $p->pickup_address = json_encode($request->pickup_address);
          $p->business_type_id = $request->business_type_id;
          $p->category_id = $request->category_id;
          $p->is_live = $request->status;
          $p->is_featured = !empty($request->is_featured) ? 1 : 0;
          $p->is_new = !empty($request->is_new) ? $request->is_new : 0;
          $p->url_slug = Str::slug($request->name, "-");
          $p->business_type =  $this->client_head->business_category_type;
          if(Product::where('url_slug',$vendor->url_slug)->count() > 0){
            $vendor->slug = Str::slug($request->url_slug, "-").rand(10,100);
          }
         if($p->save()){
           if(!empty($request->image)){
            foreach ($request->image as $key => $img_path) {
                $this->uploadVendorImages(1,$img_path,$p->vendor_id,$p->id);
            }
           }
            
            if(!empty($request->video)){
              foreach ($request->video as $key => $img_path) {
                $this->uploadVendorImages(2,$img_path,$p->vendor_id,$p->id);
              }
            }
             

            foreach ($request->pickup_address as $cate) {
                    $product_category = new \App\Models\ProductAddress();
                    $product_category->product_id = $p->id;
                    $product_category->address_id = $cate;
                    $product_category->client_id = $this->client_id;
                    $product_category->save();
            }

            foreach ($request->tags as $tag) {
                    $product_tag = new \App\Models\ProductTag;
                    $product_tag->product_id = $p->id;
                    $product_tag->tag_id = $tag;
                    $product_tag->client_id = $this->client_id;
                    $product_tag->save();
            }

            // foreach ($request->business_types as $business_type_id) {
            //         $product_tag = new \App\Models\ProductBusinessType;
            //         $product_tag->product_id = $p->id;
            //         $product_tag->business_type_id = $business_type_id;
            //         $product_tag->client_id = $this->client_id;
            //         $product_tag->save();
            // }

            foreach ($request->amenities as $tag) {
                    $product_tag = new \App\Models\ProductAmenity;
                    $product_tag->product_id = $p->id;
                    $product_tag->amenity_id = $tag;
                    $product_tag->client_id = $this->client_id;
                    $product_tag->save();
            }


            $trans = new \App\Models\ProductTranslation();
            $trans->product_id = $p->id;
            $trans->client_id = $this->client_id;
            $trans->language_id = $request->language_id;
            $trans->title               = $request->name;
            $trans->body_html           = $request->description;
            $trans->meta_title          = $request->meta_title;
            $trans->meta_keyword        = $request->meta_keyword;
            $trans->meta_description    = $request->meta_description;
            $trans->save();
            $data = ['message'=> 'Product saved successfully!','status'=> 1];
            \DB::commit();
         }else{
           $data = ['message'=> 'Something wrong!','status'=> 0];
         } 
      }
      
     //return redirect()->back()->with('success', 'Product deleted successfully!');
  }catch(\Exception $ex){
      DB::rollback();
      $data = ['message'=> $ex->getMessage(),'status'=> 0];
  }
     
    return response()->json($data);

  }

#------------------------------------------------------------------------------------------------
# create
#------------------------------------------------------------------------------------------------

	public function getProductUpdate(Request $request,$slug)
	{
     try{
        
      $v =\Validator::make($request->all(),[
         'category_id' => 'required',
         'name' => 'required',
         'country_id' => 'required',
         'city_id' => 'required',
         'state_id' => 'required',
         'pincode' => 'required',
         'location' => 'required',
         'language_id' => 'required',
         'description' => 'required',
         'latitude' => 'required',
         'longitude' => 'required',
         'actual_price' => 'required',
         'price' => 'required',
         'remarks' => 'required',
         'pickup_address' => 'required', 
         'tags' => 'required',
         'amenities' => 'required',
         'status' => 'required',
         'is_featured' => 'required',
         'is_new' => 'required',
         'image' => 'required',
      ]);

      if($v->fails()){
         $data = ['erros'=> $v->errors(),'status'=> 2];
      }else{

           
      
         
          $p = Product::where('url_slug',$slug)->where('client_id',$this->client_id)->first();
         
          $p->title = $request->name;
          $p->country_id = $request->country_id;
          $p->city_id = $request->city_id;
          $p->state_id = $request->state_id;
          $p->pincode = $request->pincode;
          $p->location = $request->location;
          $p->latitude = $request->latitude;
          $p->longitude = $request->longitude;
          $p->description = $request->description;
          $p->actual_price = $request->actual_price;
          $p->price = $request->price;
          $p->tags = json_encode($request->tags);
          $p->amenities = json_encode($request->amenities);
          $p->remarks = json_encode($request->remarks);
          $p->pickup_address = json_encode($request->pickup_address);
          $p->category_id = $request->category_id;
          $p->is_live = $request->status;
          $p->is_featured = !empty($request->is_featured) ? 1 : 0;
          $p->is_new = !empty($request->is_new) ? $request->is_new : 0;
          $p->business_type =  $this->client_head->business_category_type;
          $p->business_type_id = $request->business_type_id;
          $p->child_price = $request->child_price;
          $p->people_limit = $request->people_limit;
          $p->tour_for = $request->tour_for;
         if($p->save()){

           $img = \App\Models\VendorMedia::where('vendor_id',$p->vendor_id)
           ->where('product_id',$p->id)->delete();
      
           if(!empty($request->image)){
              foreach ($request->image as $key => $img_path) {
                if(!empty($img_path)){
                  $this->uploadVendorImages(1,$img_path,$p->vendor_id,$p->id);
                }
              }
           }
            
            if(!empty($request->video)){
              foreach ($request->video as $key => $img_path) {
                if(!empty($img_path)){
                $this->uploadVendorImages(2,$img_path,$p->vendor_id,$p->id);
              }
              }
            }

           // \App\Models\ProductBusinessType::where('product_id',$p->id)->delete();
           // foreach ($request->business_types as $business_type_id) {
           //          $product_tag = new \App\Models\ProductBusinessType;
           //          $product_tag->product_id = $p->id;
           //          $product_tag->business_type_id = $business_type_id;
           //          $product_tag->client_id = $this->client_id;
           //          $product_tag->save();
           //  }
            \App\Models\ProductAddress::where('product_id',$p->id)->delete();
             foreach ($request->pickup_address as $cate) {
                    $product_category = new \App\Models\ProductAddress();
                    $product_category->product_id = $p->id;
                    $product_category->address_id = $cate;
                    $product_category->client_id = $this->client_id;
                    $product_category->save();
             }
             
 
            \App\Models\ProductTag::where('product_id',$p->id)->delete();
            foreach ($request->tags as $tag) {
                 if(!empty($tag)){
                    $product_tag = new \App\Models\ProductTag;
                    $product_tag->product_id = $p->id;
                    $product_tag->tag_id = $tag;
                    $product_tag->client_id = $this->client_id;
                    $product_tag->save();
                 }
            }
             \App\Models\ProductAmenity::where('product_id',$p->id)->delete();
            foreach ($request->amenities as $tag) {
              if(!empty($tag)){
                    $product_tag = new \App\Models\ProductAmenity;
                    $product_tag->product_id = $p->id;
                    $product_tag->amenity_id = $tag;
                    $product_tag->client_id = $this->client_id;
                    $product_tag->save();
              }
            }
            $trs = \App\Models\ProductTranslation::where('language_id',$request->language_id)
            ->where('product_id',$p->id);
            $trans = $trs->count() > 0 ? $trs->first() : new \App\Models\ProductTranslation();
            $trans->product_id = $p->id;
            $trans->client_id = $this->client_id;
            $trans->language_id = $request->language_id;
            $trans->title               = $request->name;
            $trans->body_html           = $request->description;
            $trans->meta_title          = $request->meta_title;
            $trans->meta_keyword        = $request->meta_keyword;
            $trans->meta_description    = $request->meta_description;
            $trans->save();
            $data = ['message'=> 'Product updated successfully!','status'=> 1];
            \DB::commit();
         }else{
           $data = ['message'=> 'Something wrong!','status'=> 0];
         } 
      }
      
     //return redirect()->back()->with('success', 'Product deleted successfully!');
  }catch(\Exception $ex){
     
      $data = ['message'=> $ex->getMessage(),'status'=> 0];
  }
     
    return response()->json($data);

	}

#------------------------------------------------------------------------------------------------
# productDetail 
#------------------------------------------------------------------------------------------------


public function uploadVendorImages($type=1,$img_path,$vendor_id,$product_id=0)
{
      $img = new \App\Models\VendorMedia();
      $img->media_type = $type;
      $img->vendor_id = $vendor_id;
      $img->product_id = $product_id;
      $img->path = $img_path;
      $img->save();
}


#------------------------------------------------------------------------------------------------
# productDetail 
#------------------------------------------------------------------------------------------------

public function deleteProduct(Request $request)
{
        $v = Product::where('vendor_id', $request->vendor_id)->where('id',$request->product_id)->where('client_id',$this->client_id);
        if($v->count() == 1){
             $v->delete();
             \App\Models\ProductTranslation::where('product_id',$request->product_id)->delete();
             \App\Models\VendorMedia::where('product_id',$request->product_id)->delete();
             \App\Models\ProductTag::where('product_id',$request->product_id)->delete();
             \App\Models\ProductAmenity::where('product_id',$request->product_id)->delete();
          $data = ['message'=> 'Product deleted successfully!','status'=> 1];
        }else{
          $data = ['message'=> 'Something wrong!','status'=> 0];
        }

     return response()->json($data);
}

#------------------------------------------------------------------------------------------------
# productDetail 
#------------------------------------------------------------------------------------------------

public function getVendorProducts(Request $request,$slug)
{
    
         $v = Vendor::where('slug', $slug)->where('client_id',$this->client_id);
        if($v->count() == 1){
          $vendor = $v->first();
          $products = Product::with('translation_one','product_image')->where('vendor_id',$vendor->id)->where('client_id',$this->client_id)->get();
            

             // $products = Product::->select('id', 'sku','vendor_id', 'is_live', 'is_new', 'is_featured', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'Requires_last_mile', 'averageRating', 'brand_id','minimum_order_count','batch_count')
             //        ->where('vendor_id', $id)->get();
          $data = [
            'message'=> 'Vendor loaded successfully!',
            'status'=> 1,
            'data' => [
               'listing' => $products,
             ]
          ];
        }else{
          $data = ['message'=> 'Something wrong!','status'=> 0];
        }

     return response()->json($data);
}
#------------------------------------------------------------------------------------------------
# productDetail 
#------------------------------------------------------------------------------------------------

public function getProductDetails(Request $request,$slug)
{
    
   $v = Product::with('translation_one','product_images','vendor','product_videos')
                ->where('url_slug', $slug)->where('client_id',$this->client_id);
        if($v->count() == 1){
          $product = $v->first();
          
          $data = [
            'message'=> 'Product Details loaded successfully!',
            'status'=> 1,
            'data' => [
               'listing' => $product
            ]
          ];
        }else{
          $data = ['message'=> 'Something wrong!','status'=> 0];
        }

     return response()->json($data);
}

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

  
}
