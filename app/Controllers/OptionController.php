<?php use App\Core\Auth;
use App\Core\Controller;

use App\Models\{User,Options};

class OptionController extends Controller
{
    public function __construct()
    {
        Auth::redirectToLogin('admin/login','admin');
    }

    public function aboutUs()
    {
        $op = Options::findById(1)[0];
        return view('admin.aboutUs',compact('op'));
	}

    public function addAbout()
    {
        $op = Options::all()[0];
        $op->main = $_POST['main'];
        $op->save();
        header('Location: '.url('admin/about-us'));
	}

    public function benefits()
    {
        $op = Options::all()[1];
        return view('admin.benefits',compact('op'));
	}

    public function addBenefits()
    {
        $op = Options::all()[1];
        $op->main = $_POST['main'];
        $op->save();
        header('Location: '.url('admin/benefits'));
    }

    public function upload()
    {

        /*******************************************************
         * Only these origins will be allowed to upload images *
         ******************************************************/
        $accepted_origins = array("http://localhost", "http://192.168.1.1", "http://example.com","http://iranbaguette.com");

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        $imageFolder = 'upload/';

        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                // same-origin requests won't set an origin. If the origin is set, it must be valid.
                if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header("HTTP/1.1 403 Origin Denied");
                    return;
                }
            }

            /*
              If your script needs to receive cookies, set images_upload_credentials : true in
              the configuration and enable the following two headers.
            */
            // header('Access-Control-Allow-Credentials: true');
            // header('P3P: CP="There is no P3P policy."');

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);
            $filetowrite = '../public/'.$filetowrite;
            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            echo json_encode(array('location' => $filetowrite));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }


    }

}