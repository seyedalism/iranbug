<?php

define('HOST','localhost');
define('USERNAME','root');
define('PASSWORD','');
define('DATABASE','iranbug');

define('ROOTPATH'  , dirname(dirname(dirname(__FILE__))));
define('DIRSEP'  , DIRECTORY_SEPARATOR);
//define('PUBLICPATH', ROOTPATH . DIRSEP .'public');
define('VIEWSPATH', ROOTPATH . DIRSEP .'views');

define('URL','http://localhost/projects/irabBuget/');
define('PUBLICPATH', URL.'public/');
define('UPLOADDIR', ROOTPATH . DIRSEP . 'public' . DIRSEP .'upload' . DIRSEP);
define('DOWN', 0);

define('DEVELOPER_MODE',1);

define('ADMIN_PASSWORD','$2y$12$A.St8RKwGlXMTcO30QBK1.4ENKRYeL3o27XBnB4oppIxOBmAmBd2C'); //sju&83#@?
define('ADMIN_USERNAME','panahian');