<?php use App\Core\Controller;

use App\Core\{Bcrypt,Auth,jdf};
use App\Models\{Reserve,ResReserve};

class ReserveController extends Controller
{
    public function home($id = -1)
    {
        $message = isset($_GET['message']) ? $_GET['message'] : null;
        $home = 1;
        $miz  = ResReserve::findByResid($id);
        $reserve = Reserve::findByRes($id);
        $reserve = ($reserve) ? $reserve : [];
        $out = $miz;
        foreach ($reserve as $r)
        if($r->time_e < time())
            $r->delete();

        $errors = (isset($_GET['errors'])) ? unserialize($_GET['errors']) : [];

        return view('reserve',compact('errors','home','id','out','message'));
	}
    public function add($id = -1)
    {
        $_POST['time_s'] = $this->faTOen($_POST['time_s']);
        $date = explode('-',$_POST['time_s']);
        $time = explode(':',substr($date[2],'3'));
        $date[2] = substr($date[2],'0','2');
        $time_s = jdf::jmktime($time[0],$time[1],0,$date[1],$date[2],$date[0]);
        $time_e = jdf::jmktime((int)$time[0] + (int)$_POST['time_e'],$time[1],0,$date[1],$date[2],$date[0]);

        $reserve = new Reserve;
        $reserve->name   = $_POST['name'];
        $reserve->time_e = ($time_e <= 3) ? $time_e : 3;
        $reserve->time_s = $time_s;
        $reserve->phone  = $_POST['phone'];
        $reserve->detail = $_POST['detail'];
        $reserve->res_reserve_ids = rtrim($_POST['capacity'],'-');
        $reserve->res = $_POST['res'];

        $message = null;
        if ($this->checkTime($reserve,$id))
        {
            if ($reserve->save())
                $message = "میز با موفقیت رزرو شد";
            else {
                $message = "متاسفانه مشکلی در رزرو میز به وجود آمده است";
                $errors = $reserve->errors->firstOfAll();
                $errors = serialize($errors);
            }
        } else {
            $message = "متاسفانه میز انتخابی ، در زمان مورد نظر رزرو شده است.";
        }
        $home = 1;
        header('Location: '.url('reserve/'.$id.'?message=').$message.' & errors='.$errors);
    }

    function faTOen($string) {
        return strtr($string, array('۰'=>'0', '۱'=>'1', '۲'=>'2', '۳'=>'3', '۴'=>'4', '۵'=>'5', '۶'=>'6', '۷'=>'7', '۸'=>'8', '۹'=>'9', '٠'=>'0', '١'=>'1', '٢'=>'2', '٣'=>'3', '٤'=>'4', '٥'=>'5', '٦'=>'6', '٧'=>'7', '٨'=>'8', '٩'=>'9'));
    }

    public function checkTime(Reserve $reserve,$id)
    {
        $old  = Reserve::findByRes($id);
        foreach ($old as $m)
        {
            if (array_intersect(explode('-',$m->res_reserve_ids),explode('-',$reserve->res_reserve_ids)))
            {
                if ($reserve->time_e > $m->time_s && $reserve->time_s < $m->time_e)
                    return false;
            }
        }
        return true;
    }
}