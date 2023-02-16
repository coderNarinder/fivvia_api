<?php

namespace App\Http\Livewire\Business;

use Livewire\Component;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use DB;
class Register extends Component
{

    public $name;
    public $password;
    public $email;
    public $phone_number;
    public $redirection_link;
    public $redirection_linked;
   
    protected $rules = [
        'name' => 'required',
        'password' => 'required|min:6',
        'email' => 'required|email|unique:users',
        'phone_number' => 'required|numeric|min:10|unique:users',
    ];


    public function render()
    {
        return view('livewire.business.register');
    }


#-----------------------------------------------------------------------------------------------------
# Function Divider
#-----------------------------------------------------------------------------------------------------

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

#-----------------------------------------------------------------------------------------------------
# Function Divider
#-----------------------------------------------------------------------------------------------------
    
    public function checkLogin()
    {
        $validatedData = $this->validate();


        DB::beginTransaction();
        try {

             $c = new Client;
             $c->name = $this->name;
             $c->email = $this->email;
             $c->password = \Hash::make($this->password);
             $c->name = $this->name;
             $c->phone_number = $this->phone_number;
             $c->save();

             $u = new User;
             $u->email = $this->email;
             $u->password = \Hash::make($this->password);
             $u->name = $this->name;
             $u->phone_number = $this->phone_number;
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
             
            DB::commit();


            session()->flash('message', 'registeration successfully.');
            return redirect()->route('business.dashboard');
                  
        } catch (\Exception $e) {
            DB::rollback();
            
             session()->flash('error', $e->getMessage());
        }
            
        
    }


}
