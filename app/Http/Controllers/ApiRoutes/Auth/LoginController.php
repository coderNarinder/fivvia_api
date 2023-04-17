<?php

namespace App\Http\Controllers\ApiRoutes\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\ApiRoutes\M1\BaseController; 
use Illuminate\Support\Str;
use Auth;
use App\Models\User;
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
        	if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'code'=>$this->client_id])) {
		                $client = User::where('email',$request->email)->first();
			            if($client->is_superadmin == 1 || $client->is_admin == 1){
			                  $user = Auth::user();
						      $token = $user->createToken("API TOKEN")->plainTextToken;
						      $status = [
						      	'status' => 1,
						        'accessToken' => $token,
						        'data' => $user,
						        'client_head'=> $this->client_head,
						        'client_preferencs' => $this->client_preferencs
						      ]; 
			            }else{
			                
			                $status = ['status' => 2,'message' => 'You are unauthorized user.'];
			            }
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
		
         $status = [
	      	'status' => 1,
	        'data' => $request->user(),
	        'client_head'=> $this->client_head,
	        'client_preferencs' => $this->client_preferencs
	      ]; 
        return response()->json($status);
    }

}