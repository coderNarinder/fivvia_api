<?php

namespace App\Http\Livewire\Business\Steps;

use Livewire\Component;
use Auth;
use App\Models\Client;
class Domains extends Component
{
    public $domain_type = 1;
    public $subdomain = '';
	public $domain = '';
    public $rules = [];

     

    public function mount($value='')
    {
        $c = Client::find(Auth::user()->client_id);
        $domain = !empty($c->custom_domain) ? $c->custom_domain : $this->createDomain($c);
        $this->subdomain = $domain;
        $this->domain = $domain;
    }


    public function render()
    {
        return view('livewire.business.steps.domains');
    }

    public function createDomain($c)
    {
        if(!empty($c->company_name)){
            $domainArray = explode(' ', $c->company_name);
            $domain = implode('-', $domainArray);
            return 'https://'.strtolower($domain).'.fivvia.com';
         }
    }

    public function updated()
    {
       $this->dispatchBrowserEvent('loader-hide');

        
    }

    public function changeType($value)
    {
    	$this->domain_type = $value; 
    }

    public function saveDomains()
    {
             $this->dispatchBrowserEvent('loader-show');
          $c = Client::find(Auth::user()->client_id);
             $domain_url = str_replace('https://','',$this->domain);
             $domain_url = str_replace('http://','',$domain_url);
             #  
             $subdomain_url = str_replace('https://','',$this->subdomain);
             $subdomain_url = str_replace('http://','',$subdomain_url);


       
             $domain =  Client::where('custom_domain',$domain_url)
                              ->where('id','!=',$c->id)
                              ->count();
             $sub_domain = Client::where('custom_domain',$subdomain_url)
                             ->where('id','!=',$c->id)
                              ->count();



 

           
        if($this->domain_type == 0){
            if(!empty($this->domain)){

                if($domain == 0){
                    $c->custom_domain = $domain_url;
                    $c->sub_domain = $domain_url;
                    $c->completed_steps = 7;
                    $c->save();
                    session()->flash('message', 'Domain is assigned successfully!');
                     return redirect()->route('business.template');
                }else{
                  session()->flash('error', 'The url is not available.');
                }
            }else{
              
              session()->flash('error', 'Pease add domain first.');
            }
        }elseif($this->domain_type == 1){
            if(!empty($this->subdomain)){
                 if($sub_domain == 0){
                    $c->custom_domain = $subdomain_url;
                    $c->sub_domain = $subdomain_url;
                    $c->completed_steps = 7;
                    $c->save();
                    session()->flash('message', 'Domain is assigned successfully!');
                     return redirect()->route('business.template');
                }else{
                  session()->flash('error', 'The url is not available.');
                }
            }else{
               session()->flash('error', 'Pease add domain first.');
            }
        } 
         $this->dispatchBrowserEvent('loader-hide');
    }
}
