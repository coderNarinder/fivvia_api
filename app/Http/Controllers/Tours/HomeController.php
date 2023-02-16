<?php

namespace App\Http\Controllers\Tours;
use Session;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Tours\BaseController;
use App\Models\{Currency, Banner};
class HomeController extends BaseController
{
   
#---------------------------------------------------------------------
# Index Function
#---------------------------------------------------------------------

public function index(Request $request)
{
    $banners = Banner::where('status', 1)->where('validity_on', 1)
            ->where(function ($q) {
                $q->whereNull('start_date_time')->orWhere(function ($q2) {
                    $q2->whereDate('start_date_time', '<=', Carbon::now())
                        ->whereDate('end_date_time', '>=', Carbon::now());
                });
            })
            ->where('client_id',$this->obj->client_id)
            ->orderBy('sorting', 'asc')
            ->with('category')
            ->with('vendor')
            ->get();
	 return view($this->root.'home');
}
#---------------------------------------------------------------------
# Index Function
#---------------------------------------------------------------------


}
