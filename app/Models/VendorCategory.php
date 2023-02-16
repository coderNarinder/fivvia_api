<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    use HasFactory;
    protected $fillable = ['status','vendor_id', 'category_id','client_id'];
    
    public function category(){
  	    return $this->hasOne('App\Models\Category', 'id', 'category_id'); 
  	}
  	public function addVendorCategory($vendor_id,$category_id)
  	{
  		return self::updateOrCreate([
  			'vendor_id' => $vendor_id,
  			'category_id' => $category_id
  		],[]);
  	}


    public function vendor($value='')
    {
     return $this->belongsTo('App\Models\Vendor');
    }

}
