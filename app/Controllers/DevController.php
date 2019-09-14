<?php

use App\Core\Auth;
use App\Core\Controller;

use App\Models\{Developer};

class DevController extends Controller
{

    public function show()
    {
        Auth::redirect('dev/dashboard','Developer');
        return view('dev.login');
    }

    public function login()
    {
        Auth::redirect('dev/dashboard','Developer');
        $post = $_POST;
        if(Auth::login($post['username'],$post['password'],'Developer'))
        {
            header('Location: '.url('dev/dashboard'));
        } else {
            header('Location: '.url('dev/login'));
        }
    }

    public function home()
    {
        Auth::redirectToLogin('dev/login','Developer');
        return view('dev.dashboard');
    }

    public function logout()
    {
        Auth::logout('Developer');
        header('Location: '.url('dev/login'));
    }

}