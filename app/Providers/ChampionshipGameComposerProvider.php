<?php

namespace App\Providers;

use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\Championship\Game;
use Stash\Session;
use \View;
use Cache;

class ChampionshipGameComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if(isset($flush) and $flush){
            Cache::flush();
            $flush = false;
        }
        if(isset($cache) and $cache){ //TODO: will have to update to session whenever have time
            list($games, $tournaments, $teams, $players) = $this->DBProcessCacheAll();
        }elseif(!isset($cache) or !$cache){
            $games = $this->DBGetGames();
            $tournaments = $this->DBGetTournaments();
            $teams = $this->DBGetTeams();
            $players = $this->DBGetPlayers($teams);
        }
        View::composer(['game.game'],  function ($view) use ($games) {
            $view->with('games', $games);
        });
        View::composer(['game.tournament'], function ($view) use ($games, $tournaments) {
            $view->with('games', $games)
                ->with('tournaments', $tournaments);
        });
        View::composer(['game.team'], function ($view) use ($games, $tournaments, $teams) {
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams);
        });
        View::composer(['game.player'], function ($view) use ($games, $tournaments, $teams, $players) {
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('players', $players);
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
    public function DBProcessCacheAll()
    {
        $expiresAt = Carbon::now()->addMinute(2)->toDateTimeString(); // 1 min
        $expiredAt = null;
        if (!Cache::has('expiration_c') or Cache::get('expiration_c') > $expiresAt) {
            $expiredAt = Cache::get('expiration_c');
        } else {
            Cache::put('expiration_c', $expiresAt, $expiresAt);
        }
        if (Cache::has('games_c') or $expiredAt != null) {
            $games = Cache::get('games_c');
        } else {
            $games = $this->DBGetGames();
            Cache::put('games_c', $games, $expiresAt);
        }
        if (Cache::has('tournaments_c') or $expiredAt != null) {
            $tournaments = Cache::get('tournaments_c');
        } else {
            $tournaments = $this->DBGetTournaments();
            Cache::put('tournament_c', $tournaments, $expiresAt);
        }
        if (Cache::has('teams_c') or $expiredAt != null) {
            $teams = Cache::get('teams_c');
        } else {
            $teams = $this->DBGetTeams();
            Cache::put('teams_c', $teams, $expiresAt);
        }
        if (Cache::has('players_c') or $expiredAt != null) {
            $players = Cache::get('players_c');
            return array($games, $tournaments, $teams, $players);
        } else {
            $players = $this->DBGetPlayers($teams);
            Cache::put('players_c', $players, $expiresAt);
            return array($games, $tournaments, $teams, $players);
        }
    }

    /**
     * @return array
     */
    public function DBGetTeams()
    {
        $teams = Team::orderBy('name')->get()->toArray();
        $times = Player::select(DB::raw("COUNT(id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();

        foreach ($teams as $key => $team) {
            foreach ($times as $k => $t) {
                if ($team['id'] == $t['team_id']) {
                    $teams[$key]['team_count'] = $t['team_count'];
                    break;
                }
            }
        }
        return $teams;
    }

    /**
     * @param $teams
     * @return mixed
     */
    public function DBGetPlayers($teams)
    {
        $players = Player::orderBy('team_id')->get()->toArray();
        foreach ($players as $key => $player) {
            foreach ($teams as $k => $t) {
                if ($t['id'] == $player['team_id']) {
                    $players[$key]['team_count'] = $t['team_count'];
                    $players[$key]['team_name'] = $t['name'];
                    break;
                }
            }
        }
        return $players;
    }

    /**
     * @return mixed
     */
    public function DBGetTournaments()
    {
        $tournaments = Tournament::orderBy('name')->get()->toArray();
        return $tournaments;
    }

    /**
     * @return mixed
     */
    public function DBGetGames()
    {
        $games = Game::orderBy('name')->get()->toArray();
        return $games;
    }
}
