<?php
namespace App\Models;
use App\Core\Model as Model;

class Res extends Model
{
	protected $fields  = [
	    'id' => 0,
        'username' => null ,
        'password' => null,
        'name' => 0,
        'park' => 0,
        'wifi' => 0,
        'game' => 0,
        'child_bench' => 0,
        'music' => 0,
        'kart' => 0,
        'img' => 0,
        'details' => 0,
        'time1' => 0,
        'time2' => 0,
        'delivery' => 0
        ];
	protected $guarded = ['id'];
	protected $rules   = ['password' => 'min:8','email' => 'email'];
}