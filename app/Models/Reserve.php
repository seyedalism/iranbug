<?php
namespace App\Models;
use App\Core\Model as Model;

class Reserve extends Model
{
	protected $fields  = [
        'id'=> 0,
        'time_s'=>0,
        'time_e'=>0,
        'res'=>null,
        'user_id'=>null,
        'detail'=>null,
        'res_reserve_ids'=>null,
        'name'=>null,
        'price'=>null,
        'phone'=>null
        ];
	protected $guarded = ['id'];
	protected $rules   = [
	    'time_s'    =>'required',
        'time_e'    =>'required|integer',
        'user_id'   =>'numeric',
        'phone'     =>'required|numeric',
        'price'     =>'numeric',
        'res_reserve_ids'     =>'required|alpha_num',
        'detail'    =>'alpha_num',
        'name'      =>'required|alpha_spaces',
    ];
}