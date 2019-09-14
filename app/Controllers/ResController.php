<?php use App\Core\Controller;

use App\Core\{Bcrypt , Auth};
use App\Models\{Category , Product , Slide , Special , Img , ResReserve , Reserve , Res};

class ResController extends Controller
{
	public function detail ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$res = Auth::returnUser('Res');
		return view('res.detailsRes' , compact('res'));
	}

	public function editDetail ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$res = Auth::returnUser('Res');
		$res->name = $_POST[ 'name' ];
		$res->time1 = $_POST[ 'time1' ];
		$res->time2 = $_POST[ 'time2' ];
		$res->delivery = $_POST[ 'delivery' ];
		$res->wifi = $_POST[ 'wifi' ];
		$res->game = $_POST[ 'game' ];
		$res->park = $_POST[ 'park' ];
		$res->child_bench = $_POST[ 'child_bench' ];
		$res->kart = $_POST[ 'kart' ];
		$res->music = $_POST[ 'music' ];
		$res->details = $_POST[ 'details' ];
		$res->save();
		header('Location: ' . url('restaurant/detail-res'));
	}

	public function loginPage ()
	{
		Auth::redirect('restaurant/dashboard' , 'Res');
		if ( !\Session::get('id') ) Auth::logout('Res');
		Auth::redirect('restaurant/dashboard' , 'Res');

		return view('res.login');
	}

	public function specialShow ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$s = Special::findByResid(\Session::get('id'));
		$imgs = [];
		if ( !empty($s) ) $imgs = Img::findBySpecialid($s[ 0 ]->id);
		return view('res.special' , compact('imgs'));
	}

	public function addEvent ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$special = Special::findByResid(\Session::get('id'));
		if ( empty($special) ) {
			$special = new Special;
			$special->title = $_POST[ 'title' ];
			$special->main = $_POST[ 'main' ];
			$special->resid = \Session::get('id');
			$special->save();
		} else {
			$special = $special[ 0 ];
			$special->title = $_POST[ 'title' ];
			$special->main = $_POST[ 'main' ];
			$special->resid = \Session::get('id');
			$special->save();
		}
		header('Location: ' . url('restaurant/event'));
	}

	public function addImgEv ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$special = Special::findByResid(\Session::get('id'));
		if ( !empty($special) ) {
			$special = $special[ 0 ];
			$img = new Img;
			$img->specialid = $special->id;
			$img->save();
		}
		header('Location: ' . url('restaurant/event'));
	}

	public function rmvImgEv ( $id )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$img = Img::findById($id);
		$s = Special::findById($img[ 0 ]->specialid);
		$this->ifThisIsYours($s , "restaurant/event" , "resid");
		if ( $s[ 0 ]->resid == \Session::get('id') ) {
			$img = $img[ 0 ];
			deleteFile('upload/' . $img->img);
			$img->delete();
		}
		header('Location: ' . url('restaurant/event'));
	}

	public function login ()
	{
		Auth::redirect('restaurant/dashboard' , 'Res');
		$post = $_POST;
		if ( Auth::login($post[ 'username' ] , $post[ 'password' ] , 'Res') ) {
			header('Location: ' . url('restaurant/dashboard'));
		} else {
			header('Location: ' . url('restaurant/login'));
		}
	}

	public function dashboard ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		return view('res.dashboard');
	}

	public function logout ()
	{
		Auth::logout('Res');
		header('Location: ' . url('restaurant/login'));
	}

	public function show ( $id = -1 )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$html = $this->getCategories();
		if ( $id != -1 ) {
			$product = Product::findById($id);
			$this->ifThisIsYours($product , "restaurant/show-products");
			return view('res.editProduct' , compact('html' , 'product'));
		}

		return view('res.addProduct' , compact('html'));
	}

	public function addProduct ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');

		$html = $this->getCategories();

		$post = $_POST;

		$product = new Product;
		foreach ( $post as $key => $value ) {
			$product->$key = $value;
		}
		$product->res = \Session::get('id');
		$c = Category::findById($product->category);
		if ( !( !empty($c) && $c->res == \Session::get('id') ) ) $product->category = null;
		$product->save();
		$errors = $product->errors->firstOfAll();
		return view('res.addProduct' , compact('html' , 'errors' , 'product'));
	}

	//    public function remove($id)
	//    {
	//        Auth::redirectToLogin('restaurant/login','Res');
	//        $p = Product::findById($id);
	//        $this->deleteFile('upload/'.$p->img);
	//        $p->delete();
	//        header('location: '.url('restaurant/show-products'));
	//    }

	public function update ( $id )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');

		$product = Product::findById($id);
		$this->ifThisIsYours($product , 'restaurant/show-products');

		$post = $_POST;

		$html = $this->getCategories();
		foreach ( $post as $key => $value ) {
			if ( !empty($value) ) $product->$key = $value;
		}
		$product->save();

		return view('res.editProduct' , compact('html' , 'product'));
	}

	public function manageProducts ( $page = 0 )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');

		$zarib = 30;
		$start = $page * $zarib;
		$end = $start + $zarib;
		$products = Product::findByRes(\Session::get('id'));
		$i = 0;
		$pages = [ "pre" => $page - 1 , "next" => $page + 1 ];
		return view('res.showProduct' , compact('products' , 'start' , 'i' , 'pages'));
	}

	public function getCategories ()
	{
		$html = '<div class="p-1 lister">';
		$modals = "";
		$categories = Category::findByRes(\Session::get('id'));
		foreach ( $categories as $category ) {
			$html .= '<h5><span>+ </span> <input type="radio" name="category" value="' . $category->id . '"> <span class="text-info">' . $category->name . '</span><span class="col-5"></span>';
			echo "<br>";
			$html .= '</div>';
		}
		$html .= '</div>';
		return $html;
	}

	public function deleteFile ( $file )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$file = ( isset(glob($file)[ 0 ]) ) ? glob($file)[ 0 ] : 'nothing';
		if ( is_file($file) ) unlink($file);
	}

	//
	//    public function slideShow()
	//    {
	//        Auth::redirectToLogin('restaurant/login','Res');
	//        $slides = Slide::findByRes(\Session::get('id'));
	//        $cats = Category::findByRes(\Session::get('id'));
	//        return view('res.manageSlides',compact('slides','cats'));
	//    }
	//
	//    public function slideDelete($id)
	//    {
	//        Auth::redirectToLogin('restaurant/login','Res');
	//        $slide = Slide::findById($id)[0];
	//        $slide->delete();
	//        header('location: '.url('restaurant/'));
	//    }

	//    public function slideAdd()
	//    {
	//        Auth::redirectToLogin('restaurant/login','Res');
	//        $reffer = $_SERVER['HTTP_REFERER'];
	//        $post = $_POST;
	//        $slide = new Slide;
	//        $slide->category = $post['category'];
	//        $slide->res = \Session::get('id');
	//        $slide->save();
	//        header('location: '.$reffer);
	//    }

	// cat
	public function Catshow ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$html = $this->getCategories2();
		$category = Category::findByRes(\Session::get('id'));
		return view('res.category' , compact('html' , 'category'));
	}

	public function Catadd ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$message = null;
		$errors = null;
		$name = isset($_POST[ 'name' ]) ? $_POST[ 'name' ] : null;
		$mainCategory = new Category;
		$mainCategory->name = $name;
		$mainCategory->res = \Session::get('id');
		$mainCategory->save();
		$errors = $mainCategory->errors->all();

		if ( !empty($errors) ) $message = "مشکلی در اضافه کردن رخ داده است."; else
			$message = "موضوع مورد نظر با موفقیت اضافه گردید";

		return view('res.category' , compact('errors' , 'message'));
	}

	public function Catupdate ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$main = Category::findById($_POST[ 'id' ]);
		$this->ifThisIsYours($main , 'restaurants/category');
		$main->name = $_POST[ 'name' ];
		$main->save();

		header('Location: ' . url('restaurant/category'));
	}

	public function CatmainDelete ( $id )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$cat = Category::findById($id);
		$this->ifThisIsYours($cat , 'restaurant/category');
		$res = $cat->delete();
		$message = "موضوع مورد نظر همراه زیر موضوع ها حذف گردید";
		if ( !$res ) $message = "مشکلی در حذف رخ داده است.";
		return view('res.category' , compact('message'));
	}

	public function deleteProduct ( $id )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$p = Product::findById($id);
		$this->ifThisIsYours($p , "restaurant/show-products");

		$this->deleteFile('upload/' . $p->img);
		$p->delete();
		header('location: ' . url('restaurant/show-products'));
	}

	public function getCategories2 ()
	{
		$html = '<div class="p-1 lister">';
		$modals = "";
		$categories = Category::findByRes(\Session::get('id'));
		$categories = ( empty($categories) ) ? [] : $categories;
		foreach ( $categories as $category ) {
			$html .= '<h5><span>+ </span><span class="text-info">' . $category->name . '</span><span class="col-5"></span><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' . $category->id . '">ویرایش</button><span class="col-5"></span><a href="' . url('restaurant/category/delete/') . $category->id . '"class="btn btn-danger">حذف</a></h5><div class="px-3">';
			$modals .= $this->createModal($category->id , 1);
			echo "<br>";
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= $modals;
		return $html;
	}

	public function createModal ( $id , $main = 0 )
	{
		$sub = "";
		return $modal = '
        <div class="modal" id="' . $sub . 'modal' . $id . '" style="z-index: 2000;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="' . url('restaurant/category') . '" method="post">
              <input type="hidden" name="_method" value="PATCH" >
              <input type="hidden" name="main" value="' . $main . '" >
              <input type="hidden" name="id" value="' . $id . '" >
              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">نام جدید را وارد کنید.</h4>
              </div>
        
              <!-- Modal body -->
              <div class="modal-body">
                <input type="text" class="form-control" name="name" required>
              </div>
        
              <!-- Modal footer -->
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn btn-primary" name="submit">ویرایش</button>
              </div>
              
              </form>
            </div>
          </div>
        </div>
        ';
	}

	///////////////////////// miz
	public function sitSetting ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$opt = ResReserve::findByResid(\Session::get('id'));
		$opt = ( empty($opt) ) ? [] : $opt;
		return view('res.sitSetting' , compact('opt'));
	}

	public function addSit ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$sit = new ResReserve();
		$sit->capacity = $_POST[ 'count' ];
		$sit->price = $_POST[ 'price' ];
		$sit->resid = \Session::get('id');
		$sit->save();
		header('Location: ' . url('restaurant/sit/setting'));
	}

	public function rmvSit ( $id )
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$sit = ResReserve::findById($id)[ 0 ];
		$this->ifThisIsYours($sit , 'restaurant/sit/setting');
		$sit->delete();
		header('Location: ' . url('restaurant/sit/setting'));
	}

	public function showReserved ()
	{
		Auth::redirectToLogin('restaurant/login' , 'Res');
		$reserve = Reserve::where('res' , '=' , \Session::get('id') , 'time_s|ASC');
		$reserve = ( $reserve ) ? $reserve : [];
		return view('res.manageReserved' , compact('reserve'));
	}


	/// baghali
	public function Adetail ()
	{
		Auth::redirectToLogin('admin/login' , 'admin');
		$res = Res::findById(-1)[ 0 ];
		return view('admin.detailsRes' , compact('res'));
	}

	public function AeditDetail ()
	{
		Auth::redirectToLogin('admin/login' , 'admin');
		$res = Res::findById(-1)[ 0 ];
		$res->name = $_POST[ 'name' ];
		$res->time1 = $_POST[ 'time1' ];
		$res->time2 = $_POST[ 'time2' ];
		$res->delivery = $_POST[ 'delivery' ];
		$res->wifi = $_POST[ 'wifi' ];
		$res->game = $_POST[ 'game' ];
		$res->park = $_POST[ 'park' ];
		$res->child_bench = $_POST[ 'child_bench' ];
		$res->kart = $_POST[ 'kart' ];
		$res->music = $_POST[ 'music' ];
		$res->details = $_POST[ 'details' ];
		$res->save();
		header('Location: ' . url('admin/detail-res'));
	}

	// utilities
	public function ifThisIsYours ( &$x , $url , $res = "res" )
	{
		if ( empty($x) || \Session::get('id') != $x[ 0 ]->$res ) {
			header("Location: " . url($url));
			exit;
		}
		$x = $x[ 0 ];
	}
}