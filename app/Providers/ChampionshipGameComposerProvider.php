<?php

namespace App\Providers;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
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
                ->with('teams', $this->shortTeams($this->getMaxPlayers()))
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
        $players = Player::select(DB::raw("COUNT(id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();
        foreach ($teams as $key => $team) {
            foreach ($players as $k => $t) {
                if ($team['id'] == $t['team_id']) {
                    $teams[$key]['team_count'] = $t['team_count'];
                    break;
                }
            }
        }
        Cache::put('teams_c', $teams, $this->getExpiresAt());
        return $teams;
    }

    /**
     * @param $maxPlayers
     * @return array
     */
    public function shortTeams($maxPlayers)
    {
//        if (Cache::has('short_teams_c_'.$maxPlayers) and $this->getExpiredAt() != null and $this->getExpiredAt() > Carbon::now()->toDateTimeString()) {
//            return Cache::get('short_teams_c_'.$maxPlayers);
//        }
        $teams = Team::orderBy('name')->get()->toArray();
        $times = Player::select(DB::raw("COUNT(id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();

        foreach ($teams as $key => $team) {
            foreach ($times as $k => $t) {
                if ($team['id'] == $t['team_id']) {
                    if ($maxPlayers <= $t['team_count']) {
                        unset($teams[$key]);
                        break;
                    }
                    $teams[$key]['team_count'] = $t['team_count'];
                    break;
                }
            }
            if (!isset($teams[$key]['team_count']) and isset($teams[$key])) {
                $teams[$key]['team_count'] = 0;
            }
        }
        Cache::put('short_teams_c_'.$maxPlayers, $teams, $this->getExpiresAt());
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
        $teamIds = [];
        foreach ($teams as $k => $t) {
            $teamIds[$t['id']] = 1;
        }
        $players = Player::orderBy('team_id')->get()->toArray();
        foreach ($players as $key => $player) {
            if(!array_key_exists($player['team_id'], $teamIds)){
                $players[$key]['team_name'] = " Doesn't Exist Anymore!!!!!";
                $players[$key]['team_count'] = "x";
            }else {
                foreach ($teams as $k => $t) {
                    if ($t['id'] == $player['team_id']) {
                        $players[$key]['team_count'] = $t['team_count'];
                        if (!isset($t['name']) or $t['name'] == "") {
                        }
                        $players[$key]['team_name'] = $t['name'];
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
        $players = IndividualPlayer::all()->toArray();
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
