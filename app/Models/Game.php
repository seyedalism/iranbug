<?php namespace App\Models;

use App\Core\Model as Model;

class Game extends Model
{
    protected $fields  = ['id' => 0,'code' => null,'valid' => 0];
    protected $guarded = ['id'];
}