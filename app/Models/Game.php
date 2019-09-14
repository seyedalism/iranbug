<?php namespace App\Models;

use App\Core\Model as Model;

class Game extends Model
{
	protected $fields = [
		'id' => 0,
		'file' => null ,
		'status' => 0 ,
		'name' => null ,
		'userid' => -1 ,
		'poster' => null ,
		'part' => 0 ,
		]
;
	protected $guarded = ['id'];
}