<?php

function dd($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre><br>";
	exit;
}

function d($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre><br>";
}

function view($view,$data = [])
{
	App\Core\View::render($view,$data);	
}

function showSystemError($mes,$sysMes)
{
	echo '<br>'.$mes.'<br>';
	if(DEVELOPER_MODE)
	{
		echo $sysMes;
	}
	exit;
}

function crypter($string,$action)
{
	$output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'aj=ab?chi*-6@#';
    $secret_iv = 'h&wn39s)-=?as1@!';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') 
    {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } 
    else 
    {
        if ($action == 'decrypt') 
        {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    return $output;
}


function asset($file){
    return PUBLICPATH.$file;
}

function url($route = ""){
    return URL.$route;
}

function deleteFile($file)
{
    $file = (isset(glob($file)[0])) ? glob($file)[0] : 'nothing';
    if(is_file($file))
        unlink($file);
}
