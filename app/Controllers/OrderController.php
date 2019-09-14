<?php

use App\Core\Auth;
use App\Core\Controller;

use App\Models\{Product,Category,Res,Slide,Special,Img,ResReserve,Reserve};

class OrderController extends Controller
{
    public function show()
    {
        $home = 1;
        $products = Product::findByRes(-1);
        $products = array_slice($products,0,6);
        $res = Res::findById(-1)[0];
        $slides = Slide::findByRes(-1);
        $special = Special::findByResid(-1);
        $imgs = [];
        if(!empty($special)) {
            $special = $special[0];
            $imgs = Img::findBySpecialid($special->id);
        }
        return view('order',compact('imgs','special','slides','home','products','res'));
    }

    public function ajax($id)
    {
        $products = null;
        $product  = Product::findByCategory($id);
        foreach ($product as $p)
        {
            $el['name']  = $p->name;
            $el['price'] = $p->price;
            $el['img']   = asset('upload/'.$p->img);
            $el['id']    = $p->id;
            $el['desc']  = $p->small_detail;
            $products[]  = $el;
        }
        echo json_encode($products);
    }

    public function sitSetting()
    {
        Auth::redirectToLogin('admin/login','admin');
        $opt = ResReserve::findByResid('-1');
        $opt = (empty($opt)) ? [] : $opt;
        return view('admin.sitSetting',compact('opt'));
    }

    public function addSit()
    {
        Auth::redirectToLogin('admin/login','admin');
        $sit = new ResReserve();
        $sit->capacity = $_POST['count'];
        $sit->price = $_POST['price'];
        $sit->resid = '-1';
        $sit->save();
        header('Location: ' . url('admin/sit/setting'));
    }

    public function rmvSit($id)
    {
        Auth::redirectToLogin('admin/login','admin');
        $sit = ResReserve::findById($id)[0];
        $sit->delete();
        header('Location: '.url('admin/sit/setting'));
    }

    public function showReserved()
    {
        Auth::redirectToLogin('admin/login','admin');
        $reserve = Reserve::where('res','=','-1','time_s|ASC');
        $reserve = ($reserve) ? $reserve : [];
        return view('admin.manageReserved',compact('reserve'));
    }
}