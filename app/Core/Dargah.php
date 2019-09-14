<?php namespace App\Core;

use App\Core\{Bcrypt};
use App\Models\User;
use http\Client;
use Zarinpal\Zarinpal;


class Dargah
{
    public static $errors;
    public static $res;

    public static function transaction($amount, $redirect, $mobile = null, $email = null,$description = null)
    {
        $zarinpal = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
        $zarinpal->enableSandbox();
//            $zarinpal->isZarinGate();
        $results = $zarinpal->request(
            $redirect,
            $amount,
            'testing',
            $email,
            $mobile
        );
        \Session::set('jam',$amount);
        if (isset($results['Authority'])) {
            file_put_contents('Authority', $results['Authority']);
            $zarinpal->redirect();
        }

    }

    public static function end()
    {
        $zarinpal = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
        $authority = file_get_contents('Authority');
        $jam = \Session::get('jam');
        if($jam) {
            $res = $zarinpal->verify($jam,$authority);
            dd($res);
            self::$res = $res;
            if($res['Status'] == "success")
                return true;
            return false;
        }
    }

}