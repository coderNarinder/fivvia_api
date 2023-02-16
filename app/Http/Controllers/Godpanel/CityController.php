<?php
namespace App\Http\Controllers\Godpanel;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, Language, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, Brand, CategoryHistory, City, Type, CategoryTag, Vendor, State, DispatcherWarningPage, DispatcherTemplateTypeOption, Product,CategoryTranslation,Country};
use GuzzleHttp\Client as GCLIENT;
class CityController extends Controller
{
    private $blocking = '2';
    private $folderName = 'cities/icon';
    private $view_path = 'godpanel.modules.cities.';

#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function index()
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $country = City::where('parent',0)->orderBy('name','ASC')->paginate(20);
         
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
        $country = Country::where('parent',0)->orderBy('name','ASC')->get(); 
        return view($this->view_path.'add')
        ->with([
            'languages' => $languages,
            'country' => $country,
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
              $c = new City;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->country_id = $request->country_name;
              $c->state_id = $request->state_name;
              $c->save();
              $parent = $c->id;
           }else{
              $c = new City;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->country_id = $request->country_name;
              $c->state_id = $request->state_name;
              $c->parent = $parent;
              $c->language_id = $language_id;
              $c->save();
           }

        }
        return redirect()->route('godpanel.city.index')->with('success','City Added successfully!');
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
             $country = Country::where('parent',0)->orderBy('name','ASC')->get(); 
            $data = City::find($id);
             $states = State::where('parent',0)->where('country_id',$data->country_id)->orderBy('name','ASC')->get(); 
        return view($this->view_path.'edit')
        ->with([
            'languages' => $languages,
            'data' => $data,
            'country' => $country,
            'states' => $states,
         ]);
}

   
   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function destroy($id)
{
        
      $Country = City::where('id',$id)->orWhere('parent',$id)->delete();
       return redirect()->route('godpanel.city.index')->with('success','City deleted successfully!');
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function update(Request $request,$id)
{

    // return $request->all();
       $Country = City::find($id);
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      if(!empty($request->language_id)){
        $parent = 0;
        foreach($request->language_id as $key => $language_id){
        
           if($key == 0){
              $c = $Country;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->country_id = $request->country_name;
              $c->state_id = $request->state_name;
              $c->save();
              $parent = $c->id;
           }else{
              $country = City::where('parent',$id)->where('language_id',$language_id);
              $c = $country->count() == 1 ? $country->first() : new City;
              $c->name = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->parent = $parent;
              $c->country_id = $request->country_name;
              $c->state_id = $request->state_name;
              $c->language_id = $language_id;
              $c->save();
           }

        }
        return redirect()->route('godpanel.city.index')->with('success','City updated successfully!');
      }      
}


}
