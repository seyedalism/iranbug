<?php namespace App\Core;

use App\Core\Bcrypt;
use App\Models\{User,Res,Developer};
class Auth
{
    public static function check($key = 'User')
    {
        $auth = \Session::get($key);
        if(isset($auth))
        {
            return true;
        }
        return false;
    }

    public static function login($username,$password,$obj = 'User',$remember = false)
    {
        $name = $obj;
        $obj = 'App\Models\\'.$obj;
        $user = $obj::findByUsername($username);
        if(empty($user))
            return false;
        else
            $user = $user[0];
        if(Bcrypt::checkPassword($password,$user->password))
        {
            \Session::set($name,$username);
            \Session::set('id',$user->id);
            return true;
        }

        return false;
    }

    public static function adminLogin($username,$password)
    {
        if(Bcrypt::checkPassword($password,ADMIN_PASSWORD) && ADMIN_USERNAME == $username)
        {
            \Session::set('admin',$username);
            return true;
        }

        return false;
    }

    public static function logout($key = 'User')
    {
        if(self::check($key))
        {
            \Session::remove($key);
            \Session::remove('id');
            \Cookie::remove($key);
            return true;
        }
        return false;
    }

    public static function redirectToLogin($url,$key = 'User')
    {
        if(!self::check($key))
        {
            header('Location: '.url($url));
            exit();
        }
    }

    public static function redirect($url,$key = 'User')
    {
        if(self::check($key))
        {
            header('Location: '.url($url));
            exit();
        }
    }

    public static function returnUser($key = 'User')
    {
        if(self::check($key))
        {
            $model = "App\Models\\".$key;
            return $model::findByUsername(\Session::get($key))[0];
        }
    }

}