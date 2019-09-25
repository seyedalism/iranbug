<?php use App\Core\Controller;

use App\Core\{Bcrypt,Auth};

class AdminController extends Controller
{
	public function show()
	{
	    Auth::redirect('admin/home','admin');
	    return view('admin.login');
    }

    public function login()
	{
	    $post = $_POST;
	    Auth::adminLogin($post['username'],$post['password']);
        header('Location: '.url('admin/login'));
    }

    public function logout()
	{
        Auth::logout('admin');
	    header('Location: '.url('admin/login'));
    }

    public function home()
    {
        Auth::redirectToLogin('admin/login','admin');
        return view('admin.dashboard');
    }
}