<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerritorialLocations extends Model
{
	use HasFactory;
	/** 
	 * nameTable 
	 */
	protected $table = 'territorial_locations';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 
		'name', 
		'code_iso',
		'type_location_id',
		'territorial_locations_parent_id',
	];
}
