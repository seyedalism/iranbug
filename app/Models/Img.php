<?php namespace App\Models;

use App\Core\Model as Model;

class Img extends Model
{
    protected $fields  = ['id' => 0,'specialid' => null,'img' => null];
    protected $guarded = ['id'];
    protected $rules   = [
        'img'          => 'required|uploaded_file|max:500K|mimes:jpeg,png,jpg',
    ];
}