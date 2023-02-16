<?php

use App\Models\CartProduct;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\{User, TempCartProduct, Vendor};
use App\Models\Nomenclature;
use App\Models\UserRefferal;
use App\Models\ProductVariant;
use App\Models\ClientPreference;
use App\Models\Client as ClientData;
use App\Models\PaymentOption;
use App\Models\ShippingOption;
use App\Models\VendorSlot;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Providers\Custom\ClientCacheClass;
# GET WEBSITE CLIENT DATA FROM REDIS
 
require __DIR__.'/helpers/template_helper_fun.php'; 
require __DIR__.'/helpers/cache_helpers.php'; 



function checkBusinessCurl($token)
{
 
try {
   
    // dd($this->jwt_request($token));
    $httpClient = new \GuzzleHttp\Client();
    
    $newresponse = $httpClient->request(
    'POST',
    'https://api.fivvia.com/user/getLoginData', 
    ['headers' => 
    [
        'Authorization' => "Bearer ".$token,
        'Content-Type' => 'application/json', 
        'Accept' => 'application/json'
    ],
    'body' => '{}'
    ]  
    )->getBody()->getContents();
     return $data = json_decode($newresponse);
} catch (Exception $e) {

    $data = $e->getMessage();
   return  [
       'statusCode' => 401,
       'message'=> $data
   ];
}
   

   

}



 
function custom_format($n, $d = 0) {
    $n = number_format($n, $d, '.','');
    $n = strrev($n);

    if ($d) $d++;
    $d += 3;

    if (strlen($n) > $d)
        $n = substr($n, 0, $d) . ','
            . implode(',', str_split(substr($n, $d), 2));

    return strrev($n);
}

function getWebClient()
{   
    $obj = new ClientCacheClass;
    return $obj->client_head;
}


function getTemplate()
{ 
   $obj = new ClientCacheClass;
   $temp_id = '';
   $client_preferencs = $obj->ClientPreferencs;
   if(!empty($client_preferencs) && !empty($client_preferencs->template)){
        $temp_id = $client->client_preferencs->template->temp_id;
   } 
return $temp_id;
}

function getWebClientID()
{   
    $obj = new ClientCacheClass;
    return $obj->client_id;
}

function getAppType()
{    
    return  env('BUSINESS_TYPE','tours');
}

function getDomainName($value='')
{
    $domain = url('/');
    if(config('app.env') != 'local') {
        $domain = str_replace(array('https://', '.test.com/login'), '', $domain);
     }else{
        $domain = str_replace(array('http://', '.test.com/login'), '', $domain); 
     }
    $subDomain = explode('.', $domain);
    return $domain;
}

function clientSettings()
{    
    $obj = new ClientCacheClass;
    return $obj->ClientPreferencs; 
}


function getTemplateLayoutPath($t)
{

      $obj = new ClientCacheClass;
      $path = $obj->template_path; 
   //   $domain = url('/');
   // if(config('app.env') != 'local') {
   //      $domain = str_replace(array('https://', '.test.com/login'), '', $domain);
   //   }else{
   //      $domain = str_replace(array('http://', '.test.com/login'), '', $domain); 
   //   }
   //  $path = (ARRAY)json_decode(Redis::get($domain.'_LAYOUT_TEMPLATE'));
 
  return !empty($path[$t]) ? $path[$t] : '';
   
}

function getTemplates(){
 return [
    'template-1' => [
       'title' => 'Basic Design',
       'image' => 'fivvia_business/templates/1.png',
       'tagline' => 'You can advertise your business here'
    ],
    'template-2' => [
       'title' => 'Template 1',
       'image' =>  'fivvia_business/templates/2.png',
       'tagline' => 'You can advertise your business here'
    ],
    'template-3' => [
       'title' => 'Template 2',
       'image' => 'fivvia_business/templates/3.png',
       'tagline' => 'You can advertise your business here'
    ],
    'template-4' => [
       'title' => 'Template 3',
       'image' =>  'fivvia_business/templates/4.png',
       'tagline' => 'You can advertise your business here'
    ],
    'template-5' => [
       'title' => 'Template 4',
       'image' => 'fivvia_business/templates/5.png',
       'tagline' => 'You can advertise your business here'
    ]
];

}


