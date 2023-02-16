<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorMedia extends Model
{
	protected $table = 'vendor_media';
  protected $fillable = ['media_type','vendor_id','path'];

    public function getPathAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
       
      $img = str_replace(' ', '', $img);
      $ex = checkImageExtension($img);

      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      if (substr($img, 0, 7) == "http://" || substr($img, 0, 8) == "https://"){
        $values['image_path'] =  urlOfImage($img);//url($img);
        // $values['image_path'] = \Config::get('app.IMG_URL2').'/'.$img;
      } else {
        $values['image_path'] = urlOfImage($img);//url($img);// \Storage::disk('public')->path($img);
        // $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).$ex;
      }
      $values['image_fit'] = \Config::get('app.FIT_URl');
      return $values;
    }
}
