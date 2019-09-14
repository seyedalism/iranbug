<?php use App\Core\Controller;
use App\Models\{Product,Pay,user};
use App\Core\Auth;
class PayController extends Controller
{
    public function __construct()
    {
        Auth::redirectToLogin('admin/login','admin');
    }
    public function show()
    {
        $allPay=Pay::all();
        return view('admin.payList',compact('allPay'));
    }
    public function removePay($id){
        $itemPay=Pay::findById($id)[0];
        $itemPay->delete();
        header('Location: '.url('admin/manage-pays'));
    }
    public function detailPay($id)
    {
        $product=Pay::findById($id)[0];
        return view('admin.detailPayList',compact('product'));
    }
}