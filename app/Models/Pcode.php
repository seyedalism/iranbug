<?php namespace App\Models;

use App\Core\Model as Model;

class Pcode extends Model
{
	protected $fields = [
		'id' => 0 ,
		'user_id' => -1 ,
		'p_id' => -1 ,
		'game_id' => null ,
		'code' => null ,
		'part' => 1 ,
	];
	protected $guarded = ['id'];
}