function savetemplates($value='')
{
  foreach(getTemplates() as $temp_id => $template){
     $t = \App\Models\Template::where('temp_id',$temp_id);
     $te = $t->count() == 1 ? $t->first() : new \App\Models\Template;
     $te->temp_id = $temp_id;
     $te->name = $template['title'];
     $te->image = $template['image'];
     $te->tagline = $template['tagline'];
     $te->for = 1;
     $te->save();
  }
}

function getPackageFeatures(){
 return [
    'social-media-posts',
    'stories',
    'zash',
    'events',
    'groups',
    'channels',
    'chat',
    'payment-gateways',
    'logistic',
    'fleet-tracking',
    'visitor-analytics',
    'sell-unlimited-products'
  ];
}

function getPackageFeaturesAll($duration){
    $Basic = \App\Models\SubscriptionPackages::where('duration',$duration)->where('type','Basic')->first();
    $Standard = \App\Models\SubscriptionPackages::where('duration',$duration)->where('type','Standard')->first();
    $Advance = \App\Models\SubscriptionPackages::where('duration',$duration)->where('type','Advance')->first();
 return [
    'social-media-posts' => getPackageFeature('social-media-posts',$Basic,$Standard,$Advance,$duration),
    'stories' => getPackageFeature('stories',$Basic,$Standard,$Advance,$duration),
    'zash' => getPackageFeature('zash',$Basic,$Standard,$Advance,$duration),
    'events' => getPackageFeature('events',$Basic,$Standard,$Advance,$duration),
    'groups' => getPackageFeature('groups',$Basic,$Standard,$Advance,$duration),
    'channels' => getPackageFeature('channels',$Basic,$Standard,$Advance,$duration),
    'chat' => getPackageFeature('chat',$Basic,$Standard,$Advance,$duration),
    'payment-gateways' => getPackageFeature('payment-gateways',$Basic,$Standard,$Advance,$duration),
    'logistic' => getPackageFeature('logistic',$Basic,$Standard,$Advance,$duration),
    'fleet-tracking' => getPackageFeature('fleet-tracking',$Basic,$Standard,$Advance,$duration),
    'visitor-analytics' => getPackageFeature('visitor-analytics',$Basic,$Standard,$Advance,$duration),
    'sell-unlimited-products' => getPackageFeature('sell-unlimited-products',$Basic,$Standard,$Advance,$duration)
  ];
}


function getPackageFeature($type,$basic,$standard,$advance)
{
     
     $Basic = \App\Models\SubscriptionPackageFeature::where('package_id',$basic->id)
              ->where('type',$type)->first();
     $Standard = \App\Models\SubscriptionPackageFeature::where('package_id',$standard->id)
              ->where('type',$type)->first();
     $Advance = \App\Models\SubscriptionPackageFeature::where('package_id',$advance->id)
              ->where('type',$type)->first();                   
     return [
       'feature' => $type,
       'basic' => $Basic->has_feature,
       'standard' => $Standard->has_feature,
       'advance' => $Advance->has_feature,
     ];
}

function urlOfImage($imagePath)
{
    return !empty($imagePath) ? url($imagePath) : $imagePath;
     // return \Storage::disk('s3')->url($img)
}





 function uploadFileWithAjax23($path,$file)
 {

                    $timestamp = time().str::random(5);
                    $hash = explode(' ',$file->getClientOriginalName());
                    $OriginalName = implode("",$hash);
                    $hash2 = explode('-',$OriginalName);
                    $OriginalName2 = implode("",$hash2);
                    $name = $timestamp. '' .$OriginalName2;  
                    if($file->move($path, $name)) {
                         return $path.$name;
                        
                    }else{

                        return 0;
                    }
}


function changeDateFormate($date,$date_format){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
}

