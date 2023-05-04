<?php

namespace App\Http\Controllers\ApiRoutes\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackages;
use App\Models\SubscriptionPackageFeature;
use App\Models\Template;
class PackageController extends Controller

{
 
    private $folderName = 'category/icon';
    private $view_path = 'godpanel.modules.packages.';
#---------------------------------------------------------------------------------------------
# Index (Package Listing)
#---------------------------------------------------------------------------------------------

    public function index()
    {
        $packages = SubscriptionPackages::get();
        return view($this->view_path.'index',[
            'packages' => $packages
        ]);
    }


#-------------------------------------------------------------------------------------------------
# BUSINESS TYPES
#-------------------------------------------------------------------------------------------------

    public function list(Request $request){

          $skip = !empty($request->skip) ? $request->skip : 0;
          $limit = !empty($request->limit) ? $request->limit : 20;
          $types = !empty($request->types) ? [$request->types] : ['monthly','yearly'];
          $business_types = $cate = SubscriptionPackages::with(['features' => function($t){
            $t->where('has_feature','yes');
          }])->where(function($t) use($types) {
               $t->whereIn('duration',$types);
          });
          $response = [
            'status' => 1,
            'count' => $cate->count(),
            'message' => 'business types loaded successfully',
            'data' => [
               'listing' => $business_types->skip($skip)->limit($limit)->get()
            ]
          ];
          return response()->json($response);
    }


#---------------------------------------------------------------------------------------------
# Index (Package Listing)
#---------------------------------------------------------------------------------------------

    public function getFeatures()
    {
         savetemplates();
         $template_records = [];
           
         // foreach (getTemplates() as $temp_id => $v) {
         //      $ar = ['temp_id' => $temp_id,'image'=> $v['image'], 'tagline'=> $v['tagline'], 'title'=> $v['title']];
         //      array_push($template_records, $ar);
         // }


          $response = [
            'status' => 1,
            'message' => 'Packages loaded successfully',
            'data' => [
               'listing' => getPackageFeatures(),
               'template_records' =>  Template::where('for',1)->get()
            ]
          ];
          return response()->json($response);
    }


#---------------------------------------------------------------------------------------------
# Index (Package Listing)
#---------------------------------------------------------------------------------------------

    public function edit(request $request,$id)
    {
          $package = SubscriptionPackages::with('features')->where('id',$id)->first(); 
          $templates = !empty($package->templates) ? json_decode($package->templates) : [];

          $response = [
            'status' => 1,
            'message' => 'Packages loaded successfully',
            'data' => [
               'package' => $package,
               'listing' => SubscriptionPackageFeature::where('package_id',$id)->get(),
               'templates' => $templates,
               'template_records' =>  Template::where('for',1)->get()
            ]
          ];
          return response()->json($response);
    }


#---------------------------------------------------------------------------------------------
# Store 
#---------------------------------------------------------------------------------------------

    public function store(Request $request)
    {
        $v = \Validator::make($request->all(),[
                'type' => 'required',
                'duration' => 'required',
                'title' => 'required',
                'tagline' => 'required',
                'price' => 'required|numeric',
                'actual_price' => 'required|numeric',
                'default_member' => 'required|numeric',
                'extra_per_member_rate' => 'required|numeric',
         ]);

         if($v->fails()){
               $response = [ 'status' =>0, 'message' => 'Please fill all the fields'];
         }else{
               $package = SubscriptionPackages::where('type',$request->type)
                                        ->where('duration',$request->duration)
                                        ->count();
               if($package > 0){
                   $response = [ 'status' =>0, 'message' => 'Already Existed!'];
               }else{
                  $p = new SubscriptionPackages;
                  $p->type = $request->type;
                  $p->duration = $request->duration;
                  $p->actual_price = $request->actual_price;
                  $p->title = $request->title;
                  $p->tagline = $request->tagline;
                  $p->price = $request->price;
                  $p->description = $request->description;
                  $p->default_member = $request->default_member;
                  $p->extra_per_member_rate = $request->extra_per_member_rate;
                  $p->templates = !empty($request->templates) ? json_encode($request->templates) : json_encode([]);
                  $p->save();
                  $this->addfeatures($request,$p->id);
                  $response = [ 'status' => 1, 'message' => 'Package saved successfully'];
               }
         }
         return response()->json($response); 
    }

#---------------------------------------------------------------------------------------------
# Store 
#---------------------------------------------------------------------------------------------

    public function update(Request $request,$id)
    {
     
         $v = \Validator::make($request->all(),[
                'type' => 'required',
                'duration' => 'required',
                'title' => 'required',
                'tagline' => 'required',
                'price' => 'required|numeric',
                'actual_price' => 'required|numeric',
                'default_member' => 'required|numeric',
                'extra_per_member_rate' => 'required|numeric',
         ]);

         if($v->fails()){
               $response = [ 'status' =>0, 'message' => 'Please fill all the fields'];
         }else{
               $package = SubscriptionPackages::where('type',$request->type)
                                        ->where('duration',$request->duration)
                                        ->where('id','!=',$id)
                                        ->count();
               if($package > 0){
                   $response = [ 'status' =>0, 'message' => 'Already Existed!'];
               }else{
                  $p = SubscriptionPackages::find($id);
                  $p->type = $request->type;
                  $p->duration = $request->duration;
                  $p->actual_price = $request->actual_price;
                  $p->title = $request->title;
                  $p->tagline = $request->tagline;
                  $p->price = $request->price;
                  $p->description = $request->description;
                  $p->default_member = $request->default_member;
                  $p->extra_per_member_rate = $request->extra_per_member_rate;
                  $p->templates = !empty($request->templates) ? json_encode($request->templates) : json_encode([]);
                  $p->save();
                  $this->addfeatures($request,$p->id);

                  // foreach($request->features as $key => $feature){ 
                  //       $f = SubscriptionPackageFeature::where('package_id',$p->id)
                  //                                      ->where('type',$feature->type);
                  //       $features = $f->count() > 0 ? $f->first() : new SubscriptionPackageFeature;
                  //       $features->type = $feature->type;
                  //       $features->package_id = $p->id;
                  //       $features->duration = !empty($feature->duration_key) ? $feature->duration_key : '';
                  //       $features->duration_value = !empty($feature->duration_value) ? $feature->duration_value : '';
                  //       $features->has_feature = !empty($feature->has_feature) ? $feature->has_feature : '';
                  //       $features->save();
                  // }
                  $response = [ 'status' => 1, 'message' => 'Package updated successfully'];
               }
         }
         return response()->json($response);
         
    }

public function addfeatures($request,$package_id)
{
  $getPackageFeatures =[];
  $feature_array = (object)$request->features;
  foreach($feature_array as $key => $feature){
    array_push($getPackageFeatures, $feature['title']);
    $f = SubscriptionPackageFeature::where('package_id',$package_id)
                                   ->where('type',$feature['title']);
    $features = $f->count() > 0 ? $f->first() : new SubscriptionPackageFeature;
    $features->type = $feature['title'];
    $features->package_id = $package_id;
    $features->duration = !empty($feature['duration_key']) ? $feature['duration_key'] : '';
    $features->duration_value = !empty($feature['duration_value']) ? $feature['duration_value'] : '';
    $features->has_feature = !empty($feature['has_feature']) ? $feature['has_feature'] : '';
    $features->save();
  }
  SubscriptionPackageFeature::where('package_id',$package_id)->whereNotIn('type',$getPackageFeatures)->delete();
}

}
