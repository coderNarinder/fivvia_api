<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function state($value='')
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function country($value='')
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}
