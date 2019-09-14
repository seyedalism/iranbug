<?php use App\Core\Controller;

use App\Models\{Comment};
use App\Core\{Auth};

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::redirectToLogin('admin/login-pof','admin');
    }

    public function show($page = 0)
    {
        $page =  (int) $page;
        $comments = Comment::all($page * 9,$page * 9 + 9);
        return view('admin.comments',compact('comments','page'));
    }

    public function add($id)
    {
        $c = Comment::findById($id)[0];
        $c->valid = 1;
        $c->save();
        header('Location: '.url('admin/comments'));
    }

    public function delete($id)
	{
        $c = Comment::findById($id)[0];
        $c->delete();
        header('Location: '.url('admin/comments'));
    }

}