<?php

namespace App\Http\Controllers;

use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\WpOption;
use Cache;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $context = [];

    public function __construct()
    {
        $this->intContext();
    }

    public function getUserId()
    {
        return get_current_user_id();
    }

    /**
     * @return array
     */
    public function DBProcessCacheAllForced()
    {
        $this->DBProcessCacheGamesForced();
        $this->DBProcessCacheTournamentsForced();
        $this->DBProcessCacheTeamsForced();
        $this->DBProcessCachePlayersForced();
    }

    /**
     * @return array
     */
    public function DBProcessCacheGamesForced()
    {
        $expiresAt = $this->expiresAt(); // 1 min
        $games = Game::orderBy('name')->get()->toArray();
        Cache::put('games_c', $games, $expiresAt);
    }

    /**
     * @return array
     */
    public function DBProcessCacheTournamentsForced()
    {
        $expiresAt = $this->expiresAt(); // 1 min

        $tournaments = Tournament::orderBy('name')->get()->toArray();
        Cache::put('tournament_c', $tournaments, $expiresAt);
    }

    /**
     * @return array
     */
    public function DBProcessCacheTeamsForced()
    {
        $expiresAt = $this->expiresAt();
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

    /**
     * @return array
     */
    public function DBProcessCachePlayersForced()
    {
        $expiresAt = $this->expiresAt(); // 1 min
        $players = Player::orderBy('team_id')->get()->toArray();
        $teams = Cache::get('teams_c');
        foreach ($players as $key => $player) {
            foreach ($teams as $k => $t) {
                if ($t['id'] == $player['team_id']) {
                    $player[$key]['team_count'] = $t['team_count'];
                    $player[$key]['team_name'] = $t['name'];
                    break;
                }
            }
        }
        Cache::put('players_c', $players, $expiresAt);
    }

    /**
     * @return string
     */
    public function flushAll()
    {
        Cache::flush();
    }
    /**
     * @return string
     */
    public function expiresAt()
    {
        $expiresAt = Carbon::now('CMT')->addMinute(5)->toDateTimeString();
        return $expiresAt; // 5 min to give the db a break
    }

    private function intContext()
    {
        $cacheKey = md5(__METHOD__);
        $get = ['siteName'];
        try {
            return Cache::remember($cacheKey, $this->expiresAt(), function () use ($get) {
                foreach ($get as $method) {
                    $this->{$method}();
                }
            });
        } catch (\Illuminate\Database\QueryException $ex) {
            foreach ($get as $method) {
                $this->{$method}();
            }
            return Cache::put($cacheKey, $this->context, $this->expiresAt());
        }
    }

    protected function siteName()
    {
        $this->context['site_name'] = WpOption::where('option_name', 'blogname')->pluck('option_value')[0];
        $this->context['site_name_slug'] = str_slug($this->context['site_name']);
    }
}
