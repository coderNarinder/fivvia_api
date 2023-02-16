<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VendorSlot extends Model
{
    protected $fillable = ['vendor_id','category_id','geo_id','start_time','end_time','dine_in','takeaway','delivery'];

    public function day(){
        $client = Client::first();
        $mytime = Carbon::now()->setTimezone($client->timezone_data->timezone);
        return $this->hasMany('App\Models\SlotDay', 'slot_id', 'id')->where('day', $mytime->dayOfWeek+1); 
    }

    public function dayOne(){
        $client = Client::first();
        $mytime = Carbon::now()->setTimezone($client->timezone_data->timezone);
        return $this->hasOne('App\Models\SlotDay', 'slot_id', 'id')->where('day', $mytime->dayOfWeek+1); 
    }

    public function days(){
        return $this->hasOne('App\Models\SlotDay', 'slot_id', 'id'); 
    }
    public function deleteVendorSlots($vendor_id)
    {
        return $this->where('vendor_id',$vendor_id)->delete();
    }

}
