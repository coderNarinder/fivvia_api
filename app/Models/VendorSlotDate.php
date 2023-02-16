<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorSlotDate extends Model
{
    protected $fillable = ['vendor_id','category_id','start_time','end_time','specific_date','working_today','dine_in','takeaway','delivery'];

    public function deleteVendorSlotDates($vendor_id)
    {
    	return $this->where('vendor_id',$vendor_id)->delete();
    }
}
