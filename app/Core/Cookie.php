<?php

class Cookie
{

    public static function get($var)
    {
        if(isset($_COOKIE[$var]))
        {
            return crypter($_COOKIE[$var],'decrypt');
        }
        return null;
    }

    public static function set($var,$content,$time)
    {
        $content = crypter($content,'encrypt');
        setcookie($var,$content,time() + $time,'/');
    }

    public static function clear()
    {
        foreach ($_COOKIE as $key => $value) {
            setcookie($key , false , time() - 10000,'/');
        }
        unset($_COOKIE);
    }

    public static function remove($var)
    {
        setcookie($var , false , time() - 10000,'/');
    }

}