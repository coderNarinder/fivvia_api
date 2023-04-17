<?php

namespace App\Http\Controllers\ApiRoutes\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\ApiRoutes\Business\BaseController; 
use Illuminate\Support\Str;
use Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\BusinessType;
use App\Models\BusinessCategory;
class LoginController extends BaseController
{
  

#-----------------------------------------------------------------------------------------
# Login ajax for client
#-----------------------------------------------------------------------------------------

	public function getLoggedIn(Request $request)
	{
		 
        $v = \Validator::make($request->all(),[
	        'email'           => 'required|max:255|email',
          'password'        => 'required',
	      ]);
        
        if($v->fails()){
            $status = ['status' => 0,'errors' => $v->errors()];
        }else{ 
        	if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'role' => 'admin'])) {
		                $client = User::where('email',$request->email)->first();
			           
			                  $user = Auth::user();
      						      $token = $user->createToken("API TOKEN")->plainTextToken;
      						      $status = [
      						      	'status' => 1,
      						        'accessToken' => $token,
      						        'data' => $user
      						      ]; 
			           
            }else{
            	 $status = ['status' => 2,'message' => 'Invalid Credentials'];
            }
        }
        return response()->json($status);
    }


#-----------------------------------------------------------------------------------------
# Login ajax for client
#-----------------------------------------------------------------------------------------

	public function loginDetails(Request $request)
	{
		     $user = $request->user();
         $status = [
	      	'status' => 1,
	        'data' => $user
	      ]; 
        return response()->json($status);
    }




  public function createToken(Request $request)
  {
     
    
     if(empty($request->fivvia_token)){
        abort(404);
     }
     $token = $request->fivvia_token; 
     $result = (object)checkBusinessCurl($token);
     if($result->statusCode == 200){
      $data= $result->data;
       $client = Client::where('fivvia_business_id',$data->_id);
       if($client->count() > 0){
            $c = $client->first();
            $u = User::where('client_id',$c->id)->first();
            if($u->is_superadmin == 1 || $u->is_admin == 1){
                if(Auth::attempt(['email' => $u->email, 'password' => $u->email])){
                      $user = Auth::user();
				      $token = $user->createToken("API TOKEN")->plainTextToken;
				      $status = [
				      	'status' => 1,
				        'accessToken' => $token,
				        'data' => $user,
				        'client_head'=> $c,
				        'client_preferencs' => $this->preference($c->id)
				      ];
            	 }else{
                     $status = ['status' => 2,'message' => 'You are unauthorized user.'];
            	 }
                   
            }else{
                $status = ['status' => 2,'message' => 'You are unauthorized user.'];
            }

       }else{
         $status = $this->checkBusinessLogin($request,$data);
       }
       
     }else{
     	 $status = ['status' => 2,'message' => 'You are unauthorized user.'];
     }
      return response()->json($status);
  }


 
public function checkBusinessLogin($request,$data)
{           
             $phone = explode('-', $data->phone);
             $c = new Client;
             $c->name = $data->fullName;
             $c->email = $data->email;
             $c->password = \Hash::make($data->email);
             $c->dial_code = $phone[0];
             $c->phone_number = $phone[1];
             $c->fivvia_business_id = $data->_id;
             $c->save();

             $u = new User;
             $u->email = $data->email;
             $u->password = \Hash::make($data->email);
             $u->name = $c->name;
             $u->phone_number = $c->phone_number;
             $u->code = $c->id;
             $u->client_id = $c->id;
             $u->is_superadmin = 1;
             $u->is_admin = 1;
             $u->role = 'client';
             $u->save();


             $Client = Client::find($c->id);
             $Client->code = $c->id;
             $Client->business_id = 'FB'.$Client->id;
             $Client->user_id = $u->id;
             $Client->save(); 

             if(Auth::attempt(['email' => $u->email, 'password' => $u->email])){
                  $user = Auth::user();
			      $token = $user->createToken("API TOKEN")->plainTextToken;
			      $status = [
			      	'status' => 1,
			        'accessToken' => $token,
			        'data' => $user,
			        'client_head'=> $Client,
				    'client_preferencs' => $this->preference($Client->id)
			      ];
        	 }else{
                 $status = ['status' => 2,'message' => 'You are unauthorized user.'];
        	 }

        	 return $status;
}




#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  UPLOAD FILE FUNCTION
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

public function getBusinessCategories(Request $request)
{ 
     
        $banners = $this->businessCategories();
        $response = [
            'status' => 0,
            'message' => 'businessCategories successfully',
            'data' => [
               'listing' => $banners
               ]
        ];
        return response()->json($response);
}



#--------------------------------------------------------------------------------------------------------------------------------------------------------------
#  UPLOAD FILE FUNCTION
#--------------------------------------------------------------------------------------------------------------------------------------------------------------

public function getBusinessTypes(Request $request)
{ 
        $user = $request->user();
        $client = Client::find($user->client_id);
        $user_ids = [];
        $user_ids[] = 0;
        $user_ids[] = $client->id;

       $languages = \App\Models\Language::where('basic_lang',1)->orderBy('id','ASC')->get();
       $categories = BusinessCategory::where('parent',0)
                                       ->where('id',$client->business_category_id)
                                       ->orderBy('name','ASC')
                                       ->get(); 
       $business_categories = BusinessType::where('parent',0)
                                       ->whereIn('client_id',$user_ids)
                                       ->where('business_category_id',$client->business_category_id)
                                       ->orderBy('title','ASC')
                                       ->get();
     
         
        $response = [
            'status' => 0,
            'message' => 'businessCategories successfully',
            'data' => [
               'listing' => $business_categories,
               'categories'=> $categories,
               'languages'=> $languages
               ]
        ];
        return response()->json($response);
}


}