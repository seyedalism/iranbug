<?php namespace App\Core;

class Db
{
	public static $db;

	public static function init()
	{
		if(!self::$db)
		{
			$username = USERNAME;
			$password = PASSWORD;
			$host = HOST;
			$database = DATABASE;
			try 
			{
		    self::$db = new \PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
		    self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			}
			catch(\PDOException $e)
			{
	    	showSystemError("Connection failed: ",$e->getMessage());
			}
		}
	}
}