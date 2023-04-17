<?php

namespace App\Http\Controllers\ApiRoutes\M1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\ApiRoutes\M1\BaseController; 
use Illuminate\Support\Str;
class VenderController extends BaseController
{
  

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

  public function getVendors(Request $request)
  {
           $skip = !empty($request->skip) ? $request->skip : 0;
          $limit = !empty($request->limit) ? $request->limit : 20;
          $u = $users = Vendor::select(['id','name','slug', 'status','desc','address','logo','emp_id','country_id','city_id','state_id'])
          ->where(function($t) use($request){
             if(!empty($request->state_id)){
                $t->where('state_id',$request->state_id);
             }
             if(!empty($request->city_id)){
                $t->where('city_id',$request->city_id);
             }
             if(!empty($request->country_id)){
                $t->where('country_id',$request->country_id);
             }
             if(!empty($request->name)){
                $t->where('name','LIKE',$request->name.'%');
             }
          })->where('client_id',$this->client_id);
           $a = $b = $p = $users;
           $approved = \DB::table('vendors')->select(['id','name','slug', 'status','desc','address','logo'])
           ->where('client_id',$this->client_id)->where('status',1)->count();
           $blocked = \DB::table('vendors')->select(['id','name','slug', 'status','desc','address','logo'])
           ->where('client_id',$this->client_id)->where('status',2)->count();
           $pending = \DB::table('vendors')->select(['id','name','slug', 'status','desc','address','logo'])
           ->where('client_id',$this->client_id)->where('status',0)->count();
           $users->where('status',$request->status);
         return response()->json([
             'data' => [
                 'count' => $u->where('status',$request->status)->count(),
                 'approved_count' => $approved,
                 'blocked_count' => $blocked,
                 'pending_count' => $pending,
                 'listing' => $users->skip($skip)->limit($limit)->get(),
                 'client'=>$this->client_head
             ]
         ]);
  }

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

  public function createVendor(Request $request)
  {
      $v =\Validator::make($request->all(),[
         'address' => 'required',
         'name' => 'required',
         'email' => 'required',
         'phone_number' => 'required',
         'logo' => 'required',
         'banner' => 'required',
         'country_id' => 'required',
         'city_id' => 'required',
         'state_id' => 'required',
         'pincode' => 'required',
      ]);

        $vendor = new Vendor;
        $vendor->dine_in = 1;
        $vendor->takeaway = 1;
        $vendor->delivery = 1;
        $vendor->logo = $request->logo;
        $vendor->banner = $request->banner;
        $vendor->email = $request->email;
        $vendor->website = $request->website;
        $vendor->phone_no = $request->phone_number;
        $vendor->pincode = $request->pincode;
        $vendor->desc = $request->description;
        $vendor->name = $request->name;
        $vendor->address = $request->address;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;
        $vendor->status = 0;
        $vendor->city_id = $request->city_id;
        $vendor->state_id = $request->state_id;
        $vendor->country_id = $request->country_id;
        $vendor->client_id = $this->client_id;
        $vendor->slug = Str::slug($request->name, "-");

       
        if(Vendor::where('slug',$vendor->slug)->count() > 0){
          $vendor->slug = Str::slug($request->name, "-").rand(10,100);
        }

         if($vendor->save()){
             $emp_id = $this->businessID;
             $vendor->createID($emp_id,1);
            $data = ['message'=> 'Vendor saved successfully!','status'=> 1];
         }else{
           $data = ['message'=> 'Something wrong!','status'=> 0];
         } 
       return response()->json($data);
  }

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

