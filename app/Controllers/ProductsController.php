<?php

use App\Core\Auth;
use App\Core\Controller;

use App\Models\{Product,Category};

class ProductsController extends Controller
{
    public function __construct()
    {
        Auth::redirectToLogin('admin/login','admin');
    }

    public function show($id = -1)
    {
        $html = $this->getCategories();
        if($id != -1)
        {
            $product = Product::findById($id)[0];
            return view('admin.editProduct',compact('html','product'));
        }

        return view('admin.addProduct',compact('html'));
    }

    public function add()
    {
        $html = $this->getCategories();

        $post = $_POST;

        $product = new Product;
        foreach ($post as $key => $value)
        {
            $product->$key = $value;
        }
        $product->res = '-1';
        $product->save();

        $errors = $product->errors->firstOfAll();
        return view('admin.addProduct',compact('html','errors'));
    }

    public function deleteProduct($id)
    {
        $refer = $_SERVER['HTTP_REFERER'];
        $p = Product::findById($id)[0];
        deleteFile('upload/'.$p->img);
        $p->delete();
        header('location: '.$refer);
    }

    public function update($id)
    {
        $post = $_POST;

        $html = $this->getCategories();

        $product = Product::findById($id)[0];
        foreach ($post as $key => $value)
        {
            if(!empty($value))
                $product->$key = $value;
        }
        $product->save();

        return view('admin.editProduct',compact('html','product'));
    }

    public function manageProducts($page = 0)
    {
        $zarib = 30;
        $start = $page * $zarib;
        $end = $start + $zarib;
        $products = Product::findByRes('-1');
        $i = 0;
        $pages = ["pre"=> $page - 1 , "next" => $page + 1];
        return view('admin.showProduct',compact('products','start','i','pages'));
    }

    public function getCategories()
    {
        $html = '<div class="p-1 lister">';
        $modals = "";
        $categories = Category::findByRes('-1');
        foreach ($categories as $category)
        {
            $html .= '<h5><span>+ </span> <input type="radio" name="category" value="'.$category->id.'"> <span class="text-info">'.$category->name.'</span><span class="col-5"></span>';
            echo "<br>";
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }

}