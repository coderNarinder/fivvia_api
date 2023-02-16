<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessType;
use App\Models\BusinessCategory;
use App\Models\Client;
use App\Models\ClientPreference;
use App\Models\PaymentOption;
use App\Models\ClientCheckoutInformation;
use Auth;
use App\Models\Business\ClientBusinessType;
use App\Models\Language;
use App\Models\ClientCurrency;
class DashboardController extends Controller
{
  
private $path = 'pictures/business/logo/';
 private $folderName = 'BusinessType/icon/';
  public function index($value='')
  {
      $business_categories = BusinessCategory::where('parent',0)->orderBy('sorting','ASC')->paginate(20);
      return view('business.modules.index',[
        'business_categories' => $business_categories,
        'completed_steps'=> 0
      ]);
  }
  
  public function business_category($value='')
  {
       $business_categories = BusinessCategory::where('parent',0)
       ->orderBy('sorting','ASC')->paginate(20);
       $client = Client::find(Auth::user()->client_id);
          return view('business.modules.steps.business_category',[
            'business_categories' => $business_categories,
            'completed_steps'=> 1,
            'client' => $client

          ]);
  }
  
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function store_business_category(Request $request)
  {
      $this->validate($request,[
         'business_category' => 'required'
      ]);

      $category = BusinessCategory::where('id',$request->business_category);

      if($category->count() == 0){
        return redirect()->back()->with('messages','Business Category not found!!');
      }

      $cate = $category->first();

      $client = Client::find(Auth::user()->client_id);

      if($client->business_category_id != $request->business_category || $client->business_category_id == 0){
        $client->business_category_id = $request->business_category;
      }
      $client->business_category_type = $cate->slug;
      $client->completed_steps = 1;
      $client->save();
      return redirect()->route('business.business_type');

   }


  public function businessType($value='')
  {
         
      $client = Client::find(Auth::user()->client_id);
      if( $client->completed_steps == 0){
           return redirect()->route('business.business_category');
      }

       $user_ids = [];
       $user_ids[] = 0;
       $user_ids[] = $client->id;

       $languages = Language::where('basic_lang',1)->orderBy('id','ASC')->get();
       $categories = BusinessCategory::where('parent',0)
       ->where('id',$client->business_category_id)
       
       ->orderBy('name','ASC')
       ->get(); 
       $business_categories = BusinessType::where('parent',0)
       ->whereIn('client_id',$user_ids)
       ->where('business_category_id',$client->business_category_id)
       ->orderBy('title','ASC')
       ->paginate(20);
       return view('business.modules.steps.business_type',[
            'categories' => $categories,
            'languages' => $languages,
            'business_categories' => $business_categories,
            'completed_steps'=> 2,
            'client' => Auth::user()->client
       ]);
  }

  public function businessTypeStoreNew(Request $request)
  {
    
    if(!empty($request->language_id)){
         $parent = 0;
         $business_category = $request->business_category;
         $img_path = $request->hasFile('image2') ? uploadFileWithAjax23($this->folderName,$request->file('image2')) : '';
        foreach($request->language_id as $key => $language_id){
        
           if($key == 0){
              $c = new BusinessType;
              $c->title = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->image = $img_path; 
              $c->business_category_id = $business_category; 
              // $c->logistics = $request->logistics;
              $c->client_id = Auth::user()->client_id;
              $c->save();
              $parent = $c->id;
           }else{
              $c = new BusinessType;
              $c->title = !empty($request->names[$key]) ? $request->names[$key] : 0;
              $c->language_id = $language_id;
              $c->image = $img_path;
              $c->parent = $parent;
              $c->business_category_id = $business_category;
              // $c->logistics = $request->logistics;
              $c->client_id = Auth::user()->client_id;
              $c->save();
           }

        }
        $status = ['status' => 1,'messages' => 'Business type is submitted successfully, you can use it but admin will approve it'];
      }else{
        $status = ['status' => 0,'messages' => 'Something wrong is going on.'];
      }
      return response()->json($status);
  }
  
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function businessTypeStore(Request $request)
  {
       
      if(empty($request->business_types)){
        
        return redirect()->back()->with('error_messages','Please choose type atleast one.');
      }



      // $business_type = BusinessType::findOrFail($request->business_type);
      $client = Client::find(Auth::user()->client_id);

      $oldBusinessTypes = ClientBusinessType::where('client_id',$client->id)->delete();
      
      foreach ($request->business_types as $business_type_id) {

              // $business_type = BusinessType::findOrFail($business_type_id);
              $c = new ClientBusinessType;
              $c->client_id = $client->id;
              $c->business_type_id = $business_type_id;
              $c->status = 1;
              $c->save();
      }
      
     
      $oldBusinessTypeArray = ClientBusinessType::where('client_id',$client->id);
      if($oldBusinessTypeArray->count() == 0){
       
        return redirect()->back()->with('error_messages','Please choose type atleast one.');
      }
      $client->business_types = json_encode($oldBusinessTypeArray->pluck('business_type_id')->toArray());
      $client->completed_steps = 2;
      $client->save();
      return redirect()->route('business.business_details');

   }

  

