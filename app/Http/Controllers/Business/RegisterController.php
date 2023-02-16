<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Client;
use App\Models\User;
class RegisterController extends Controller
{
  # Business Home
  public function index()
  {
     if(\Auth::check() && !empty(Auth::user()->client_id)){
       return redirect()->route('business.dashboard');
    }
     $u = User::find(1);
          Auth::login($u);
    return view('business.index');
  }

  public function register(){
    if(\Auth::check() && !empty(Auth::user()->client_id)){
       return redirect()->route('business.dashboard');
    }
    return view('business.register');
  }


  public function logout($value='')
  {
    \Auth::logout();
    return redirect()->to('home');
  }



  public function createToken(Request $request)
  {
     
     if(empty($request->token)){
        abort(404);
     }

     $token = $request->token;
     // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6IlVTRVIiLCJfaWQiOiI2MzI5NjNjMDA3YzJkZTdmNzk1OWJhZWEiLCJ0aW1lIjoiMjAyMi0xMC0yOFQwNjoxNzoxMy42NjhaIiwiaWF0IjoxNjY2OTM3ODMzfQ.twr2nosvUeXnaevzJgE5NCjAeJSagDjPRVEJKyeXliY';

      $result = (object)checkBusinessCurl($token);
     if($result->statusCode == 200){
      $data= $result->data;
       $client = Client::where('fivvia_business_id',$data->_id);
       if($client->count() > 0){
          $c = $client->first();
          $u = User::where('client_id',$c->id)->first();
           $guard = Auth::login($u); 
           $url = route('business.dashboard');

       }else{
         $url = $this->checkBusinessLogin($request,$data);
       }
       return !empty($request->redirect_url) ? redirect($request->redirect_url) : redirect($url);
     }
     return redirect()->route('business.home');
  }


 
public function checkBusinessLogin($request,$data)
{
             $c = new Client;
             $c->name = $data->fullName;
             $c->email = $data->email;
             $c->password = \Hash::make($data->email);
             $c->phone_number = $data->phone;
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
             $u->save();

             $Client = Client::find($c->id);
             $Client->code = $c->id;
             $Client->user_id = $u->id;
             $Client->save();
             $guard = Auth::login($u); 

             return route('business.dashboard');
}



}
