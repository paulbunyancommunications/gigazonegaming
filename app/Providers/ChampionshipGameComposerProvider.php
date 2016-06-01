<?php

namespace App\Providers;

use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\Championship\Game;
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
        list($games, $tournaments, $teams, $players) = $this->DBProcessCacheAll();
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
        $expiresAt = Carbon::now()->addMinute(1)->toDateTimeString(); // 1 min
        if (Cache::has('games_c')) {
            $games = Cache::get('games_c');
        } else {
            $games = Game::orderBy('name')->get()->toArray();
            Cache::put('games_c', $games, $expiresAt);
        }
        if (Cache::has('tournaments_c')) {
            $tournaments = Cache::get('tournaments_c');
        } else {
            $tournaments = Tournament::orderBy('name')->get()->toArray();
            Cache::put('tournament_c', $tournaments, $expiresAt);
        }
        if (Cache::has('teams_c')) {
            $teams = Cache::get('teams_c');
        } else {
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
            Cache::put('teams_c', $teams, $expiresAt);
        }
        if (Cache::has('players_c')) {
            $players = Cache::get('players_c');
            return array($games, $tournaments, $teams, $players);
        } else {
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
            Cache::put('players_c', $players, $expiresAt);
            return array($games, $tournaments, $teams, $players);
        }
    }
}
