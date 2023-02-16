<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPackages extends Model
{
    use HasFactory;
    
    public function features()
    {
    	return $this->hasMany('App\Models\SubscriptionPackageFeature','package_id');
    }

    public function getTemplates()
    {
    	 $templates = !empty($this->templates) ? json_decode($this->templates) : [];
    	return $template_records = \App\Models\Template::whereIn('temp_id',$templates)->where('for',1)->get();
    }

    public function packagePlan()
    {
       $ids = [];
       array_push($ids, $this->id);
       if($this->related_id > 0){
        array_push($ids, $this->related_id);
       }

       return $ids;
    }
}
