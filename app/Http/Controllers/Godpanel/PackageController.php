<?php

namespace App\Http\Controllers\Godpanel;

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

#---------------------------------------------------------------------------------------------
# Index (Package Listing)
#---------------------------------------------------------------------------------------------

    public function create()
    {
      $template_records = Template::where('for',1)->get();
        return view($this->view_path.'create',[
            'template_records' => $template_records
        ]);
    }


#---------------------------------------------------------------------------------------------
# Index (Package Listing)
#---------------------------------------------------------------------------------------------

    public function edit($id)
    {
        $package = SubscriptionPackages::find($id);
        $templates = !empty($package->templates) ? json_decode($package->templates) : [];
          $template_records = Template::where('for',1)->get();
        return view($this->view_path.'edit',[
          'template_records' => $template_records,
          'package' => $package,
          'templates' => $templates
        ]);
    }


#---------------------------------------------------------------------------------------------
# Store 
#---------------------------------------------------------------------------------------------

    public function store(Request $request)
    {
         $this->validate($request,[
                'type' => 'required',
                'duration' => 'required',
                'title' => 'required',
                'tagline' => 'required',
                'price' => 'required|numeric',
                'actual_price' => 'required|numeric',
                'default_member' => 'required|numeric',
                'extra_per_member_rate' => 'required|numeric',
         ]);

         $package = SubscriptionPackages::where('type',$request->type)
                                        ->where('duration',$request->duration)
                                        ->count();
         if($package > 0){
            return redirect()->route('godpanel.packages.create')->with('error_delete','Already Existed');
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
            return redirect()
                  ->route('godpanel.packages.index')
                  ->with('error_delete','Package saved successfully!');
         }

    }

#---------------------------------------------------------------------------------------------
# Store 
#---------------------------------------------------------------------------------------------

    public function update(Request $request,$id)
    {
         $this->validate($request,[
                'type' => 'required',
                'duration' => 'required',
                'title' => 'required',
                'tagline' => 'required',
                'price' => 'required|numeric',
                'actual_price' => 'required|numeric',
                'default_member' => 'required|numeric',
                'extra_per_member_rate' => 'required|numeric',
         ]);

         $package = SubscriptionPackages::where('type',$request->type)
                                        ->where('duration',$request->duration)
                                        ->where('id','!=',$id)
                                        ->count();
         if($package > 0){
            return redirect()->route('godpanel.packages.create')->with('error_delete','Already Existed');
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
            return redirect()
                  ->route('godpanel.packages.index')
                  ->with('error_delete','Package updated successfully!');
         }

    }

public function addfeatures($request,$package_id)
{
  foreach(getPackageFeatures() as $key => $feature){
    $f = SubscriptionPackageFeature::where('package_id',$package_id)
                                   ->where('type',$feature);
    $features = $f->count() > 0 ? $f->first() : new SubscriptionPackageFeature;
    $features->type = $feature;
    $features->package_id = $package_id;
    $features->duration = !empty($request->duration_key[$key]) ? $request->duration_key[$key] : '';
    $features->duration_value = !empty($request->duration_value[$key]) ? $request->duration_value[$key] : '';
    $features->has_feature = !empty($request->has_feature[$key]) ? $request->has_feature[$key] : '';
    $features->save();
  }
  SubscriptionPackageFeature::where('package_id',$package_id)->whereNotIn('type',getPackageFeatures())->delete();
}

}
