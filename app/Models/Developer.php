<?php
namespace App\Models;
use App\Core\Model as Model;

class Developer extends Model
{
	protected $fields  = [
	    'id' => 0,
        'username' => null ,
        'password' => null,
        'fname' => null,
        'email' => null,
        'phone' => null,
        'lname' => null
                         ,'email' => null,'phone' => null,];
	protected $guarded = ['id'];
//	protected $rules   = ['password' => 'min:8','email' => 'email'];
}