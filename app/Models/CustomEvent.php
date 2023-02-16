<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomEvent extends Model
{

  public function category(){
      return $this->hasOne('App\Models\Category', 'id', 'redirect_category_id');
    }
    public function vendor(){
      return $this->hasOne('App\Models\Vendor', 'id', 'redirect_vendor_id');
    }


}