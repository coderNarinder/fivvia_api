<?php

namespace App\Http\Controllers\Tours\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Tours\Backend\BaseController;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class ProductController extends BaseController
{


public $view_path = 'backend.modules.products.';
#-----------------------------------------------------------------------------------------------------------------
# dashboard
#-----------------------------------------------------------------------------------------------------------------

public function list($value='')
{
	return view($this->view_path.'index');
}


#-----------------------------------------------------------------------------------------------------------------
# dashboard
#-----------------------------------------------------------------------------------------------------------------
 
public function ajax()
{ 
     $users = Vendor::select(['id','name','slug', 'status','desc','address','logo'])->where('client_id',$this->client_id)->get();

    return datatables()->of($users)
        ->addColumn('action', function ($t) {
            return  $this->Actions($t);
        })
        ->editColumn('status',function($t){
              $checked = $t->status == 1 ? 'checked' : '';
              $label = $t->status == 1 ? 'Active' : 'Deactive';
              $url = route('client.vendor.status',$t->slug);

            return ['id' => $t->id,'checked' => $checked,'label' => $label,'url' =>$url];
        })
         ->editColumn('logo',function($t){
              

            return $t->logo['image_path'];
        })
        ->editColumn('featured',function($t){
            return $t->featured == 1 ? 'Featured' : 'Unfeatured';
        })
        
        ->removeColumn('id')
        ->make(true);
}

#-----------------------------------------------------------------------
#    action 
#----------------------------------------------------------------------- 

public function Actions($data)
{
   $text ='<a href="'.route('client.vendor.edit',$data->slug).'" class="btn btn-danger" title="Click to edit"><span class="material-icons">edit</span></a>';
   $text .='<a href="'.route('client.vendor.view',$data->slug).'" class="btn btn-danger" title="Click to edit"><span class="material-icons">visibility</span></a>';
   return $text;
}

#---------------------------------------------------------------------------------------------
#  Create
#---------------------------------------------------------------------------------------------

public function status($slug)
{
  $location = Vendor::where('slug',$slug)->where('client_id',$this->client_id);
  if($location->count() > 0){
    
    $c = $location->first();
    $c->status = $c->status == 1 ? 0 : 1;
    $c->save();
  }

  return response()->json(['status' => 1,'message' => 'status has been changed']);
   
}

#---------------------------------------------------------------------------------------------
#  Create
#---------------------------------------------------------------------------------------------

public function edit($slug)
{
  $v = Vendor::where('slug',$slug)->where('client_id',$this->client_id);
  if($v->count() == 0){
     return redirect()->route('client.vendor.list');
  }

    return view($this->view_path.'edit')->with('vendor',$v->first());
   
}

#---------------------------------------------------------------------------------------------
#  Create
#---------------------------------------------------------------------------------------------

public function view($slug)
{
  $v = Vendor::where('slug',$slug)->where('client_id',$this->client_id);
  if($v->count() == 0){
     return redirect()->route('client.vendor.list');
  }

  $vendor = $v->first();

$category_type = getAppType();
  $categories = \App\Models\Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id','category_type')
    ->with('translation_one')
    ->where('category_type',$this->client_business_type)
    ->where(function ($q) use ($vendor) {
        $q->whereNull('vendor_id')->orWhere('vendor_id', $vendor->id);
    })->orderBy('position', 'asc')
    ->orderBy('id', 'asc')
    ->orderBy('parent_id', 'asc')
    ->get();

    $VendorCategory = \App\Models\VendorCategory::where('vendor_id', $vendor->id)->where('status', 1)->where('client_id', $this->client_id)->pluck('category_id')->toArray();

    return view($this->view_path.'show')
    ->with('vendor',$vendor)
    ->with('VendorCategory',$VendorCategory)
    ->with('categories',$categories);
   
}

#---------------------------------------------------------------------------------------------
#  Create
#---------------------------------------------------------------------------------------------

public function addProduct(Request $request,$slug)
{
 

  $v = Vendor::where('slug',$slug)->where('client_id',$this->client_id);
  if($v->count() == 0){
     return redirect()->route('client.vendor.list');
  }

   
  $vendor = $v->first();
  $category_type = getAppType();
    $VendorCategory = \App\Models\VendorCategory::where('vendor_id', $vendor->id)->where('status',1)->where('client_id', $this->client_id)->pluck('category_id')->toArray();
    $categories = \App\Models\Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id','category_type')
    ->with('translation_one')
    ->where('category_type',$this->client_business_type)
    ->where('parent_id','>',1)
    // ->whereIn('id',$VendorCategory)
    ->orderBy('position', 'asc')
    ->orderBy('id', 'asc')
    ->orderBy('parent_id', 'asc')
    ->get();

   

    return view($this->view_path.'create')
    ->with('vendor',$vendor)
    ->with('VendorCategory',$VendorCategory)
    ->with('categories',$categories);
   
}

}
