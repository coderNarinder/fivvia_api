<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBusinessType extends Model
{
    use HasFactory;


    public function businessType()
    {
      return $this->belongsTo('App\Models\BusinessType','business_type_id');
    }
}
