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
    protected $maxPlayers = 5;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setExpiresAt(Carbon::now()->addMinute(2)->toDateTimeString());
        $this->setExpiredAt(null);
        if (!Cache::has('expiration_c') or Cache::get('expiration_c') > $this->getExpiresAt()) {
            $this->setExpiredAt(Cache::get('expiration_c'));
        } else {
            Cache::put('expiration_c', $this->getExpiresAt(), $this->getExpiresAt());
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
                ->with('teams', $this->teams());
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
    protected function teams()
    {
        if (Cache::has('teams_c') or $this->getExpiredAt() != null) {
            return Cache::get('teams_c');
        }
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
        Cache::put('teams_c', $teams, $this->getExpiresAt());
        return $teams;
    }

    /**
     * @param $maxPlayers
     * @return array
     */
    protected function shortTeams($maxPlayers)
    {
        if (Cache::has('short_teams_c_'.$maxPlayers) or $this->getExpiredAt() != null) {
            return Cache::get('short_teams_c_'.$maxPlayers);
        }
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
    protected function players($teams)
    {
        if (Cache::has('players_c') or $this->getexpiredAt() != null) {
            return Cache::get('players_c');
        }
        $players = Player::orderBy('team_id')->get()->toArray();
        foreach ($players as $key => $player) {
            foreach ($teams as $k => $t) {
                if ($t['id'] == $player['team_id']) {
                    $players[$key]['team_count'] = $t['team_count'];
                }
            }
        }
        Cache::put('players_c', $players, $this->getExpiresAt());
        return $players;
    }

    /**
     * @return mixed
     */
    protected function individualPlayers()
    {
        if (Cache::has('individual_player_c') or $this->getexpiredAt() != null) {
            return Cache::get('individual_player_c');
        }
        $players = IndividualPlayer::all()->toArray();
        Cache::put('individual_player_c', $players, $this->getExpiresAt());
        return $players;
    }

    /**
     * @return mixed
     */
    protected function tournaments()
    {

        if (Cache::has('tournaments_c') or $this->getexpiredAt() != null) {
            return Cache::get('tournaments_c');
        }
        $tournaments = Tournament::orderBy('name')->get()->toArray();
        Cache::put('tournament_c', $tournaments, $this->getExpiresAt());
        return $tournaments;
    }

    /**
     * @return mixed
     */
    protected function games()
    {
        if (Cache::has('games_c') or $this->getExpiredAt() != null) {
            return Cache::get('games_c');
        }
        $games = Game::orderBy('name')->get()->toArray();
        Cache::put('games_c', $games, $this->getExpiresAt());

        return $games;
    }

    protected function setExpiresAt($value)
    {
        $this->expiresAt = $value;
        return $this;
    }

    protected function setExpiredAt($value)
    {
        $this->expiredAt = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return mixed
     */
    protected function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @param int $maxPlayers
     * @return ChampionshipGameComposerProvider
     */
    protected function setMaxPlayers($maxPlayers)
    {
        $this->maxPlayers = $maxPlayers;
        return $this;
    }

    /**
     * @return int
     */
    protected function getMaxPlayers()
    {
        return $this->maxPlayers;
    }
}