function pr($var) {
  	echo '<pre>';
	print_r($var);
  	echo '</pre>';
    exit();
}
function http_check($url) {
    $return = $url;
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $return = 'http://' . $url;
    }
    return $return;
}
function getUserDetailViaApi($user){
    $user_refferal = UserRefferal::where('user_id', $user->id)->first();
    $client_preference = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();
    $data['name'] = $user->name;
    $data['email'] = $user->email;
    $data['source'] = $user->image;
    $data['is_admin'] = $user->is_admin;
    $data['dial_code'] = $user->dial_code;
    $data['auth_token'] =  $user->auth_token;
    $data['phone_number'] = $user->phone_number;
    $data['client_preference'] = $client_preference;
    $data['cca2'] = $user->country ? $user->country->code : '';
    $data['callingCode'] = $user->country ? $user->country->phonecode : '';
    $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code: '';
    $data['verify_details'] = ['is_email_verified' => $user->is_email_verified, 'is_phone_verified' => $user->is_phone_verified];
    return $data;
}
function getMonthNumber($month_name){
    if($month_name == 'January'){
        return 1;
    }else if($month_name == 'February'){
        return 2;
    }else if($month_name=='March'){
        return 3;
    }else if($month_name=='April'){
        return 4;
    }else if($month_name=='May'){
        return 5;
    }else if($month_name=='June'){
        return 6;
    }else if($month_name=='July'){
        return 7;
    }else if($month_name=='August'){
        return 8;
    }else if($month_name=='September'){
        return 9;
    }else if($month_name=='October'){
        return 10;
    }else if($month_name=='November'){
        return 11;
    }else if($month_name=='December'){
        return 12;
    }
}
function generateOrderNo($length = 8){
    $number = '';
    do {
        for ($i=$length; $i--; $i>0) {
            $number .= mt_rand(0,9);
        }
    } while (!empty(\DB::table('orders')->where('order_number', $number)->first(['order_number'])) );
    return $number;
}
function generateWalletTransactionReference($length = 8){
    $number = '';
    do {
        $number = 'txn_' . md5(uniqid(rand(), true));
    } 
    while (!empty(\DB::table('transactions')->where('meta', 'Like', '%'. $number .'%')->first(['meta'])) );
    return $number;
}
function getNomenclatureName($searchTerm, $plural = true){
    $result = Nomenclature::with(['translations' => function($q) {
                $q->where('language_id', session()->get('customerLanguage'));
            }])->where('label', 'LIKE', "%{$searchTerm}%")->first();
    if($result){
        $searchTerm = $result->translations->count() != 0 ? $result->translations->first()->name : ucfirst($searchTerm);
    }
    return $plural ? $searchTerm : rtrim($searchTerm, 's');
}
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s'){
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}
function getClientPreferenceDetail()
{
    $client_preference_detail = ClientPreference::first();
    list($r, $g, $b) = sscanf($client_preference_detail->web_color, "#%02x%02x%02x");
    $client_preference_detail->wb_color_rgb = "rgb(".$r.", ".$g.", ".$b.")";
    return $client_preference_detail;
}
function getClientDetail()
{
    $clientData = ClientData::first();
    $clientData->logo_image_url = $clientData ? $clientData->logo['image_fit'].'150/92'.$clientData->logo['image_path'] : " ";
    return $clientData;
}
function getRazorPayApiKey()
{
    $razorpay_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'razorpay')->where('status', 1)->first();
    $api_key_razorpay = "";
    if($razorpay_creds)
    {
        $creds_arr_razorpay = json_decode($razorpay_creds->credentials);
        $api_key_razorpay = (isset($creds_arr_razorpay->api_key)) ? $creds_arr_razorpay->api_key : '';
    }
    return $api_key_razorpay;
}

function dateTimeInUserTimeZone($date, $timezone, $showDate=true, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $date_format = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    if($date_format == 'DD/MM/YYYY'){
        $date_format = 'DD-MM-YYYY';
    }
    $time_format = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    $dateFormat = '';
    if($showDate){
        $dateFormat = $date_format;
    }
    if($showTime){
        if($showSeconds){
            $secondsKey = ':ss';
        }
        if($time_format == '12'){
            $timeFormat = ' hh:mm'.$secondsKey.' A';
        }else{
            $timeFormat = ' HH:mm'.$secondsKey;
        }
    }

    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
}

function dateTimeInUserTimeZone24($date, $timezone, $showDate=true, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $date_format = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    if($date_format == 'DD/MM/YYYY'){
    $date_format = 'DD-MM-YYYY';
    }
    $time_format = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    $dateFormat = '';
    if($showDate){
    $dateFormat = $date_format;
    }
    if($showTime){
    $timeFormat = 'HH:mm:ss';
    }
    
    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
    }

