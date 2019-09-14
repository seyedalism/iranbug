<?php
namespace App\Models;
use App\Core\Model as Model;

class ResReserve extends Model
{
	protected $fields  = [
        'id' => 0,
        'capacity' => 0 ,
        'resid' => 0,
        'price' =>0
        ];
	protected $guarded = ['id'];
	protected $rules   = ['capacity' => 'required|numeric','resid' => 'numeric', 'price|required'=>'numeric'];
}