	public function editVendor(Request $request,$slug)
	{
      $v =\Validator::make($request->all(),[
         'address' => 'required',
         'name' => 'required',
         'email' => 'required',
         'phone_number' => 'required',
         'logo' => 'required',
         'banner' => 'required',
         'country_id' => 'required',
         'city_id' => 'required',
         'state_id' => 'required',
         'pincode' => 'required',
      ]);

        $vendor = Vendor::where('slug',$slug)->where('client_id',$this->client_id)->first(); 
        $vendor->logo = $request->logo;
        $vendor->banner = $request->banner;
        $vendor->email = $request->email;
        $vendor->website = $request->website;
        $vendor->phone_no = $request->phone_number;
        $vendor->pincode = $request->pincode;
        $vendor->desc = $request->description;
        $vendor->name = $request->name;
        $vendor->address = $request->address;
        $vendor->latitude = $request->latitude;
        $vendor->longitude = $request->longitude;
        $vendor->city_id = $request->city_id;
        $vendor->state_id = $request->state_id;
        $vendor->country_id = $request->country_id;  
        if($vendor->save()){
             
            
          $data = ['message'=> 'Vendor updated successfully!','status'=> 1];
        }else{
         $data = ['message'=> 'Something wrong!','status'=> 0];
        } 
       return response()->json($data);
	}

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

public function vendorDetail(Request $request,$slug)
{
    
         $v = Vendor::where('slug', $slug)->where('client_id',$this->client_id);
        if($v->count() == 1){
          $vendor = $v->first();
          
          $data = [
            'message'=> 'Vendor loaded successfully!',
            'status'=> 1,
            'data' => [
               'listing' => $vendor,
               'logo'=> $vendor->logo['image_path'],
               'banner'=> $vendor->banner['image_path']
              
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

public function updateVendorCategories(Request $request,$slug)
{
    
         $v = Vendor::where('slug', $slug)->where('client_id',$this->client_id);
        if($v->count() == 1){
          $vendor = $v->first();
           
           $cates = \DB::table('vendor_categories')->where('vendor_id', $vendor->id)->get();
            foreach ($cates as $key => $cx) {
             \App\Models\VendorCategory::where('id',$cx->id)->delete();
            }
          if(!empty($request->categories)){
            foreach ($request->categories as $key => $cate) {
               $c = new \App\Models\VendorCategory;
               $c->client_id = $this->client_id;
               $c->category_id = $cate;
               $c->vendor_id = $vendor->id;
               $c->status = 1;
               $c->save();
            }
          }
          $VendorCategory = \App\Models\VendorCategory::where('vendor_id', $vendor->id)
                                                    ->where('status', 1)
                                                    ->where('client_id', $this->client_id)
                                                    ->pluck('category_id')
                                                    ->toArray();

          $domain = $this->domain;
         $c_name = $domain.'_API_VENDOR_CATEGORY_'.$slug;
         \Cache::forget($c_name);

          
          $data = [
            'message'=> 'Vendor category updated successfully!',
            'status'=> 1,
            'data' => [
               'VendorCategory' => $VendorCategory
              
            ]
          ];
        }else{
          $data = ['message'=> 'Something wrong!','status'=> 0];
        }
   // $data = ['message'=> 'Something wrong!','status'=> 0];

     return response()->json($data);
}

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------

public function vendorCategories(Request $request,$slug)
{
       $domain = $this->domain;
       $client_id = $this->client_id;
       $client_business_type = $this->client_business_type;
       $categories = \Cache::remember($domain.'_API_'.$this->client_business_type,36,function() use($client_id,$slug,$client_business_type){
                $vendor = Vendor::where('slug', $slug)->where('client_id',$client_id)->first();
                return $categories = \App\Models\Category::select('id','icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id','category_type')
                        ->with('translation_one')
                        ->where('category_type',$client_business_type)
                        ->where(function ($q) use ($vendor) {
                            $q->whereNull('vendor_id')
                            ->orWhere('vendor_id', $vendor->id);
                        })
                        ->orderBy('position', 'asc')
                        ->orderBy('id', 'asc')
                        ->orderBy('parent_id', 'asc')
                        ->get();
       });
        $VendorCategory = \App\Models\VendorCategory::whereHas('vendor',function($t) use($slug){
                                                       $t->where('slug',$slug);
                                                     })
                                                    ->where('status', 1)
                                                    ->where('client_id',$this->client_id)
                                                    ->pluck('category_id')
                                                    ->toArray();
        $data = [
          'message'=> 'Vendor Category successfully!',
          'status'=> 1,
          'data' => [
             'listing' => $categories,
             'VendorCategory'=> $VendorCategory
          ]
        ];
       

     return response()->json($data);
}

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------



public function statusVendor(Request $request)
{
    
        $v = Vendor::where('slug', $request->slug)->where('client_id',$this->client_id);
        if($v->count() == 1){
          $vendor = $v->first();
          $vendor->status = $request->status;
          $vendor->save();
          $data = ['message'=> 'Vendor status changed successfully!','status'=> 1];
        }else{
          $data = ['message'=> 'Something wrong!','status'=> 0];
        }

     return response()->json($data);
}

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------



public function deleteVendor(Request $request)
{
    
        $v = Vendor::where('id', $request->vendor_id)->where('client_id',$this->client_id);
        if($v->count() == 1){
          $vendor = $v->first();
          $vendor->status = 2;
          $vendor->save();
          $data = ['message'=> 'Vendor deleted successfully!','status'=> 1];
        }else{
          $data = ['message'=> 'Something wrong!','status'=> 0];
        }

     return response()->json($data);
}

#------------------------------------------------------------------------------------------------
# getVendors 
#------------------------------------------------------------------------------------------------



public function getVendorCategories(Request $request,$slug)
{
       $vendor = Vendor::where('slug', $slug)->where('client_id',$this->client_id)->first();
       $domain = $this->domain;
       $client_id = $this->client_id;
       $client_business_type = $this->client_business_type;
       $categories = \Cache::remember($domain.'_API_VENDOR_CATEGORY_'.$slug,36,function() use($client_id,$slug,$client_business_type,$vendor){
                $VendorCategory = \App\Models\VendorCategory::where('vendor_id', $vendor->id)
                ->where('status',1)->where('client_id', $client_id)
                ->pluck('category_id')->toArray();
                return $categories = \App\Models\Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id','category_type')
                                          ->with('translation_one')
                                          ->where('category_type',$client_business_type)
                                          ->where('parent_id','>',1)
                                          ->whereIn('id',$VendorCategory)
                                          ->orderBy('position', 'asc')
                                          ->orderBy('id', 'asc')
                                          ->orderBy('parent_id', 'asc')
                                          ->get();
       });
       $data = [
          'message'=> 'Vendor Category successfully!',
          'status'=> 1,
          'data' => [
             'listing' => $categories
          ]
        ];
     return response()->json($data);
}

}
