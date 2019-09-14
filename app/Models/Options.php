<?php
namespace App\Models;
use App\Core\Model as Model;

class Options extends Model
{
    protected $fields  = ['id' => 0,'title' => null,'main' => null];
//    protected $rules   = ['name' => 'required'];
    protected $guarded = [];
    protected $table = 'options';
}