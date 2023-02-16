<?php

namespace App\Http\Controllers\Godpanel;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, Language, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, Brand, CategoryHistory, Type, CategoryTag, Vendor, DispatcherWarningPage, DispatcherTemplateTypeOption, Product,CategoryTranslation,Country,ShippingType,PaymentOption,CountryMetaData};
use GuzzleHttp\Client as GCLIENT;
class CountryController extends Controller
{

    private $blocking = '2';
    private $folderName = 'country/icon';
    private $view_path = 'godpanel.modules.country.';

#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function index()
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $country = Country::where('parent',0)->orderBy('name','ASC')->paginate(20);
         
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
      $languages = Language::where('basic_lang',1)->orderBy('id','ASC')
            ->get(); 
        return view($this->view_path.'add')
        ->with([
            'languages' => $languages
         ]);
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function store(Request $request)
{

    // return $request->all();
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      if(!empty($request->language_id)){
        $parent = 0;
        foreach($request->language_id as $key => $language_id){
        
           if($key == 0){
              $c = new Country;
              $c->code = !empty($request->codes[$key]) ? $request->codes[$key] : 0;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->nicename = !empty($request->nicenames[$key]) ? $request->nicenames[$key] : 0;
              $c->iso3 = !empty($request->isos[$key]) ? $request->isos[$key] : 0;
              $c->numcode = $request->numcode;
              $c->phonecode = $request->phonecode;
              $c->latitude = $request->latitude;
              $c->longitude = $request->longitude;
              $c->tax = $request->tax;
              $c->language_id = $language_id;
              $c->save();
              $parent = $c->id;
           }else{
              $c = new Country;
              $c->code = !empty($request->codes[$key]) ? $request->codes[$key] : 0;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->nicename = !empty($request->nicenames[$key]) ? $request->nicenames[$key] : 0;
              $c->iso3 = !empty($request->isos[$key]) ? $request->isos[$key] : 0;
              $c->parent = $parent;
              $c->language_id = $language_id;
              $c->save();
           }

        }
        return redirect()->route('godpanel.country.index')->with('success','Country Added successfully!');
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
            $Country = Country::find($id);
        return view($this->view_path.'edit')
        ->with([
            'languages' => $languages,
            'data' => $Country,
         ]);
}
  
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function settingsSaving(Request $request,$id)
{
   
    $types =[];
         $arr = ['_token'];
      foreach ($request->all() as $type => $values) {
         if(!in_array($type, $arr)){
              $meta = CountryMetaData::where('type',$type)->where('country_id',$id);
              $c = $meta->count() > 0 ? $meta->first() : new CountryMetaData;
              $c->type = $type;
              $c->country_id = $id;
              $c->meta_values = json_encode($values);
              $c->save();
              array_push($types, $type);
          }
      }   

      CountryMetaData::whereNotIn('type',$types)->where('country_id',$id)->delete();
    return redirect()
    ->route('godpanel.country.index')
    ->with('success','Country Settings Added successfully!');
}

   
   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function settings($id)
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      $languages = Language::orderBy('id','ASC')->get(); 
      $shipping_types = ShippingType::orderBy('name','ASC')->get(); 
      $payment_options = PaymentOption::where('client_id',0)->orderBy('title','ASC')->get(); 
            $Country = Country::find($id);
        
          $payment_data = CountryMetaData::where('type','payments')->where('country_id',$id);
          $shipping_data = CountryMetaData::where('type','shipping_options')->where('country_id',$id);
          $language_data = CountryMetaData::where('type','languages')->where('country_id',$id);
          

       $payment_arr = $payment_data->count() > 0 ? json_decode($payment_data->first()->meta_values) : [];
       $shipping_arr = $shipping_data->count() > 0 ? json_decode($shipping_data->first()
        ->meta_values) : [];
       $language_arr = $language_data->count() > 0 ? json_decode($language_data->first()->meta_values) : [];


        return view($this->view_path.'settings')
        ->with([
            'languages' => $languages,
            'data' => $Country,
            'shipping_types' => $shipping_types,
            'payment_options' => $payment_options,
            'payment_arr' => $payment_arr,
            'shipping_arr' => $shipping_arr,
            'language_arr' => $language_arr,
         ]);
}

   
   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function destroy($id)
{
        
      $Country = Country::where('id',$id)->orWhere('parent',$id)->delete();
       return redirect()->route('godpanel.country.index')->with('success','Country deleted successfully!');
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function update(Request $request,$id)
{

    // return $request->all();
       $Country = Country::find($id);
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      if(!empty($request->language_id)){
        $parent = 0;
        foreach($request->language_id as $key => $language_id){
        
           if($key == 0){
              $c = $Country;
              $c->code = !empty($request->codes[$key]) ? $request->codes[$key] : 0;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->nicename = !empty($request->nicenames[$key]) ? $request->nicenames[$key] : 0;
              $c->iso3 = !empty($request->isos[$key]) ? $request->isos[$key] : 0;
              $c->numcode = $request->numcode;
              $c->phonecode = $request->phonecode;
              $c->latitude = $request->latitude;
              $c->longitude = $request->longitude;
              $c->language_id = $language_id;
              $c->tax = $request->tax;
              $c->save();
              $parent = $c->id;
           }else{
              $country = Country::where('parent',$id)->where('language_id',$language_id);
              $c = $country->count() == 1 ? $country->first() : new Country;
              $c->code = !empty($request->codes[$key]) ? $request->codes[$key] : 0;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->nicename = !empty($request->nicenames[$key]) ? $request->nicenames[$key] : 0;
              $c->iso3 = !empty($request->isos[$key]) ? $request->isos[$key] : 0;
              $c->parent = $parent;
              $c->language_id = $language_id;
              $c->save();
           }

        }
        return redirect()->route('godpanel.country.index')->with('success','Country updated successfully!');
      }      
}



public function saveSettings(Request $request,$id)
{
   $country = Country::find($id);
   $arr = ['_token'];
   foreach ($variable as $type => $values) {
     if(!in_array($type, $arr)){
      $t = CountryMetaData::where('country_id',$id)->where('type',$type);
      $m = $t->count() > 0 ? $t->first() : new CountryMetaData;
      $m->type = $type;
      $m->meta_values = json_encode($values);
      $m->country_id = $id;
      $m->save();
      }
   }
    return redirect()->route('godpanel.country.index')->with('success','Settings updated successfully!');
}



}