  public function businessDetail($value='')
  {
         

         $countries = \App\Models\Country::where('parent',0)->where('language_id',1)->orderBy('name','ASC')->get();
         $timezones = \App\Models\Timezone::orderBy('timezone','ASC')->get();
         $languages = \App\Models\Language::where('id',1)->get();
         $currencies = \App\Models\Currency::orderBy('iso_code','asc')->get();
         $client = Client::find(Auth::user()->client_id);
         $primaryCur = \App\Models\ClientCurrency::where('is_primary', 1)->where('client_id', Auth::user()->client_id); 

         $currency_id = $primaryCur->count() > 0 ? $primaryCur->first()->currency_id : 0;

        if( $client->completed_steps < 2){
           return redirect()->route('business.business_type');
        }
         

         $email = Client::where('id','!=',$client->id)->where('email',$client->email)->count();
         $company_name = Client::where('id','!=',$client->id)->where('company_name',$client->company_name)->count();
         $phone_number = Client::where('id','!=',$client->id)->where('phone_number',$client->phone_number)->count(); 



          return view('business.modules.steps.business_details',[
            'completed_steps'=> 3,
            'email'=>$email,
            'currency_id'=>$currency_id,
            'company_name'=>$company_name,
            'currencies'=>$currencies,
            'phone_number'=>$phone_number,
            'client' => Auth::user()->client,
            'countries' => $countries,
            'timezones' => $timezones,
            'languages' => $languages,
          ]);
  }
  
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function businessDetailStore(Request $request)
  {
      $v = \Validator::make($request->all(),[
         'business_type' => 'required',
         'company_name' => 'required',
         'longitude' => 'required',
         'latitude' => 'required',
         'phone_number' => 'required',
         'email' => 'required',
         'country' => 'required',
         'primary_language' => 'required',
         'phone_number' => 'required',
         
         'company_address' => 'required',
         'primary_currency' => 'required',
      ]);


      
      $client = Client::find(Auth::user()->client_id);
      $client->business_id = 'FB'.$client->id;
      $client->company_name = $request->company_name;
      $client->email = $request->email;
      $client->phone_number = $request->phone_number;
      $client->country_id = $request->country;
      $client->timezone = $request->timezone;
      $client->company_address = $request->company_address;
      $client->longitude = $request->longitude;
      $client->latitude = $request->latitude;
      $logo = $request->hasFile('logo') ? uploadFileWithAjax23($this->path,$request->file('logo')) : $client->logo['image_path'];
      $client->logo = $logo;
      $client->completed_steps = 3;
      $client->save();

      $ClientPreference = ClientPreference::where('client_id',$client->id);
      $business_type = ClientBusinessType::where('client_id',$client->id)->first();
      $p = $ClientPreference->count() > 0 ? $ClientPreference->first() : new ClientPreference;
      $p->client_id = $client->id;
      $p->client_code = $client->id;
      $p->language_id  = $request->primary_language;
      $p->business_type = $business_type->businessType->slug;
      $p->save();


             $oldAdditional = ClientCurrency::where('currency_id', $request->primary_currency)
                                      ->where('is_primary', 0)
                                      ->where('client_id', $client->id)
                                      ->delete();
            $c_data = ['is_primary' => 1,'client_code'=>$client->id,'client_id' => $client->id,'currency_id' => $request->primary_currency, 'doller_compare' => 1];

            $primaryCur = ClientCurrency::where('is_primary', 1)->where('client_id', $client->id);
            if($primaryCur->count() == 1){
                $primaryCur->update($c_data);
            }else{
               ClientCurrency::insert($c_data);
            }

       
      

      return response()->json(['status'=> 1,'url' => route('business.businessPlan'), 'messages' => 'Business details added sucessfully!']);

   }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

