<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProductPrescription extends Model
{
    use HasFactory;

    public function getPrescriptionAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $ex = checkImageExtension($img);
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Storage::disk('s3')->url($img).$ex;
      $values['image_fit'] = \Config::get('app.FIT_URl');

      return $values;
    }
}
