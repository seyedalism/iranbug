<?php namespace App\Models;

use App\Core\Model as Model;

class Advertise extends Model
{
	protected $table = 'advertise';
    protected $fields  = ['id' => 0,'text' => null,'time' => null,'clicks' => 0,'url' => '','img' => '','state' => 0];
    protected $guarded = ['id'];
}