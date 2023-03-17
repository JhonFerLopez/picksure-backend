<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imageproduct extends Model
{
	use HasFactory;
	public function language()
	{
		return $this->belongsToMany('App\Models\Language','texts_imageproducts', 'language_id','imageproduct_id')->withPivot('id','title','description');
	} 
	   
}
