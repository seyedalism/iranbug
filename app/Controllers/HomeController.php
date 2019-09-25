<?php use App\Core\Auth;
use App\Core\Controller;

use App\Models\Slide;
use App\Models\{Pcode , User , Options};

class HomeController extends Controller
{
	public function home()
	{
        $home = 1;
        $op = Options::findById(1)[0];
        $slides = Slide::findByRes(-1);
        $op->main = str_replace('../','',$op->main);
        $op->main = str_replace('width="','class="img-fluid"',$op->main);
        $op->main = str_replace('height="','',$op->main);
        return view('home',compact('op','home','slides'));
    }

    public function benefits()
    {
        $benefits = Options::all()[1];
        $benefits->main = str_replace('../','',$benefits->main);
        $benefits->main = str_replace('width="','class="img-fluid"',$benefits->main);
        $benefits->main = str_replace('height="','',$benefits->main);
        return view('benefits',compact('benefits'));
	}
    public function contactUs()
    {
        $contactUs = Options::all()[1];
        $contactUs->main = str_replace('../','',$contactUs->main);
        $contactUs->main = str_replace('width="','class="img-fluid"',$contactUs->main);
        $contactUs->main = str_replace('height="','',$contactUs->main);
        return view('contact-us',compact('contactUs'));
    }

	public function pcode ($id)
	{
		Auth::redirectToLogin('login');
		$code = $_POST['buy_code'];
		$p = Pcode::findByCode($code);
		if(!empty($p) && $p[0]->code == $code && !$p[0]->game_id) {
			$p = $p[0];
			$p->user_id = Auth::returnUser()->id;
			$p->game_id = $id;
			$p->part = $_POST['p'];
			$p->save();
		}
		return header('Location: '.$_SERVER['HTTP_REFERER']);
	}
}