<?php
use App\Core\Controller;

use App\Models\{Product,Category,Sub};

class FoodController extends Controller
{
    public function showAll($page = 0)
    {
        $page = (int) $page;
        $products = Product::all($page*9,$page*9+9);
        $empty = (empty($products)) ? 1 : 0;
        return view('foods',compact('page','products','empty'));
    }

    public function show($id,$alert = null)
    {
        $id = (int) $id;
        $product = Product::findById($id)[0];
        return view('food_detail',compact('product','alert'));
    }

}