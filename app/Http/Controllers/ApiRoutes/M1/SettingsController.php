<?php

namespace App\Http\Controllers\ApiRoutes\M1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\ClientLanguage;
use App\Models\ClientCurrency;
use App\Http\Controllers\ApiRoutes\M1\BaseController; 
use Illuminate\Support\Str;
use DB;
class SettingsController extends BaseController
{
  


public function updateSettings(Request $request)
{

    if(!empty($request->type) && $request->type == 'lang'){
    	ClientLanguage::where('client_code', $this->client_id)->delete();
        foreach ($request->options as $lan) {
        	if(!empty($lan)){
        	    $langs = ClientLanguage::where('client_code', $this->client_id)->where('language_id', $lan);
                $client_language = $langs->count() > 0 ? $langs->first() : new ClientLanguage();
                $client_language->client_code = $this->client_id;
                $client_language->is_primary = 0;
                $client_language->language_id = $lan;
                $client_language->is_active = 1;
                $client_language->save();
            }
        }

        $language = ClientLanguage::where('client_code', $this->client_id)->where('is_primary',1)->where('language_id',$request->primary);
        $client_language = $language->count() > 0 ? $language->first() : new ClientLanguage();
        $client_language->client_code = $this->client_id;
        $client_language->is_primary = 1;
        $client_language->language_id = $request->primary;
        $client_language->is_active = 1;
        $client_language->save();

        $c= \App\Models\ClientPreference::where('client_id',$this->client_id)->first();
        $c->language_id = $request->primary;
        $c->save();
        $this->removeCaches('settings');
      
    }elseif(!empty($request->type) && $request->type == 'deleteCurrency'){
        ClientCurrency::where('client_code', $this->client_id)->where('currency_id',$request->primary)->delete();

    }elseif(!empty($request->type) && $request->type == 'currency'){
        foreach ($request->options as $key => $lan) {
                $langs = ClientCurrency::where('client_code', $this->client_id)->where('currency_id', $lan);
                $client_language = $langs->count() > 0 ? $langs->first() : new ClientCurrency();
                $client_language->client_code = $this->client_id;
                $client_language->is_primary = 0;
                $client_language->currency_id = $lan;
                $client_language->doller_compare = !empty($request->doller_compare[$key]) ? $request->doller_compare[$key] : 1;
                $client_language->save();
        }
 
    }elseif(!empty($request->type) && $request->type == 'addCurrencyValues'){ 
                $langs = ClientCurrency::where('client_code', $this->client_id)->where('currency_id',$request->primary);
                if($langs->count() > 0){
                    $client_language = $langs->first(); 
                    $client_language->doller_compare = $request->options;
                    $client_language->save();
                }
 
    }elseif(!empty($request->type) && $request->type == 'AddPrimaryCurrency'){
    	 
        $language = ClientCurrency::where('client_code', $this->client_id)->where('is_primary',1);
        if($language->count() > 0){
            $curr1 = $language->first();
            if($curr1->currency_id != $request->primary){
              $curr1->is_primary = 0;
              $curr1->save();
            }

            $language = ClientCurrency::where('client_code', $this->client_id)->where('currency_id',$request->primary);
            $curr = $language->count() > 0 ? $language->first() : new ClientCurrency();
            $curr->client_code = $this->client_id;
            $curr->client_id = $this->client_id;
            $curr->is_primary = 1;
            $curr->currency_id = $request->primary; 
            $curr->save();

        }else{
            $language = ClientCurrency::where('client_code', $this->client_id)->where('currency_id',$request->primary);
            $curr = $language->count() > 0 ? $language->first() : new ClientCurrency();
            $curr->client_code = $this->client_id;
            $curr->client_id = $this->client_id;
            $curr->is_primary = 1;
            $curr->currency_id = $request->primary; 
            $curr->save();
        }
       
        $c= \App\Models\ClientPreference::where('client_id',$this->client_id)->first();
        $c->currency_id = $request->primary;
        $c->save();
        $this->removeCaches('settings');
    }

     $data = ['message'=> 'Settings saved successfully!','status'=> 1];
     return response()->json($data);
}



public function getCustomization($value='')
{
	 $langs = $arr1 = ClientLanguage::with('language')->where('client_code', $this->client_id)->where('is_active',1)->where('is_primary',0);
	 $currencies = $arr2 = ClientCurrency::with('currency')->where('client_code', $this->client_id)->where('is_primary',0);
     $primary_c = ClientCurrency::with('currency')->where('client_code', $this->client_id)->where('is_primary',1)->first();
     $primary_l = ClientLanguage::with('language')->where('client_code', $this->client_id)->where('is_primary',1)->first();
	     return response()->json([
             'data' => [
                 'languages' => $langs->get(),
                 'language_ids' => $arr1->pluck('language_id')->toArray(),
                 'currencies' => $currencies->get(),
                 'currency_ids' => $arr2->pluck('currency_id')->toArray(),
                 'primary_language' => $primary_l,
                 'primary_currency' => $primary_c,
                 
             ]
         ]);
}

public function getPaymentOptions($value='')
{
    $payment_options = \App\Models\PaymentOption::where('client_id',$this->client_id)->get();
         return response()->json([
             'data' => [
                 'listing' => $payment_options,
             ]
         ]);
}


public function updatePaymentOptions(Request $request)
{
       
        $payment_opt = !empty($request->payment_options) ? (array)$request->payment_options : [];
        $payment_options = \App\Models\PaymentOption::where('client_id',0)->get();
        foreach($payment_options as $column => $p){
            if(in_array($p->id, $payment_opt)){
                 $payment_opts = \App\Models\PaymentOption::where('client_id',$this->client_id)->where('parent',$p->id);
                 if($payment_opts->count() == 0){
                   $PaymentOption = new \App\Models\PaymentOption;
                   $PaymentOption->parent = $p->id;
                   $PaymentOption->code = $p->code;
                   $PaymentOption->path = $p->path;
                   $PaymentOption->title = $p->title;
                   $PaymentOption->credentials = $p->credentials;
                   $PaymentOption->status = $p->status;
                   $PaymentOption->off_site = $p->off_site;
                   $PaymentOption->test_mode = $p->test_mode;
                   $PaymentOption->client_token_id = $this->client_id;
                   $PaymentOption->client_id = $this->client_id;
                   $PaymentOption->save();
                 }
            }else{
                   \App\Models\PaymentOption::where('client_id',$this->client_id)->where('parent',$p->id)->delete();
            }
        } 

        // $payment_options = \App\Models\PaymentOption::where('client_id',$this->client_id)->pluck('parent')->toArray();
        $payment_options = \App\Models\PaymentOption::where('client_id',$this->client_id)->pluck('parent')->toArray();
        $c= \App\Models\ClientPreference::where('client_id',$this->client_id)->first();
        $c->payment_options = json_encode($payment_options);
        $c->save();
        $this->removeCaches('settings');

         $data = ['message'=> 'Payment saved successfully!','status'=> 1,'ids' => $payment_options];
     return response()->json($data);
}



}