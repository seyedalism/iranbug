<?php
namespace App\Models;
use App\Core\Model as Model;

class Sub extends Model
{
    protected $fields  = ['id' => 0,'name' => null,'main'=>null];
    protected $rules   = ['name' => 'required','main' =>'required'];
    protected $guarded = ['id'];
}