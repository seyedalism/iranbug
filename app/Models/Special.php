<?php namespace App\Models;

use App\Core\Model as Model;

class Special extends Model
{
    protected $fields  = ['id' => 0,'title' => null,'main' => null,'resid' => null];
    protected $guarded = ['id'];
    protected $rules   = [
        'main'         => 'required|alpha_spaces',
        'title'        => 'required|alpha_spaces',
    ];
}