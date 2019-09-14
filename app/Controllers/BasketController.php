<?php use App\Core\Controller;

use App\Models\{Product,Pay};
use App\Core\{Auth,Dargah};

class BasketController extends Controller
{
	public function show()
	{
        $basket = unserialize(\Cookie::get('basket'),["allowed_classes" => false]);
        if($basket == null) $basket = [];
        return view('user.basket',compact('basket'));
    }

    public function add($id)
	{
	    $id = (int) $id;
        $counter = $_GET['count'];
	    if(is_integer($id))
	    {
            $count = (\Cookie::get('count') != null) ? \Cookie::get('count') : 0;
            $basket = unserialize(\Cookie::get('basket'),["allowed_classes" => false]);
            $product = Product::findById($id)[0];

            if(isset($basket[$id])) {
                $basket[$id]['count'] += $counter;
            }
            else {
                $basket[$id] = ['res' => $product->res,'price' => $product->price,'name' => $product->name,'img' => $product->img,'count' => $counter];
                $count++;
            }
            \Cookie::set('basket',serialize($basket),60*60);
            \Cookie::set('count',$count,60*60);
        }
        header('Location: '.rtrim($_SERVER['HTTP_REFERER'],'/alert').'/alert');
    }

    public function remove($id)
    {
        $id = (int) $id;
        if (is_integer($id)) {
            $basket = unserialize(\Cookie::get('basket'),["allowed_classes" => false]);
            if (isset($basket[$id])) {
                unset($basket[$id]);
                \Cookie::set('basket',serialize($basket),60*60);
                $count = \Cookie::get('count');
                $count--;
                \Cookie::set('count',$count,60*60);
            }
        }
        header('Location: ' . url('basket'));
    }

    public function checkout()
	{
	    Auth::redirectToLogin('login');
	    $user = Auth::returnUser();
	    if(!$user->complete)
        {
            header('location: '.url('edit'));
        }
	    $basket = \Cookie::get('basket');
        if($basket)
        {
            $basket = unserialize($basket,["allowed_classes" => false]);
            $jam = 0;
            foreach ($basket as $pid => $value)
            {
                $p = Product::findById($pid);
                if(isset($p[0]))
                    $jam += $p[0]->price * (1 - $p[0]->off/100);
            }
            Dargah::transaction($jam,url('reply'));
        }
        else
            header('location: '.url());
    }

    public function reply()
    {
        Auth::redirectToLogin('login');
        $user = Auth::returnUser();
        if(Dargah::end())
        {
            $res = Dargah::$res;
            if(empty(Pay::findByTransid($res->transId)))
            {
                $pay           = new Pay;
                $pay->userid   = $user->id;
                $pay->products = \Cookie::get('basket');
                $pay->transid  = $res->transId;
                $pay->factor   = $this->CreateFactor();
                $pay->trace    = $res->traceNumber;
                $pay->time     = time();
                if($pay->save())
                {
                    $message  = 'محصول با موفقیت خرید شد.';
                    $message .= '<br>';
                    $message .= 'شماره فاکتور : ';
                    $message .= $pay->factor;
                    $message .= '<br>';
                    $message .= 'شماره پیگیری بانک : ';
                    $message .= $pay->trace;
                    $message .= '<br>';
                    \Cookie::remove('basket');
                    \Cookie::remove('count');
                }
            }
            else
                $message = 'شما پرداخت را با موفقیت انجام داده اید.';
        }
        else
            $message = 'مشکلی در پرداخت به وجود آمده است.درصورت کسر وجه تا 1 ساعت مبلغ به حسابتان باز خواهد گشت.';
        return view('user.complete',compact('message'));
    }

    public function CreateFactor()
    {
        $f = Pay::all(0,2,'factor|DESC');
        if(!empty($f))
            $factor = $f[0]->factor;
        else
            $factor = 1000000;
        return $factor + 1;
    }

    public function status()
    {
        Auth::redirectToLogin('login');
        $user = Auth::returnUser();
        $pays = Pay::findByUserid($user->id);
        return view('user.status',compact('pays'));
    }
}