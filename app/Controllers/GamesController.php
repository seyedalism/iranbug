<?php use App\Core\Controller;
use App\Models\{Advertise , Game , Pcode , Pay};
use App\Core\{Auth};

class GamesController extends Controller
{
	public function show ($id = 1)
	{
		$sell = false;
		$ads = Advertise::where('state' , '<' , '2');
		$dynamic = Advertise::where('state' , '=' , '2');
		$game = Game::findById($id)[0];
		$urls = ["1" => url('public/games/'.rtrim($game->file , '.zip').'/part1/index.html')];
		if(Auth::check()) {
			$user = Auth::returnUser();
			$pcode = Pcode::where('user_id' , '=' , $user->id);
			if(!empty($pcode)) {
				$pcode = array_filter($pcode , function ($item) use ($id) {
					if($item->game_id == $id) {
						return $item->part;
					}
				});
				rsort($pcode);
				for ( $i = 1 ; $i <= count($pcode) + 1 ; $i++ ) {
					$urls[$i] = url('public/games/'.rtrim($game->file , '.zip').'/part'.$i.'/index.html');
				}
			}
			//
			$pays = Pay::findByProducts('game'.$id);
			if(!empty($pays)) foreach ( $pays as $pay ) if($pay->userid == $user->id) $sell = true;

		}
		$zirnevis = Advertise::random();
		if(!empty($zirnevis)) $zirnevis = $zirnevis[0];
		return view('game.games' , compact('id' , 'ads' , 'dynamic' , 'game' , 'urls' , 'zirnevis' , 'sell'));
	}

	public function redirectToAd ($id)
	{
		$ad = Advertise::findById($id)[0];
		$ad->clicks++;
		$ad->save();
		return header('Location: '.$ad->url);
	}

	public function gamesPage ()
	{
		$games = Game::findByStatus(0);
		return view('game.gamesPage' , compact('games'));
	}
}