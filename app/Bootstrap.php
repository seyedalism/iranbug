<?php namespace App;

class Bootstrap
{
	public function run()
	{
		include('Core/conf.php');
		include('Core/helpers.php');
		include('Core/Session.php');
		include('Core/Cookie.php');
		include ROOTPATH . '/routes.php';
		Core\Router::route();
	}
}