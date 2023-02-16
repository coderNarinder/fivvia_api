<?php

namespace App\Http\Controllers\Godpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Models\Language;
use Session;
use Auth;
class PaymentOptionController extends Controller
{
     private $blocking = '2';
    private $folderName = 'PaymentOption/icon/';
    private $view_path = 'godpanel.modules.PaymentOption.';

#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function index()
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $country = PaymentOption::where('client_id',0)->orderBy('title','ASC')->paginate(20);

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
               $options = [
             'omnipay/paypal',
			 'omnipay/stripe',
			 'paystackhq/omnipay-paystack',
			 'omnipay/payfast',
			 'mobbex/sdk',
			 'yoco/yoco-php-laravel',
			 'paylink/paylink',
			 'razorpay/razorpay',
			 'adyen/php-api-library',
			 'rak/simplify',
			 'square/square',
			 'tradesafe/omnipay-ozow',
			 'pagarme/pagarme-php',
			 'checkout/checkout-sdk-php',
			 'mobbex/sdk'
			];

			 $codes = [
             'cod',
			 'paypal',
			 'stripe',
			 'paystack',
			 'payfast',
			 'mobbex',
			 'yoco',
			 'paylink',
			 'razorpay',
			 'gcash',
			 'simplify',
			 'square',
			 'ozow',
			 'pagarme',
			 'checkout'
			];
        return view($this->view_path.'add')
        ->with([
            'languages' => $languages,
            'options' => $options,
            'codes' => $codes,
         ]);
}

  
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function store(Request $request)
{

     
      $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
      $img_path = uploadFileWithAjax23($this->folderName,$request->file('image2'));
       
      $c = new PaymentOption;
      $c->name =  $request->name;
      $c->type = $request->type;
      $c->image = $img_path; 
      $c->status = 0;
      $c->save(); 
           
        return redirect()->route('godpanel.PaymentOption.index')->with('success','Shipping type Added successfully!');
         
}


   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function edit($id)
{
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $languages = Language::where('basic_lang',1)->orderBy('id','ASC')
            ->get(); 
              $options = [
             'omnipay/paypal',
			 'omnipay/stripe',
			 'paystackhq/omnipay-paystack',
			 'omnipay/payfast',
			 'mobbex/sdk',
			 'yoco/yoco-php-laravel',
			 'paylink/paylink',
			 'razorpay/razorpay',
			 'adyen/php-api-library',
			 'rak/simplify',
			 'square/square',
			 'tradesafe/omnipay-ozow',
			 'pagarme/pagarme-php',
			 'checkout/checkout-sdk-php',
			 'mobbex/sdk'
			];

			 $codes = [
             'cod',
			 'paypal',
			 'stripe',
			 'paystack',
			 'payfast',
			 'mobbex',
			 'yoco',
			 'paylink',
			 'razorpay',
			 'gcash',
			 'simplify',
			 'square',
			 'ozow',
			 'pagarme',
			 'checkout'
			];

        $data = PaymentOption::find($id); 
        return view($this->view_path.'edit')
        ->with([
            'options' => $options,
            'codes' => $codes,
            'languages' => $languages,
            'data' => $data
         ]);
}

   
   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function destroy($id)
{
        
      $Country = PaymentOption::where('id',$id)->delete();
       return redirect()->route('godpanel.PaymentOption.index')->with('success','Shipping type deleted successfully!');
}

   
#-----------------------------------------------------------------------------------------------
# Country Listing
#-----------------------------------------------------------------------------------------------
public function update(Request $request,$id)
{

    // return $request->all();
       $Country = PaymentOption::find($id);
       $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
       $img_path = $request->hasFile('image2') ? uploadFileWithAjax23($this->folderName,$request->file('image2')) : $Country->image;
		$c = $Country;
		$c->title =  $request->title;
		$c->path =  $request->path;
		$c->code =  $request->code;
		$c->payment_gateway_link =  $request->payment_gateway_link;
	   $c->image = $img_path; 
		$c->status = $request->status;
		$c->save(); 
      
        return redirect()->route('godpanel.PaymentOption.index')->with('success','Shipping type updated successfully!');
        
}


}
