<?php namespace App\Core;

class Input
{
    private static $post;
    private static $files;

    public static function init()
    {
        self::$post = $_POST;
        self::$files = $_FILES;
        unset($_POST);
        unset($_FILES);
    }

    public static function getAllPost()
    {
        return self::$post;
    }

    public static function getAllFiles()
    {
        return self::$files;
    }
}