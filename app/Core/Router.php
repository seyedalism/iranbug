<?php namespace App\Core;

class Router
{
	private static $routes = [];
	// private static $defaultConfig = ['method' => 'GET'];

	public static function get($route,$action)
	{
		self::register($route,$action,['method' => ['GET']]);
	}

	public static function post($route,$action)
	{
		self::register($route,$action,['method' => ['POST']]);
	}

	public static function put($route,$action)
	{
		self::register($route,$action,['method' => ['PUT']]);
	}

	public static function patch($route,$action)
	{
		self::register($route,$action,['method' => ['PATCH']]);
	}

	public static function delete($route,$action)
	{
		self::register($route,$action,['method' => ['DELETE']]);
	}

	public static function any($route,$action)
	{
		self::register($route,$action,['method' => ['GET','POST','PUT','PATCH','DELETE']]);
	}

	private static function arrayToUpper(&$array)
	{
		foreach ($array as $key => $value) {
			$array[$key] = strtoupper($value);
		}
	}

	private static function convertToArray(&$var)
	{
		$var = (is_array($var)) ? $var : [$var];
	} 

	public static function getUrl()
	{
        if(isset($_GET['url']))
        {
            $url = trim($_GET['url']);
            $url = htmlspecialchars($url);
            $url = rtrim($_GET['url'],'/');
            $url = ($url == "") ? '/' : $url;
            return $url;
		}
	}

	// private static function getConfig($config)
	// {
	// 	$ret = self::$defaultConfig;
	// 	foreach ($config as $key => $value) {
	// 		$ret[$key] = $value;
	// 	}
	// 	return $ret;
	// }

	private static function removeOptional($params)
	{
        $param = [];
        foreach ($params as $key => $value)
		{
			if($value[0] === '?')
				return $param;
			$param[] = $value;
		}
		return $param;
	}

	private static function errorPage($number)
	{
		$errorPath = VIEWSPATH . DIRSEP . 'error';
		$pagePath = $errorPath.DIRSEP.$number.'.html';
		if(is_dir($errorPath) && file_exists($pagePath))
		{
			switch ($number) {
				case 404:
					require_once $pagePath;
					break;
                case 'down':
					require_once $pagePath;
					break;
			}
		}
		else
		{
			switch ($number)
			{
				case 404:
					echo "404 page not found";
					break;
                case 'down':
					echo "Site is down";
					break;
			}
		}
		exit;
	}

	public static function register($route,$action,$config = [])
	{
		// preg_match_all('/^([^{]+)\//',$route,$matches);
		preg_match_all('/^([^{]+)/',$route,$matches);
		$routeName = isset($matches[1][0]) ? $matches[1][0] : $route;
		$routeName = (rtrim($routeName,'/') != '') ? rtrim($routeName,'/') : $routeName;
		$params = [];
		if($routeName !== $route)
		{
			preg_match_all('/\/{([^}]+)}/U',$route,$matches);
			$params = isset($matches[1][0]) ? $matches[1] : [];
		}
		// $config = self::getConfig($config);
		$config['name'] = $routeName;
		$config['action'] = $action;
		$config['params'] = $params;
		self::$routes[] = $config;
	}

	private static function checkRoute($url)
	{
		$_SERVER['REQUEST_METHOD'] = isset($_POST['_method']) ? $_POST['_method'] : 
		$_SERVER['REQUEST_METHOD'];
		foreach (self::$routes as $options)
		{
            $withOptional = count($options['params']);
            $options['params'] = self::removeOptional($options['params']);
            $paramsCount = count($options['params']);
            if(strtolower($options['name']) === strtolower(substr($url,0,strlen($options['name']))))
            {
                $exploded = explode('/',substr($url,strlen($options['name'].'/')));
                $exploded = ($exploded[0] === "") ? [] : $exploded;
                $urlParamsCount = count($exploded);
                static::convertToArray($options['method']);
                static::arrayToUpper($options['method']);
                static::convertToArray($_SERVER['REQUEST_METHOD']);
                static::arrayToUpper($_SERVER['REQUEST_METHOD']);
                if(!array_intersect($options['method'],$_SERVER['REQUEST_METHOD']))
                continue;
                if($urlParamsCount >= $paramsCount && $urlParamsCount <= $withOptional)
                {
                    foreach ($exploded as $index => $value)
                    {
                        $options['params'][$index] = $exploded[$index];
                    }
                    return $options;
				}
			}
		}	
		return false;
	}

	public static function route()
	{
	    if(DOWN)
            self::errorPage('down');

		$url = self::getUrl();
		if($route = self::checkRoute($url)) {
			if(is_callable($route['action']))
			{
				$argNum = new \ReflectionFunction($route['action']);
				$argNum = $argNum->getNumberOfRequiredParameters();
				if($argNum === count($route['params']))
					call_user_func_array($route['action'],$route['params']);
				else
				{
					self::errorPage(404);
				}
				exit;
			}
			$controller = explode('@',$route['action'])[0];
			$method = explode('@',$route['action'])[1];
			$controller = ucfirst($controller);
			$ctrlPath = ROOTPATH.DIRSEP.'app'.DIRSEP.'Controllers'.DIRSEP.$controller.'.php';
			if(file_exists($ctrlPath))
			{
				require_once $ctrlPath;
				$ctrl = new $controller;
				if(method_exists($ctrl, $method))
				{
					call_user_func_array([$ctrl,$method],$route['params']);
				} 
				else
					throw new \Exception("method in controller dose not exist", 1);
			}
			else
				throw new \Exception("controller dose not exist", 1);
		}
		else
		{
			self::errorPage(404);
		}
	}
}