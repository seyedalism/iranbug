<?php namespace App\Core;

class View
{
	public static function render($view,$data)
	{
		$cache = ROOTPATH.DIRSEP.'app'.DIRSEP.'Core'.DIRSEP.'cache';
		$blade = 
		new \eftec\bladeone\BladeOne(VIEWSPATH,$cache,
			\eftec\bladeone\BladeOne::MODE_DEBUG);
		echo $blade->run($view,$data);
	}
}