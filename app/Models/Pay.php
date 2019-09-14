<?php namespace App\Models;

use App\Core\Model as Model;

class Pay extends Model
{
    protected $guarded = ['id'];
    protected $fields  = ['id' => 0,'userid' => 0,'trace' => null,'transid' => null,'factor' => null,'products' => null];
}
//trace پیگیری بانک
//transid ایدی درگاه
//factor شماره فاکتور
//products