<?php

namespace App\Providers;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Player_Team;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\Championship\Game;
use \View;
use \Cache;

class ChampionshipGameComposerProvider extends ServiceProvider
{
    protected $expiresAt;
    protected $expiredAt;
    protected $maxPlayers = 0;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setExpiresAt(Carbon::now()->addMinute(5)->toDateTimeString()); //set expires in 5 min
        if (Cache::has('expiration_c') and Cache::get('expiration_c') > Carbon::now()->toDateTimeString()) {
            $this->setExpiredAt(Cache::get('expiration_c'));
        } else {
            Cache::forever('expiration_c', $this->getExpiresAt()); //name time of expiration and when it expires
        }
        View::composer(['game.game'], function ($view) {
            $view->with('games', $this->games());
        });
        View::composer(['game.tournament'], function ($view) {
            $view->with('games', $this->games())
                ->with('tournaments', $this->tournaments());
        });
        View::composer(['game.team'], function ($view) {
            $view->with('games', $this->games())
                ->with('tournaments', $this->tournaments())
                ->with('teams', $this->teams())
                ->with('players', $this->players($this->teams()));
        });
        View::composer(['game.player'], function ($view) {
            $view->with('games', $this->games())
                ->with('tournaments', $this->tournaments())
                ->with('teams', $this->teams())
                ->with('players', $this->players($this->teams()));
        });
        View::composer(['game.individualPlayer'], function ($view) {
            $view->with('games', $this->games())
                ->with('tournaments', $this->tournaments())
                ->with('teams', $this->teams())
                ->with('individualPlayers', $this->individualPlayers());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @return array
     */
    public function teams()
    {
//        if (Cache::has('teams_c') and $this->getExpiredAt() != null and $this->getExpiredAt() > Carbon::now()->toDateTimeString()) {
//            return Cache::get('teams_c');
//        }
        $teams = Team::orderBy('name')->get()->toArray();
        $player_team = Player_Team::select(DB::raw("COUNT(id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();
        foreach ($teams as $key => $team) {
            $max_p = Tournament::where('tournaments.id','=', $team['tournament_id'])->first()->toArray()['max_players'];
            foreach ($player_team as $k => $p) {
                if ($team['id'] == $p['team_id']) {
                    $teams[$key]['team_count'] = $p['team_count'];
                    $teams[$key]['max_number_of_players'] = $max_p;
//                    break;
                }
                if($player_team == []){
                    $teams[$key]['team_count'] = 0;
                    $teams[$key]['max_number_of_players'] = $max_p;
                }
            }
        }
        Cache::put('teams_c', $teams, $this->getExpiresAt());
        return $teams;
    }

    /**
     * @param $teams
     * @return mixed
     */
    public function players($teams)
    {
//        if (Cache::has('players_c') and $this->getExpiredAt() != null and $this->getExpiredAt() > Carbon::now()->toDateTimeString()) {
//            dd("here");
//            return Cache::get('players_c');
//        }
//        dd($teams);
        $teamIds = [];
        foreach ($teams as $k => $t) {
            $teamIds[$t['id']] = 1;
        }
        $players = Player::
            join('player_team', 'players.id', '=', 'player_team.player_id')
            ->join('teams','teams.id','=', 'player_team.team_id')
            ->join('player_tournament','players.id','=', 'player_tournament.player_id')
            ->join('tournaments','tournaments.id','=', 'player_tournament.tournament_id')
            ->join('games','games.id','=', 'tournaments.game_id')
            ->select(
                'players.id as id',
                'players.id as player_id',
                'players.email',
                'players.username',
                'players.name',
                'players.phone',
                'player_team.verification_code as verification_code',
                'teams.id as team_id',
                'teams.name as team_name',
                'teams.captain as captain',
                'tournaments.id as tournament_id',
                'tournaments.name as tournament_name',
                'tournaments.max_players as max_players',
                'tournaments.max_players as max_number_of_players',
                'games.id as game_id',
                'games.name as game_name'
            )
            ->orderBy('team_id')
            ->get()
            ->toArray();
        $teams = Player_Team::select(DB::raw("COUNT(team_id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();

        foreach ($players as $key => $player) {
            if(!array_key_exists($player['team_id'], $teamIds)){
                $players[$key]['team_name'] = " Doesn't Exist Anymore!!!!!";
                $players[$key]['team_count'] = "x";
            }else {
                foreach ($teams as $k => $t) {
                    if ($t['team_id'] == $player['team_id']) {
                        $players[$key]['team_count'] = $t['team_count'];
                    }
                }
            }

        }
        Cache::put('players_c', $players, $this->getExpiresAt());
        return $players;
    }

    /**
     * @return mixed
     */
    public function individualPlayers()
    {
//        if (Cache::has('individual_player_c') and $this->getExpiredAt() != null and $this->getExpiredAt() > Carbon::now()->toDateTimeString()) {
//            return Cache::get('individual_player_c');
//        }
        $players = Player::all()->toArray();
        $users = DB::table('players')
            ->join('player_team', 'players.id', '=', 'player_team.players_id')
            ->where()
            ->get();
        Cache::put('individual_player_c', $players, $this->getExpiresAt());
        return $players;
    }

    /**
     * @return mixed
     */
    public function tournaments()
    {

//        if (Cache::has('tournaments_c') and $this->getExpiredAt() != null and $this->getExpiredAt() > Carbon::now()->toDateTimeString()) {
//            return Cache::get('tournaments_c');
//        }
        $tournaments = Tournament::orderBy('name')->get()->toArray();
        Cache::put('tournament_c', $tournaments, $this->getExpiresAt());
        return $tournaments;
    }

    /**
     * @return mixed
     */
    public function games()
    {
//        if (Cache::has('games_c') and $this->getExpiredAt() != null and $this->getExpiredAt() > Carbon::now()->toDateTimeString()) {
//            return Cache::get('games_c');
//        }
        $games = Game::orderBy('name')->get()->toArray();
        Cache::put('games_c', $games, $this->getExpiresAt());

        return $games;
    }

    public function setExpiresAt($value)
    {
        $this->expiresAt = $value;
        return $this;
    }

    public function setExpiredAt($value)
    {
        $this->expiredAt = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return mixed
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @param int $maxPlayers
     * @return ChampionshipGameComposerProvider
     */
    public function setMaxPlayers($maxPlayers)
    {
        $this->maxPlayers = $maxPlayers;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPlayers()
    {
        return $this->maxPlayers;
    }
}
