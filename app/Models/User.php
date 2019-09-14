<?php
namespace App\Models;
use App\Core\Model as Model;

class User extends Model
{
	protected $fields  = [
	    'id' => 0,
        'username' => null ,
        'password' => null,
        'fname' => null,
        'lname' => null,
        'email' => null,
        'phone' => null,
        'address' => null,
        'complete' => 0,
        ];
	protected $guarded = ['id'];
	protected $rules   = ['password' => 'min:8','email' => 'email','phone' => 'numeric'];
	protected $table   = "client";
}