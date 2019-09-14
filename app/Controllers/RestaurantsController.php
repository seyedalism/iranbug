<?php
use App\Core\Controller;

use App\Models\{Product,Category,Res};

class RestaurantsController extends Controller
{
    public function home()
    {
        $home = 1;
        return view('res.restaurantsPage',compact('home'));

    }
    public function show($id=1)
    {
        $home = 1;
        $products = Product::findByRes($id);
        $products = array_slice($products,0,6);
        $res = Res::findById($id)[0];
        $cats = Category::findByRes($id);
        if(empty($res))
            header('Location: '.url());
        return view('restaurants',compact('id','cats','home','products','res'));
    }

    public function ajax()
    {
        $cat = trim(explode('|',$_POST['a'])[1]);
        $id  = trim(explode('|',$_POST['a'])[0]);
        $products = null;
        $product  = Product::findByRes($id);
        foreach ($product as $p)
        {
            if($p->category == $cat)
            {
                $el['name']  = $p->name;
                $el['price'] = $p->price;
                $el['img']   = asset('upload/'.$p->img);
                $el['id']    = url('food/').$p->id;
                $el['desc']  = $p->small_detail;
                $products[]  = $el;
            }
        }

        echo json_encode($products);
    }

}