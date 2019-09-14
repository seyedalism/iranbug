<?php namespace App\Models;

use App\Core\Model as Model;

class Comment extends Model
{
	protected $table = "comments";
    protected $fields  = [
    	'id'         => 0,
    	'name'       => null,
    	'email'      => null,
    	'comment'    => null,
    	'product_id' => null,
    	'time'       => null,
    	'valid'      => 0
    ];

    protected $rules  = [
    	'name'       => 'required|max:40',
    	'email'      => 'email|required|max:40',
        'comment'    => 'max:800',
    ];

    protected $guarded = ['id'];
}