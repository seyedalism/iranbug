<?php use App\Core\Controller;

use App\Core\{Bcrypt,Auth};
use App\Models\{Special};

class SpecialController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::redirectToLogin('admin/login-pof','admin');
    }

    public function show()
	{
        $boxes = Special::all();
        return view('admin.specialBox',compact('boxes'));
    }

    public function add()
	{
        $post = \Input::post();
	    $box = new Special;
	    $box->name = $post['name'];
	    $box->save();
        header('Location: '.url('admin/special-box'));
    }

    public function delete($id)
	{
        $box = Special::findById($id)[0];
	    $box->delete();
        header('Location: '.url('admin/special-box'));
    }
}