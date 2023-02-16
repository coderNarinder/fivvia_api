<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    public function states($value='')
    {
        return $this->hasMany(State::class,'country_id')->where('parent',0);
    }
}
