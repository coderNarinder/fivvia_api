<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupAddress extends Model
{
	
	public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
}
