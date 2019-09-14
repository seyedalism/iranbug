<?php namespace App\Models;

use App\Core\Model as Model;

class Slide extends Model
{
    protected $fields  = ['id' => 0,'img' => null,'category' => null,'res' => null];
    protected $guarded = ['id'];
    protected $rules   = ['img' => 'required|uploaded_file|max:500K|mimes:jpeg,png,jpg','category' => 'required|numeric'];
}