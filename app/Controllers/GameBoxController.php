<?php use App\Core\Controller;
use App\Models\Advertise;

class GameBoxController extends Controller
{
	public function manage ()
	{
		$games = \App\Models\Game::all();
		return view('admin.manageGames' , compact('games'));
	}

	public function add ()
	{
		$game = new \App\Models\Game;
		$game->name = $_POST['name'];
		$game->part = $_POST['part'];
		if($game->save())
		{
			$file = UPLOADDIR.$game->file;
			$zip = new ZipArchive;
			$res = $zip->open($file);
			if ($res === TRUE) {
				$zip->extractTo('games/'.rtrim($game->file,'.zip'));
				$zip->close();
			} else {
				echo 'failed, code:' . $res;
				exit;
			}
		}
		header('Location: '.url('admin/games'));
	}

	public function delete ($id)
	{
		$id = (int) $id;
		$game = \App\Models\Game::findById($id)[0];
		deleteFile('upload/'.$game->poster);
		deleteFile('upload/'.$game->file);
		$game->delete();
		return header('Location: '.url('admin/games'));
	}

}