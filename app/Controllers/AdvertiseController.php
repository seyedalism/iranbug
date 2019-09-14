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

	// dynamic
	public function dynamicManage ()
	{
		$ads = Advertise::findByState(2);
		return view('admin.dynamicManage',compact('ads'));
	}

	public function dynamicDelete ($id)
	{
		$id = (int) $id;
		$ad = Advertise::findById($id)[0];
		deleteFile('upload/'.$ad->img);
		$ad->delete();
		return header('Location: '.url('admin/advertise/dynamic'));
	}

	public function dynamicAdd ()
	{
		$ad = new Advertise;
		$ad->state = 2;
		$ad->time = $_POST['time'];
		$ad->url = $_POST['url'];
		$ad->save();
		return header('Location: '.url('admin/advertise/dynamic'));
	}
	// zirnevis
	public function zirnevisManage ()
	{
		$ads = Advertise::findByState(3);
		return view('admin.zirnevisManage',compact('ads'));
	}

	public function zirnevisDelete ($id)
	{
		$id = (int) $id;
		$ad = Advertise::findById($id)[0];
		deleteFile('upload/'.$ad->img);
		$ad->delete();
		return header('Location: '.url('admin/advertise/zirnevis'));
	}

	public function zirnevisAdd ()
	{
		$ad = new Advertise;
		$ad->state = 3;
		$ad->time = $_POST['time'];
		$ad->url = $_POST['url'];
		$ad->text = $_POST['text'];
		$ad->save();
		return header('Location: '.url('admin/advertise/zirnevis'));
	}
}