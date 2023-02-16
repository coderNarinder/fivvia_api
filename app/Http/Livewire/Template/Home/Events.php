<?php

namespace App\Http\Livewire\Template\Home;
use Carbon\Carbon;
use Livewire\Component;

class Events extends Component
{
	public $banners =[];

	public function getData($value='')
	{
		       $this->banners = \App\Models\CustomEvent::where('validity_on', 1)
                        ->where(function ($q) {
                            $q->whereNull('start_date_time')->orWhere(function ($q2) {
                                $q2->whereDate('start_date_time', '<=', Carbon::now())
                                    ->whereDate('end_date_time', '>=', Carbon::now());
                            });
                        })
                        ->where('client_id',getWebClientID())
                        ->with('category')
                        ->with('vendor')
                        ->get();
	}
    public function render()
    {
      $this->getData();
        return view('livewire.template.home.events');
    }
}
