<?php use App\Core\Controller;

use \Gregwar\Captcha\CaptchaBuilder;
use \Gregwar\Captcha\PhraseBuilder;
use App\Models\{User,Developer,Res};
use App\Core\{Auth,Bcrypt};

class UserController extends Controller
{
	public function show()
	{
        Auth::redirect(url());
        return view('user.register');
	}

    public function register()
    {    
        Auth::redirect(url());
        $res = false;
        $errors = [];
        $post = $_POST;
        $user = null;
            if(empty(User::findByUsername($post['username'])[0]))
            {
                $user = new User;
                $user->username = $post['username'];
                $user->password = Bcrypt::hashPassword($post['password']);
                $user->email = $post['email'];
                $user->phone = $post['phone'];
                $user->fname = $post['fName'];
                $user->lname = $post['lName'];
                $res = $user->save();
                $errors = $user->errors->firstOfAll();
            }
            else
                $errors['username'] = "نام کاربری وارد شده تکراری می باشد.";

        if($res)
        {
            Auth::login($post['username'],$post['password']);
            header('Location: '.url());
        }
        else
        {
            return view('user.register',compact('errors','user'));
        }
        
    }

    public function login()
	{
        Auth::redirect('basket');
        $post = $_POST;
        if(Auth::login($post['username'],$post['password']))
        {
            header('Location: '.url('basket'));
        } else {
            $errors['login'] = 'اطلاعات وارد شده صحیح نمی باشد.';
            return view('user.login',compact('errors'));
        }
	}

    public function showLogin()
    {
        Auth::redirect('basket');
        return view('user.login');
    }

    public function takmil()
    {
        Auth::redirectToLogin('login');
        $user = Auth::returnUser();
        return view('user.edit',compact('user'));
    }

    public function takmiler()
    {
        Auth::redirectToLogin('login');
        $user = Auth::returnUser();
        $post = $_POST;
        $errors = [];
        $user->email   = (empty($post['email'])) ? null : $post['email'];
        $user->fname   = (empty($post['fName'])) ? null : $post['fName'];
        $user->phone   = (empty($post['phone'])) ? null : $post['phone'];
        $user->lname   = (empty($post['lName'])) ? null : $post['lName'];
        $user->address = (empty($post['address'])) ? null : $post['address'];
        if(!($user->fname == null || $user->lname == null  || $user->address == null || $user->phone == null)) $user->complete = 1; else $user->complete = 0;
            $user->save();

        if($user->errors) {
            $errors = $user->errors->firstOfAll();
            return view('edit', compact('user', 'errors'));
        }
        header('Location: '.url('basket'));
    }

    public function logout()
    {
        Auth::logout();
        header('Location: '.url());
    }

    //admin user control
    public function showUsers()
    {
        Auth::redirectToLogin('admin/login','admin');
        $users = User::all();
        return view('admin.manageUsers',compact('users'));
    }

    public function showSpecials()
    {
        Auth::redirectToLogin('admin/login','admin');
        $users = Res::all();
        $s = true;
        return view('admin.manageUsers',compact('users','s'));
    }

    public function showDevs()
    {
        Auth::redirectToLogin('admin/login','admin');
        $users = Developer::all();
        $dev = true;
        return view('admin.manageUsers',compact('users','dev'));
    }

    public function showUser($id)
    {
        Auth::redirectToLogin('admin/login','admin');
        $user = User::findById($id)[0];

        return view('admin.showUser',compact('user'));
    }

    public function removeUser($type,$id)
    {
        Auth::redirectToLogin('admin/login','admin');
        if($type == "s") {
            $user = Res::findById($id)[0];
            $user->delete();
            header('Location: '.url('admin/manage-special-users'));
        }
        else if($type == "d") {
            $user = Developer::findById($id)[0];
            $user->delete();
            header('Location: '.url('admin/manage-dev-users'));
        }
        else {
            $user = User::findById($id)[0];
            $user->delete();
            header('Location: '.url('admin/manage-users'));
        }
    }

    public function promoteToD($id)
    {
        Auth::redirectToLogin('admin/login', 'admin');
        $user = User::findById($id)[0];
        if (empty(Developer::findByUsername($user->username))) {
            $dev = new Developer;
            $dev->username = $user->username;
            $dev->password = $user->password;
            $dev->email = $user->email;
            $dev->phone = $user->phone;
            $dev->fname = $user->fname;
            $dev->lname = $user->lname;
            $dev->save();
        }
        header('Location: '.url('admin/manage-users'));
    }

    public function promoteToS($id)
    {
        Auth::redirectToLogin('admin/login','admin');
        $user = User::findById($id)[0];
        if (empty(Res::findByUsername($user->username))) {
            $dev = new Res;
            $dev->username = $user->username;
            $dev->password = $user->password;
            $dev->save();
        }
        header('Location: '.url('admin/manage-users'));
    }

}