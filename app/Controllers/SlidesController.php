<?php use App\Core\Controller;

use App\Models\Category;
use App\Models\Slide;
use App\Core\{Auth};

class SlidesController extends Controller
{
    public function __construct()
    {
        Auth::redirectToLogin('admin/login','admin');
    }

    public function show()
    {
        $slides = Slide::findByRes('-1');
        $cats = Category::findByRes('-1');

        return view('admin.manageSlides',compact('cats','slides'));
    }

    public function delete($id)
	{
        $reffer = $_SERVER['HTTP_REFERER'];
        $slide = Slide::findById($id)[0];
        $slide->delete();
        header('location: '.$reffer);
	}

    public function add()
	{
        $reffer = $_SERVER['HTTP_REFERER'];
        $post = $_POST;
        $slide = new Slide;
        $slide->category = $post['category'];
        $slide->res = '-1';
        $slide->save();
        header('location: '.$reffer);
	}


}