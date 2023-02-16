<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusinessCategory;
use App\Models\Client;
class BusinessType extends Model
{
    
   public function category($value='')
   {
       return $this->belongsTo(BusinessCategory::class,'business_category_id');
   } 

   public function client($value='')
   {
       return $this->belongsTo(Client::class,'client_id');
   }

}
