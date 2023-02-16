<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class ProductTranslation extends Model{
	protected $fillable = ['title','body_html','meta_title','client_id','meta_keyword','meta_description','product_id','language_id'];
}