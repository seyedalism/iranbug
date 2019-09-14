<?php

class Session
{

    public static function init()
    {
        if(!session_id())
            session_start();
    }

    public static function get($var)
    {
        self::init();
        if(isset($_SESSION[$var]))
        {
            return crypter($_SESSION[$var],'decrypt');
        }
        return null;
    }

    public static function set($var, $content)
    {
        self::init();
        $content = crypter($content,'encrypt');
        $_SESSION[$var] = $content;
    }

    public static function clear()
    {
        unset($_SESSION);
        session_destroy();
    }

    public static function remove($var)
    {
        if(isset($_SESSION[$var]))
            unset($_SESSION[$var]);
    }

    public static function csrf_token(){
        self::init();
        $token = md5(uniqid(rand()));
        $all = self::get('csrf_token');
        $all[] = $token;
        self::set('csrf_token',$all);
        return $token;
    }

}