function helper_number_formet($number){
    return number_format($number,2);
}
function productvariantQuantity($variantId ,$type=1){
    if($type==1){
        $ProductVariant =  ProductVariant::where('id',$variantId)
        ->select('quantity')->first();
    }else{
        $ProductVariant =  ProductVariant::where('sku',$variantId)
        ->select('quantity')->first();
    }
    if($ProductVariant){
    return  $ProductVariant->quantity;
    }
    return "variant not found";
}
function checkImageExtension($image)
{
    $ch =  substr($image, strpos($image, ".") + 1);
    $ex = "";
    if($ch == 'svg')
    {
        $ex = "";
    }
    return $ex;
}
function getDefaultImagePath()
{
    $values = array();
    $img = 'default/default_image.png';
    $values['proxy_url'] = \Config::get('app.IMG_URL1');
    // $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).'@webp';
    $values['image_path'] =  \Storage::disk('s3')->url($img);
    $values['image_fit'] = \Config::get('app.FIT_URl');
    return $values;
}
function getImageUrl($image,$dim)
{
    $server = env('APP_ENV', 'development');
    if($server == 'local')
    {
        return $image;
    }
    return \Storage::disk('s3')->url($image);//\Config::get('app.FIT_URl').$dim.\Config::get('app.IMG_URL2').'/'.$image.'@webp';
}


function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function createSlug($str, $delimiter = '-'){

    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;

}

    function getBaseprice($dist,$option = 'lalamove')
    {
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code',$option)->where('status', 1)->first();
        if($simp_creds && $simp_creds->credentials){
            $creds_arr = json_decode($simp_creds->credentials);
            $base_price = $creds_arr->base_price??'0';
            if($base_price>0)
            {
                $distance = $creds_arr->distance??'0';
                $amount_per_km = $creds_arr->amount_per_km??'0';

            }
            $lalamove_status = $simp_creds->status??'';
        }
         $distance = $dist;  
        if($distance < 1 || $base_price < 1)
        {
            return 0;    
        }

        $base_price = $base_price;
        $amount_per_km = $amount_per_km;
        $total = $base_price + ($distance * $amount_per_km);
        return  $total;
    // + ($paid_duration * $pricingRule->duration_price);
    }


    function SplitTime($myDate,$StartTime, $EndTime, $Duration="60",$delayMin = 0)
    {
    $Duration = (($Duration==0)?'60':$Duration);

    $user = Auth::user();
    if(isset($user->timezone) && !empty($user->timezone))
    $timezoneset = $user->timezone;
    else
    {   
        $client = ClientData::orderBy('id','desc')->select('id','timezone')->first();

        if(isset($client->timezone_data->timezone) && !empty($client->timezone_data->timezone))
        $timezoneset = $client->timezone_data->timezone;
        else
        $timezoneset = 'Asia/Kolkata';
    }
    

    $cr = Carbon::now()->addMinutes($delayMin);
    $now = dateTimeInUserTimeZone24($cr, $timezoneset);
    $nowT = strtotime($now);
    $nowA = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$StartTime);
    $nowS = Carbon::createFromFormat('Y-m-d H:i:s', $nowA)->timestamp;
    $nowE = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$EndTime)->timestamp;
    if($nowT > $nowE)
    {
    return [];
    }elseif($nowT>$nowS)
    {
    $StartTime = date('H:i',strtotime($now));
    }else{
    $StartTime = date('H:i',strtotime($nowA));
    }
    
    
    $ReturnArray = array ();
    $StartTime = strtotime ($StartTime); //Get Timestamp
    $EndTime = strtotime ($EndTime); //Get Timestamp
    $AddMins = $Duration * 60;
    $endtm = 0;
    
    while ($StartTime <= $EndTime)
    {
    $endtm = $StartTime + $AddMins;
    if($endtm>$EndTime)
    {
    $endtm = $EndTime;
    }
    
    $ReturnArray[] = date ("G:i", $StartTime).' - '.date ("G:i", $endtm);
    $StartTime += $AddMins;
    $endtm = 0;
    }
    return $ReturnArray;
}


