<?php use App\Core\Controller;

use App\Models\Slide;
use App\Models\{User,Options};

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

}