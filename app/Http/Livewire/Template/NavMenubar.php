<?php

namespace App\Http\Livewire\Template;
use Carbon\Carbon;
use Livewire\Component;
use Session;
class NavMenubar extends Component
{
	public $navCategories =[];
    public function render()
    {
        $this->getMenus();
        return view('livewire.template.nav-menubar');
    }



    public function getMenus($value='')
    { 
        Session::forget('navCategories');
        $navCategories = categoryNav();
        Session::put('navCategories', $navCategories);
        $this->navCategories = $navCategories;
    } 

    
}
