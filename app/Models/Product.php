<?php
namespace App\Models;
use App\Core\Model as Model;

class Product extends Model
{
    protected $fields  = [
        'id'           => 0,
        'name'         => null,
        'price'        => null,
        'small_detail' => null,
        'main_detail'  => null,
        'img'          => null,
        'title'        => null,
        'labels'       => null,
        'res'          => null,
        'category'     => null
    ];
    protected $rules   = [
        'name'         => 'required|alpha_spaces',
        'price'        => 'required|numeric',
        'small_detail' => 'required|alpha_spaces',
        'main_detail'  => 'required|alpha_spaces',
        'title'        => 'required|alpha_spaces',
        'labels'       => 'required|alpha_spaces',
        'img'          => 'required|uploaded_file|max:500K|mimes:jpeg,png,jpg',
        'category'     => 'required|numeric'
    ];
    protected $guarded = ['id'];
    protected $table = "foods";
}