function showSlotTemp($myDate = null, $vid, $user_id, $type = 'delivery',$duration="60")
{
    //   $type = $type;
    //type must be a : delivery , takeaway,dine_in
    $client = ClientData::select('timezone')->first();
    $viewSlot = array();
    if(!empty($myDate)){
        $mytime = Carbon::createFromFormat('Y-m-d', $myDate)->setTimezone($client->timezone_data->timezone);
    }else{
        $myDate  = date('Y-m-d'); 
        $mytime = Carbon::createFromFormat('Y-m-d', $myDate)->setTimezone($client->timezone_data->timezone);
    }
    $mytime =$mytime->dayOfWeek+1;
    $slots = VendorSlot::where('vendor_id',$vid)
        ->whereHas('days',function($q)use($mytime,$type){
            return $q->where('day',$mytime)->where($type,'1');
        })->get();
    
    $min[] = '';
    $cart = TempCartProduct::where('vendor_id',$vid)->get();
    foreach($cart as $product){
       $min[] = (($product->product->delay_order_hrs * 60) + $product->product->delay_order_min);
    }

    if(isset($slots) && count($slots)>0){
        foreach($slots as $slot){
            //  echo '=h-='.$slot->dayOne->id;
            if(isset($slot->dayOne->id) && ($slot->dayOne->id > 0))
            {   
               $slotss[] = SplitTimeTemp($user_id, $myDate, $slot->start_time, $slot->end_time, $duration, max($min));
            }else{
                $slotss[] = [];
            }
        }
        $arr = array();
        $count = count($slotss);
        for($i=0;$i<$count;$i++){
            $arr = array_merge($arr,$slotss[$i]);
        }
        if(isset($arr)){
            foreach($arr as $k=> $slt)
            {
                $sl = explode(' - ',$slt);
                $viewSlot[$k]['name'] = date('h:i:A',strtotime($sl[0])).' - '.date('h:i:A',strtotime($sl[1]));
                $viewSlot[$k]['value'] = $slt;
            }
        }
    }
    return $viewSlot;
}

function SplitTimeTemp($user_id, $myDate,$StartTime, $EndTime, $Duration="60",$delayMin = 5)
{
    $Duration = (($Duration==0)?'60':$Duration);
    $user = Auth::user();
    if(isset($user->timezone) && !empty($user->timezone))
    $timezoneset = $user->timezone;
    else
    {   
        $client = ClientData::orderBy('id','desc')->select('id','timezone')->first();

        if(isset($client->timezone_data->timezone) && !empty($client->timezone_data->timezone))
        $timezoneset = $client->timezone_data->timezone;
        else
        $timezoneset = 'Asia/Kolkata';
    }
    

    $cr = Carbon::now()->addMinutes($delayMin);
    $now = dateTimeInUserTimeZone24($cr, $timezoneset);
    $nowT = strtotime($now);
    $nowA = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$StartTime);
    $nowS = Carbon::createFromFormat('Y-m-d H:i:s', $nowA)->timestamp;
    $nowE = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$EndTime)->timestamp;
    if($nowT > $nowE)
    {
        return [];
    }elseif($nowT>$nowS)
    {
        $StartTime = date('H:i',strtotime($now));
    }else{
        $StartTime = date('H:i',strtotime($nowA));
    }


    $ReturnArray = array ();
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp
    $AddMins  = $Duration * 60;
    $endtm = 0;

    while ($StartTime <= $EndTime) 
    {
        $endtm = $StartTime + $AddMins;
        if($endtm>$EndTime)
        {
         $endtm =  $EndTime;
        }

        $ReturnArray[] = date ("G:i", $StartTime).' - '.date ("G:i", $endtm);
        $StartTime += $AddMins+60; 
        $endtm = 0;
    }
    //dd($ReturnArray);
    
    return $ReturnArray;
}

    
function showSlot($myDate = null,$vid,$type = 'delivery',$duration="60")
{
 $slotDuration = Vendor::select('slot_minutes')->where('id',$vid)->first();
$duration = ($slotDuration->slot_minutes) ?? $duration;
$type = ((session()->get('vendorType'))?session()->get('vendorType'):$type);
//type must be a : delivery , takeaway,dine_in
$client = ClientData::select('timezone')->first();
$viewSlot = array();
if(!empty($myDate))
{ 
    $mytime = Carbon::createFromFormat('Y-m-d', $myDate)->setTimezone($client->timezone_data->timezone);
}else{
$myDate = date('Y-m-d');
$mytime = Carbon::createFromFormat('Y-m-d', $myDate)->setTimezone($client->timezone_data->timezone);
}
$mytime =$mytime->dayOfWeek+1;
$slots = VendorSlot::where('vendor_id',$vid)
->whereHas('days',function($q)use($mytime,$type){
return $q->where('day',$mytime)->where($type,'1');
})
->get();
$min[] = '';
$cart = CartProduct::where('vendor_id',$vid)->get();
if(isset($cart) && $cart->count()>0){
foreach($cart as $product)
{
    $delayHr= isset($product->product->delay_order_hrs) ? ($product->product->delay_order_hrs) : 0;
    $delayMin= isset($product->product->delay_order_min) ? ($product->product->delay_order_min) : 0;
    $min[] = (($delayHr * 60) + $delayMin);
}
}

if(isset($slots) && count($slots)>0){
foreach($slots as $slott){
if(isset($slott->days->id))
{
$slotss[] = SplitTime($myDate,$slott->start_time,$slott->end_time,$duration,max($min));
}else{
$slotss[] = [];
}
}

$arr = array();
$count = count($slotss);
for($i=0;$i<$count;$i++){
$arr = array_merge($arr,$slotss[$i]);
}

if(isset($arr)){
foreach($arr as $k=> $slt)
{
$sl = explode(' - ',$slt);
$viewSlot[$k]['name'] = date('h:i:A',strtotime($sl[0])).' - '.date('h:i:A',strtotime($sl[1]));
$viewSlot[$k]['value'] = $slt;
}
}
}

return $viewSlot;
}

