<?php

namespace App\Http\Livewire\Godpanel;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
class Login extends Component
{

    public $password;
    public $email;
    public $redirection_link;
    public $redirection_linked;
     public $type = "admin";
    protected $rules = [
        'password' => 'required|min:6',
        'email' => 'required|email',
    ];

#-----------------------------------------------------------------------------------------------------
# Function Divider
#-----------------------------------------------------------------------------------------------------

    public function render()
    {
        return view('livewire.godpanel.login');
    }

#-----------------------------------------------------------------------------------------------------
# Function Divider
#-----------------------------------------------------------------------------------------------------

    public function updated($propertyName)
    {
        $this->redirection_linked = $this->redirection_link;
        $this->validateOnly($propertyName);
    }

#-----------------------------------------------------------------------------------------------------
# Function Divider
#-----------------------------------------------------------------------------------------------------
    
    public function checkLogin(Request $request)
    {
        $validatedData = $this->validate();

        // if($this->type == "business" && Auth::attempt(['email' => $this->email, 'password' => $this->password])){
        //    return redirect()->route('business.dashboard');
        // }else
        if(Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password]))
        {
            session()->flash('message', 'Login successfully.');
            $admin = Admin::where('email', $this->email)->first();
            Auth::login($admin);
            $details = Auth::guard('admin')->user();
            $user = $details['original'];
            return redirect()->route('god.dashboard');
        }else{
            session()->flash('error', 'Invalid Credentials');
        }
    }
}
