<?php

namespace App\Http\Livewire\Business\Steps;

use Livewire\Component;
use App\Models\SubscriptionPackages;
use Auth;
use App\Models\ClientCheckoutInformation;
class Packages extends Component
{
    public $duration_type = 0;
    public $durations = 'monthly';
    public $trial = 1;
    public $trial_status = 1;
    public $members = [];
    public $members_rates = [];
    public $packages = [];

    public function mount($value='')
    {
        $this->durations = $this->duration_type == 1 ? 'yearly' : 'monthly';
        if(!empty(Auth::user()->client->client_billing_details)){
            $paid_status = Auth::user()->client->client_billing_details->paid_status;
            $this->trial = $paid_status;
            $this->trial_status = $paid_status;

        }
        $this->packages = SubscriptionPackages::where('duration',$this->durations)->get();
    }

    public function changeType($value='')
    {
        $this->dispatchBrowserEvent('loader-show');
        $this->durations = $this->duration_type == 1 ? 'yearly' : 'monthly';
        $this->dispatchBrowserEvent('loader-hide');
    }

    public function changeTrialStatus()
    {
        $this->trial = $this->trial_status;
    }

    public function loadinit($value='')
    {
        $this->dispatchBrowserEvent('loader-hide');
        # code...
    }

    public function updated($value='')
    {
         $this->dispatchBrowserEvent('loader-show');
    	$this->durations = $this->duration_type == 1 ? 'yearly' : 'monthly';
    	$this->packages = SubscriptionPackages::where('duration',$this->durations)->get();
        $this->dispatchBrowserEvent('loader-hide');

    }

    public function render()
    {

        return view('livewire.business.steps.packages');
    }

    public function increaseMemberData($p_id,$type)
    {
        $this->dispatchBrowserEvent('loader-show');
        $members= !empty($this->members[$p_id]) ? $this->members[$p_id] : 0;
        $members_rates= !empty($this->members_rates[$p_id]) ? $this->members_rates[$p_id] : 0;
        $package = SubscriptionPackages::where('id',$p_id)->first();
        $rate = $package->extra_per_member_rate;
       if($type == "plus") {
           $current_members = $members + 1;
           $this->members_rates[$p_id] = ($current_members * $rate);
           $this->members[$p_id] = $current_members;
        }elseif($type == "minus"){
            if($members > 0){
                $current_members = $members - 1;
                $this->members_rates[$p_id] = ($current_members * $rate);
                $this->members[$p_id] = $current_members;
            }
           
        }elseif($type == "choose"){
            $ClientCheckoutInformation = ClientCheckoutInformation::where('client_id',Auth::user()->client->id);
            $client = \App\Models\Client::find(\Auth::user()->client_id);
            $client->completed_steps = 4;
            $client->members = $members;
            $client->package_id = $p_id;
            $client->save();

            $checkout = $ClientCheckoutInformation->count() == 1 ? $ClientCheckoutInformation->first() : new ClientCheckoutInformation;
 
           $checkout->trial_status = $this->trial_status;
           $checkout->save();


            return redirect()->route('business.paymentOptions');

        }
        $this->dispatchBrowserEvent('loader-hide');
    }
}
