<?php

namespace App\Http\Controllers\Godpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingType;
use App\Models\Language;
use Session;
use Auth;
class ShippingTypeController extends Controller
{
    private $blocking = '2';
    private $folderName = 'ShippingType/icon/';
    private $view_path = 'godpanel.modules.ShippingType.';

#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function index()
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $country = ShippingType::orderBy('name','ASC')->paginate(20);
         
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
        return view($this->view_path.'add')
        ->with([
            'languages' => $languages,
            
         ]);
}

  
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function store(Request $request)
{

     
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      $img_path = uploadFileWithAjax23($this->folderName,$request->file('image2'));
       
      $c = new ShippingType;
      $c->name =  $request->name;
      $c->type = $request->type;
      $c->image = $img_path; 
      $c->status = 1;
      $c->save(); 
           
        return redirect()->route('godpanel.ShippingType.index')->with('success','Shipping type Added successfully!');
         
}


   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function edit($id)
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $languages = Language::where('basic_lang',1)->orderBy('id','ASC')
            ->get(); 
        $data = ShippingType::find($id); 
        return view($this->view_path.'edit')
        ->with([
            'languages' => $languages,
            'data' => $data
         ]);
}

   
   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function destroy($id)
{
        
      $Country = ShippingType::where('id',$id)->delete();
       return redirect()->route('godpanel.ShippingType.index')->with('success','Shipping type deleted successfully!');
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function update(Request $request,$id)
{

    // return $request->all();
       $Country = ShippingType::find($id);
       $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
       $img_path = $request->hasFile('image2') ? uploadFileWithAjax23($this->folderName,$request->file('image2')) : $Country->image;
     
		$c = $Country;
		$c->name =  $request->name;
		$c->type = $request->type;
		$c->image = $img_path; 
		$c->status = 1;
		$c->save(); 
    

              
           
        return redirect()->route('godpanel.ShippingType.index')->with('success','Shipping type updated successfully!');
        
}


}