   public function checkBusinesField(Request $request)
   {
      
      if(!empty($request->email)){
        $user1 = Client::where('email',$request->email)->where('id','!=',Auth::user()->client_id)->count();
        $user = $user1 == 1 ? 0 : 1;
      }elseif(!empty($request->phone_number)){
        $user1 = Client::where('phone_number',$request->phone_number)->where('id','!=',Auth::user()->client_id)->count();
        $user = $user1 == 1 ? 0 : 1;
      }elseif(!empty($request->company_name)){
        $user1 = Client::where('company_name',$request->company_name)->where('id','!=',Auth::user()->client_id)->count();
        $user = $user1 == 1 ? 0 : 1;
      }
      
      echo $user > 0 ? 'true' : 'false';
   }

  
  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function businessPlan(Request $request)
  {
       
         $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 3){
           return redirect()->route('business.business_details');
         }
         $packages = \App\Models\SubscriptionPackages::get();
          return view('business.modules.steps.packages',[
            'completed_steps'=> 4,
            'client' => Auth::user()->client,
            'packages' => $packages
          ]);

  }


  // #---------------------------------------------------------------------------------
  // # STORE FUNCTION OF STEP !
  // #---------------------------------------------------------------------------------

  // public function bankDetails(Request $request)
  // {
       
  //       $client = Client::findOrFail(Auth::user()->client_id);
  //        if( $client->completed_steps < 4){
  //          return redirect()->route('business.businessPlan');
  //       }
          
  //         return view('business.modules.steps.bankDetails',[
  //           'completed_steps'=> 5,
  //           'client' => Auth::user()->client,
            
  //         ]);

  // }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function paymentOptions(Request $request)
  {
       
        $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 4){
           return redirect()->route('business.businessPlan');
        }
         $paymentOptions = \App\Models\CountryMetaData::where('country_id',$client->country_id)->where('type','payments');
         $options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
         $payment_options = \App\Models\PaymentOption::whereIn('code',$options)->where('client_id',0)->get();
          return view('business.modules.steps.paymentOptions',[
            'completed_steps'=> 5,
            'client' => Auth::user()->client,
            'payment_options' => $payment_options
          ]);

  }







  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function paymentOptionSaved(Request $request)
  {
        $client = Client::findOrFail(Auth::user()->client_id);
        $payment_opt = !empty($request->payment_options) ? (array)$request->payment_options : [];
        $payment_options = \App\Models\PaymentOption::where('client_id',0)->get();
        foreach($payment_options as $column => $p){
            if(in_array($p->code, $payment_opt)){
                 $payment_opts = PaymentOption::where('client_id',$client->id)->where('code',$p->code);
                 if($payment_opts->count() == 0){
                   $PaymentOption = new PaymentOption;
                   $PaymentOption->code = $p->code;
                   $PaymentOption->path = $p->path;
                   $PaymentOption->title = $p->title;
                   $PaymentOption->credentials = $p->credentials;
                   $PaymentOption->status = $p->status;
                   $PaymentOption->off_site = $p->off_site;
                   $PaymentOption->test_mode = $p->test_mode;
                   $PaymentOption->client_token_id = $client->code;
                   $PaymentOption->client_id = $client->id;
                   $PaymentOption->save();
                 }
            }else{
               PaymentOption::where('client_id',$client->id)->where('code',$p->code)->delete();
            }
        } 

        $counts = \App\Models\PaymentOption::where('client_id',$client->id)->count();
        if($counts > 0){
             $client->completed_steps = 5;
             $client->save(); 
             return response()->json([
                'status'=> 1,
                'url' => route('business.logistics'), 
                'messages' => 'Please payment options have been assigned sucessfully!'
            ]);
        }else{
             return response()->json([
                'status'=> 0,
                'url' => route('business.logistics'), 
                'messages' => 'Please choose payment options'
            ]);
        }

  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function logisticsSaved(Request $request)
  {
        $client = Client::findOrFail(Auth::user()->client_id);
        $shipping_options = !empty($request->shipping_options) ? (array)$request->shipping_options : [];

        foreach($shipping_options as $type){
            
                 $payment_opts = \App\Models\ShippingOption::where('client_id',$client->id)
                            ->where('code',$type);
                 if($payment_opts->count() == 0){
                     $s = new \App\Models\ShippingOption;
                     $s->code = $type;
                     $s->client_id = $client->id;
                     $s->save();
                 }
        } 

        \App\Models\ShippingOption::where('client_id',$client->id)->whereNotIn('code',$shipping_options)->delete();
        $counts = \App\Models\ShippingOption::where('client_id',$client->id)->count();
        if($counts > 0){
             $client->completed_steps = 6;
             $client->save(); 
             return response()->json([
                'status'=> 1,
                'url' => route('business.domains'), 
                'messages' => 'Please shipping options have been assigned sucessfully!'
            ]);
        }else{
             return response()->json([
                'status'=> 0,
                'url' => route('business.domains'), 
                'messages' => 'Please choose shipping options'
            ]);
        }

  }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function logistics(Request $request)
  {
       
        $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 5){
           return redirect()->route('business.paymentOptions');
         }

         if(empty($client->business_types)){
             return redirect()->route('business.business_type');
         }

         $business_types = json_decode($client->business_types);

          $logistics = \App\Models\BusinessType::whereIn('id',$business_types)->where('logistics',1)->count();

         if($logistics == 0){
           $client->completed_steps = 6;
           $client->save();
            return redirect()->route('business.domains');
         }


         $paymentOptions = \App\Models\CountryMetaData::where('country_id',$client->country_id)
                                                      ->where('type','shipping_options');
         $options = $paymentOptions->count() > 0 ? json_decode($paymentOptions->first()->meta_values) : [];
         $shipping_options = \App\Models\ShippingType::whereIn('id',$options)->get();
       
          return view('business.modules.steps.logistics',[
            'completed_steps'=> 6,
            'client' => Auth::user()->client,
            'shipping_options' => $shipping_options,
           ]);
          

  }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function domains(Request $request)
  {
       
        $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 6){
           return redirect()->route('business.logistics');
        }
         return view('business.modules.steps.domains',[
            'completed_steps'=> 7,
            'client' => Auth::user()->client,
             
          
          ]);
  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function template(Request $request)
  { 
        $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 7){
           return redirect()->route('business.domains');
        }

        // savetemplates();


         return view('business.modules.steps.templates',[
            'completed_steps'=> 8,
            'client' => Auth::user()->client,
            'templates' => $client->package->getTemplates()
             
          
          ]);
  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function templateSave(Request $request)
  { 
        $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 7){
           return redirect()->route('business.domains');
        }
        
        $ClientPreference = ClientPreference::where('client_id',$client->id);
        if(!empty($request->template)){
          $c = $ClientPreference->count() > 0 ? $ClientPreference->first() : new ClientPreference;
          $c->client_id = $client->id;
          $c->web_template_id = $request->template;
          $c->save();
          $client->completed_steps = 8;
          $client->save();
          return redirect()->route('business.checkout')->with('messages','Template saved successfully.');
        }else{
           return redirect()->route('business.template')->with('messages','Please choose any template.');
        }



  }

  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function checkout(Request $request)
  { 
        $client = Client::findOrFail(Auth::user()->client_id);
        if($client->completed_steps < 8){
           return redirect()->route('business.template');
        }

        if(empty($client->package)){
           return redirect()->route('business.businessPlan');
        }

        $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);

        $package = Auth::user()->client->package;
        $members = $client->members;
        $domain_rate = 0;
        $rate = $package->price + ($members * $package->extra_per_member_rate);

        

        $country = $client->country;
        $tax = $country->tax > 0 ? (($rate / 100) * $country->tax) : 0;
        $pvtReg =$ClientCheckoutInformation->count() > 0 && $ClientCheckoutInformation->first()->pvtReg > 0 ? $ClientCheckoutInformation->first()->pvtReg : 0; 
        $total = $tax + $rate + $pvtReg;

        // domain rate + package price
        $subtotal = $rate + $domain_rate;

            $extra_members = [];
         if($client->members > 0){
            $extra_members = [
              'members' => $client->members,
              'price' => ($package->extra_per_member_rate * $client->members)
             ];
          }
                                                                         

        $package_details = [
           'title' => $package->title,
           'price' => $package->price,
           'duration' => $package->duration,
           'default_member' => $package->default_member,
           'extra_per_member_rate' => $package->extra_per_member_rate,
           'extra_members' => $extra_members
        ];

        $domain_details = [
          'domain' => $client->custom_domain,
          'price'=> 'free'
        ];
          $trial_end_date = date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))));
          $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;

          $checkout->package_details = json_encode($package_details);
          $checkout->domain_details = json_encode($domain_details);
          $checkout->client_id = $client->id;
          $checkout->subtotal = $subtotal;
          $checkout->tax = $tax;
          $checkout->total = $total;
          $checkout->trial_end_date = $trial_end_date;
          $checkout->save();


          return view('business.modules.steps.checkout',[
              'completed_steps'=> 8,
              'client' => Auth::user()->client,
              'package' => $package,
              'rate' => $rate,
              'tax' => $tax,
              'members' => $members,
              'total' => $total,
              'country' => $country,
              'checkout' => $checkout,
          ]);
  }


  #---------------------------------------------------------------------------------
  # STORE FUNCTION OF STEP !
  #---------------------------------------------------------------------------------

  public function checkoutSave(Request $request)
  { 
        $client = Client::findOrFail(Auth::user()->client_id);
         if( $client->completed_steps < 8){
           return redirect()->route('business.template');
        }
           $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);
           $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
         if(!empty($checkout->check_step) && $checkout->check_step == 1){ 
          
           
           $checkout->check_step = 2;
           $checkout->save();

           return redirect()->route('business.checkout');
         }elseif(!empty($checkout->check_step) && $checkout->check_step == 2){

            $checkout->name = $request->name;
            $checkout->email = $request->email;
            $checkout->phone = $request->phone;
            $checkout->address = $request->address;
           
            $checkout->check_step = 3;
            $checkout->save();
             return redirect()->route('business.checkout');
         }elseif(!empty($checkout->check_step) && $checkout->check_step == 3){

          
            $checkout->pvtReg = !empty($request->pvtReg) ? 30 : 0;
            $checkout->check_step = 4;
            $checkout->save();
           return redirect()->route('business.checkout');

         }elseif(!empty($checkout->check_step) && $checkout->check_step == 4){   

         
          $trial_end_date = date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))));
         $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);
         $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
         $checkout->paid_status = 1;
         $checkout->trial_end_date = $trial_end_date;
         $checkout->save();

         return redirect()->route('business.dashboard');
       }
  }


  public function checkoutDuration($id)
  {
            $client = \App\Models\Client::find(\Auth::user()->client_id); 
            $client->package_id = $id;
            $client->save();
            return response()->json(['status' => 1, 'messages' => 'Plan duration changed successfully!']);
  }

  public function checkoutBack($value='')
  {
           $client = Client::findOrFail(Auth::user()->client_id);
           $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',$client->id);
           $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
         if(!empty($checkout->check_step) && $checkout->check_step == 1){ 
          return redirect()->route('business.template');
         }elseif(!empty($checkout->check_step) && $checkout->check_step == 2){
                $checkout->check_step = 1;
                $checkout->save();
             return redirect()->route('business.checkout');
         }elseif(!empty($checkout->check_step) && $checkout->check_step == 3){
                $checkout->check_step = 2;
                $checkout->save();
           return redirect()->route('business.checkout');

         }elseif(!empty($checkout->check_step) && $checkout->check_step == 4){   
                $checkout->check_step = 2;
                $checkout->save();
            return redirect()->route('business.checkout');
         }
  }


}
