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

        $player_team_count = PlayerRelation::select(DB::raw("COUNT(relation_id) as team_count"), "relation_id as team_id")->where('relation_type','like','%Team')->groupBy('team_id')->get()->toArray();

        $teams = $this->teams($player_team_count);

        $players = $this->players($teams, $player_team_count);

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
    public function teams($player_team)
    {
        $teams = Team::orderBy('name')->get()->toArray();

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
        return $teams;
    }

    /**
     * @param $teams
     * @return mixed
     */
    public function players($player_team_count)
    {
        $teamIds = [];
        foreach ($player_team_count as $k => $t) {
            $teamIds[$t['id']] = $t['team_count'];
        }


        //todo Check team player variable below,
        // make a model function from it and make it work with so it can be call and be more specific as well
        // I mean, specify a game, tournament, game or even player


        $players = Player::
            join('player_relations', 'players.id', '=', 'player_relations.player_id')
            ->join('teams', function($join2)
            {
                $join2->on('teams.id', '=', 'player_relations.relation_id');
                $join2->where('player_relations.relation_type','like', "%Team");
            })
            ->join('tournaments','tournaments.id','=', 'teams.tournament_id')
            ->join('games','games.id','=', 'tournaments.game_id')
            ->select(
                'players.id as id',
                'players.id as player_id',
                'players.email',
                'players.username',
                'players.name',
                'players.phone',
                'teams.verification_code as verification_code',
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

        foreach ($players as $key => $player) {
            if(!array_key_exists($player['team_id'], $teamIds)){
                $players[$key]['team_name'] = " Doesn't Exist Anymore!!!!!";
                $players[$key]['team_count'] = "x";
            }else {
                foreach ($teamIds as $k => $t) {
                    if ($t['team_id'] == $k) {
                        dd("here");
                        $players[$key]['team_count'] = $t;
                    }
                }
            }

        }
        return $players;
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
        $tournamentss = Tournament::orderBy('name')->get()->toArray();
        return $tournamentss;
    }

    /**
     * @return mixed
     */
    public function games()
    {
        $games = Game::orderBy('name')->get()->toArray();
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
