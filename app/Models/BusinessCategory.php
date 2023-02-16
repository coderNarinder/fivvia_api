<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    use HasFactory;


    public function BusinessTypes($value='')
    {
        return $this->hasMany('App\Models\BusinessType','business_category_id');
    }
}
