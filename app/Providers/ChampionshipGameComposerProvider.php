<?php

namespace App\Providers;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Player_Team;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\PlayerRelationable;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\Championship\Game;
use PhpParser\Node\Expr\Array_;
use \View;
use \Cache;

class ChampionshipGameComposerProvider extends ServiceProvider
{
    use PlayerRelationable;
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
        $games = $this->games();
        View::composer(['game.game'], function ($view) use ($games) {
            $view->with('games', $games);
        });
        $tournaments = $this->tournaments();
        View::composer(['game.tournament'], function ($view) use ($games, $tournaments) {
            $view->with('games', $games)
                ->with('tournaments', $tournaments);
        });

        $teams = $this->teams();

        $players = $this->getPlayersInfoBy();

        View::composer(['game.team'], function ($view) use ($games, $tournaments, $teams, $players) {
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('players', $players);
        });
        View::composer(['game.player'], function ($view) use ($games, $tournaments, $teams, $players) {
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('players', $players);
        });
        View::composer(['game.individualPlayer'], function ($view) use ($games, $tournaments, $teams, $players)  {
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('individualPlayers', $this->individualPlayers($players));
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
        $player_team = PlayerRelation::select(DB::raw("COUNT(relation_id) as team_count"), "relation_id as team_id")->where('relation_type','like','%Team')->groupBy('team_id')->get()->toArray();

        $teams = Team::select(
            'teams.id as team_id',
            'teams.name as team_name',
            'teams.emblem as team_emblem',
            'teams.verification_code as team_verification_code',
            'teams.captain as team_captain',
            'teams.tournament_id'
        )->orderBy('name')->get()->toArray();

        foreach ($teams as $key => $team) {
            $max_p = Tournament::where('tournaments.id','=', $team['tournament_id'])->first()->toArray()['max_players'];
            foreach ($player_team as $k => $p) {
                if ($team['team_id'] == $p['team_id']) {
                    $teams[$key]['team_count'] = $p['team_count'];
                    $teams[$key]['team_max_players'] = $max_p;
//                    break;
                }
                if($player_team == []){
                    $teams[$key]['team_count'] = 0;
                    $teams[$key]['team_max_players'] = $max_p;
                }
            }
        }
        return $teams;
    }
    /**
     * @return mixed
     */
    public function individualPlayers($players)
    {
        dd($players);
        return $players;
    }

    /**
     * @return mixed
     */
    public function tournaments()
    {
        $tournaments = Tournament::select('tournaments.name as tournament_name', 'tournaments.game_id', 'tournaments.max_players', 'tournaments.id as tournament_id')->orderBy('name')->get()->toArray();
        return $tournaments;
    }

    /**
     * @return mixed
     */
    public function games()
    {
        $games = Game::select('games.name as game_name', 'games.id as game_id', 'games.title as game_title', 'games.description as game_description', 'games.uri as game_uri' )->orderBy('name')->get()->toArray();
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
