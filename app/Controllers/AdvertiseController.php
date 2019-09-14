<?php use App\Core\Controller;

use App\Core\{Bcrypt , Auth};
use App\Models\Advertise;

class AdvertiseController extends Controller
{
	public function __construct ()
	{
		Auth::redirectToLogin('admin/login' , 'admin');
	}

	public function show ()
	{
		$ads = Advertise::all();
		return view('admin.advertise' , compact('ads'));
	}

	public function delete ($id)
	{
		$id = (int) $id;
		$ad = Advertise::findById($id)[0];
		deleteFile('upload/'.$ad->img);
		$ad->delete();
		return header('Location: '.url('admin/advertise'));
	}

	public function add ()
	{
		$ad = new Advertise;
		$ad->state = $_POST['state'];
		$ad->url = $_POST['url'];
		$ad->save();
		return header('Location: '.url('admin/advertise'));
	}

}