<?php

namespace App\Http\Controllers\Godpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;
use App\Models\BusinessType;
use App\Models\Language;
use Session;
use Auth;
class BusinessTypeController extends Controller
{
     private $blocking = '2';
    private $folderName = 'BusinessType/icon/';
    private $view_path = 'godpanel.modules.businessType.';

#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function index()
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $country = BusinessType::where('parent',0)->orderBy('client_id','DESC')->paginate(20); 
        return view($this->view_path.'index')
        ->with([
            'country' => $country
         ]);
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function create()
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $languages = Language::where('basic_lang',1)->orderBy('id','ASC')->get();
        $categories = BusinessCategory::where('parent',0)->orderBy('name','ASC')->get(); 
        return view($this->view_path.'add')
        ->with([
            'languages' => $languages,
            'categories' => $categories,
            
         ]);
}

  
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function store(Request $request)
{

     
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      if(!empty($request->language_id)){
        $parent = 0;
        $business_category = $request->business_category;
         $img_path = uploadFileWithAjax23($this->folderName,$request->file('image2'));
        foreach($request->language_id as $key => $language_id){
        
           if($key == 0){
              $c = new BusinessType;
              $c->title = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->image = $img_path; 
              $c->business_category_id = $business_category; 
              $c->logistics = $request->logistics;
              $c->save();
              $parent = $c->id;
           }else{
              $c = new BusinessType;
              $c->title = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->image = $img_path;
              $c->parent = $parent;
              $c->business_category_id = $business_category;
              $c->logistics = $request->logistics;
              $c->save();
           }

        }
        return redirect()->route('godpanel.businessType.index')->with('success','Business Category Added successfully!');
      }      
}


   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function edit($id)
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $languages = Language::where('basic_lang',1)->orderBy('id','ASC')
            ->get(); 
        $data = BusinessType::find($id); 
         $categories = BusinessCategory::where('parent',0)->orderBy('name','ASC')->get();
        return view($this->view_path.'edit')
        ->with([
            'languages' => $languages,
            'data' => $data,
            'categories'=> $categories
         ]);
}

   
   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function destroy($id)
{
        
      // $Country = BusinessCategory::where('id',$id)->orWhere('parent',$id)->delete();
       return redirect()->route('godpanel.businessType.index')->with('success','Business Category deleted successfully!');
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function update(Request $request,$id)
{

    // return $request->all();
       $Country = BusinessType::find($id);
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
       $img_path = $request->hasFile('image2') ? uploadFileWithAjax23($this->folderName,$request->file('image2')) : $Country->image;
      if(!empty($request->language_id)){
        $parent = 0;
         $business_category = $request->business_category;
        foreach($request->language_id as $key => $language_id){
        
           if($key == 0){
              $c = $Country;
              $c->title = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->image = $img_path;
              $c->business_category_id = $business_category;
              $c->logistics = $request->logistics;
              $c->client_id = $request->client_id;
              $c->save();
              $parent = $c->id;
           }else{
              $country = BusinessType::where('parent',$id)->where('language_id',$language_id);
              $c = $country->count() == 1 ? $country->first() : new BusinessType;
              $c->title = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->image = $img_path;
              $c->parent = $parent;
              $c->business_category_id = $business_category;
              $c->logistics = $request->logistics;
              $c->client_id = $request->client_id;
              $c->save();
           }

        }
        return redirect()->route('godpanel.businessType.index')->with('success','Business category updated successfully!');
      }      
}
}