function findSlot($myDate = null,$vid,$type = 'delivery',$api = null)
{
   $myDate  = date('Y-m-d'); 
   $type = ((session()->get('vendorType'))?session()->get('vendorType'):$type);
            $slots = showSlot($myDate,$vid,'delivery');
            // return count((array)$slots);
            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+1 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            // dd($slots);
            }
           
            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+2 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }

            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+3 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }
        if(isset($slots) && count((array)$slots)>0){
            $time = explode(' - ',$slots[0]['value']);

            if($api != 'api'){
                return date('d M, Y h:i:A',strtotime($myDate.'T'.$time[0]));
            }else{
                return date('Y-m-d',strtotime($myDate.'T'.$time[0]));
            }
            
        }else{
            return 0;
        }
}

function findSlotNew($myDate,$vid)
{
        $slots = showSlot($myDate,$vid,'delivery');
            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+1 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }
           
            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+2 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }

            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+3 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }
            if(isset($slots)){
                $slots = $slots;
                return array('mydate'=>$myDate,'slots'=>$slots);
            }else{
                return array('mydate'=>'','slots'=>[]);
            }
}

function GoogleDistanceMatrix($latitude, $longitude)
{
    $send   = [];
    $client = ClientPreference::where('id', 1)->first();
    $lengths = count($latitude) - 1;
    $value = [];
    
    for ($i = 1; $i<=$lengths; $i++) {
        $count  = 0;
        $count1 = 1;
        $ch = curl_init();
        $headers = array('Accept: application/json',
                'Content-Type: application/json',
                );
         $url =  'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$latitude[$count].','.$longitude[$count].'&destinations='.$latitude[$count1].','.$longitude[$count1].'&key='.$client->map_key.'';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch); // Close the connection
        $new =   $result;
       // dd($result);
        array_push($value, $result->rows[0]->elements);
        $count++;
        $count1++;
    }
  
    if (isset($value)) {
        $totalDistance = 0;
        $totalDuration = 0;
        foreach ($value as $item) {
            //dd($item);
            $totalDistance = $totalDistance + $item[0]->distance->value;
            $totalDuration = $totalDuration + $item[0]->duration->value;
        }
       
       
        if ($client->distance_unit == 'metric') {
            $send['distance'] = round($totalDistance/1000, 2);      //km
        } else {
            $send['distance'] = round($totalDistance/1609.34, 2);  //mile
        }
        //
        $newvalue = round($totalDuration/60, 2);
        $whole = floor($newvalue);
        $fraction = $newvalue - $whole;

        if ($fraction >= 0.60) {
            $send['duration'] = $whole + 1;
        } else {
            $send['duration'] = $whole;
        }
    }
    return $send;
}
function getDynamicMail(){
    $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
    $config = array(
        'driver' => $data->mail_driver,
        'host' => $data->mail_host,
        'port' => $data->mail_port,
        'from'       => array('address' => $data->mail_from, 'name' => $data->mail_from),
        'encryption' => $data->mail_encryption,
        'username' => $data->mail_username,
        'password' => $data->mail_password,
        'sendmail' => '/usr/sbin/sendmail -bs',
        'pretend' => false,
    );
    \Config::set('mail.mailers.smtp', $config);
    return 2;
}
function getDynamicTypeName($name)
{
    $new_name = getNomenclatureName($name, true);
    $new_name = ($new_name === $name) ? __($name) : $new_name;
    return $new_name;
}

// Number Format according to Client preferences
function decimal_format($number,$format="")
{
    $preference = session()->get('preferences');
    $digits = $preference['digit_after_decimal'] ?? 2;
    return number_format($number,$digits,'.',$format);
}

