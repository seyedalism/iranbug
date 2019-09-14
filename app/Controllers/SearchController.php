<?php use App\Core\Controller;

use App\Models\{Product};

class SearchController extends Controller
{
	public function search($text)
	{
	    $text = rtrim($text,'?');
	    $products = Product::search($text,'title');
	    return view('search',compact('products'));
    }
}