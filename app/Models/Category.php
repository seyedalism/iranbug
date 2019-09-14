<?php
namespace App\Models;
use App\Core\Model as Model;

class Category extends Model
{
    protected $fields  = ['id' => 0,'name' => null,'res' => null];
    protected $rules   = ['name' => 'required|alpha_spaces'];
    protected $guarded = ['id'];
}