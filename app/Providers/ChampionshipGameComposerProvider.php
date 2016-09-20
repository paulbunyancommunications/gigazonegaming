<?php

namespace App\Providers;

use App\Models\Championship\Game;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\PlayerRelationable;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use View;

class ChampionshipGameComposerProvider extends ServiceProvider
{
    use PlayerRelationable;
    protected $expiresAt;
    protected $expiredAt;
    protected $maxPlayers = 0;
    protected $connection = 'mysql_champ';
    protected $viewComposerElements = [];


    /**
     * Get a list of method returns
     * @param array $methodList
     * @return array
     */
    private function getViewComposerElements($methodList = [])
    {
        $return = [];
        foreach ($methodList as $method) {
            if (array_key_exists($method, $this->viewComposerElements)) {
                $return[$method] = $this->viewComposerElements[$method];
                continue;
            }
            $return[$method] = $this->{$method}();
        }

        return $return;
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['game.game'], function ($view) {
            extract($this->getViewComposerElements(['games']));
            $view->with('games', $games);
        });

        View::composer(['game.tournament'], function ($view) {
            extract($this->getViewComposerElements(['games','tournaments']));
            $view->with('games', $games)
                ->with('tournaments', $tournaments);
        });

        View::composer(['game.team'], function ($view) {
            extract($this->getViewComposerElements(['games','tournaments','getPlayersInfoBy','teams']));
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('players', $getPlayersInfoBy);
        });

        View::composer(['game.player'], function ($view) {
            extract($this->getViewComposerElements(['games','tournaments','getPlayersInfoBy','teams']));
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('players', $getPlayersInfoBy);
        });

        View::composer(['game.individualPlayer'], function ($view) {
            extract($this->getViewComposerElements(['games','tournaments','getPlayersInfoBy','teams']));
            $view->with('games', $games)
                ->with('tournaments', $tournaments)
                ->with('teams', $teams)
                ->with('individualPlayers', $this->individualPlayers($getPlayersInfoBy));
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
        $playerTeam = PlayerRelation::select(DB::raw("COUNT(relation_id) as team_count"), "relation_id as team_id")
            ->where('relation_type', '=', self::getTeamRoute())
            ->groupBy('team_id')
            ->get()
            ->toArray();

        $table = with(new Team)->getTable();
        $columns = \Schema::connection($this->connection)->getColumnListing($table);
        $select = [];
        for ($c = 0; $c < count($columns); $c++) {
            switch ($columns[$c]) {
                case ('id'):
                case ('name'):
                case ('emblem'):
                case ('verification_code'):
                case ('captain'):
                    array_push($select, $this->tableColumnAsTableColumn($table, $columns[$c]));
                    break;
                case ('tournament_id'):
                    array_push($select, $this->tableColumnAsColumn($table, $columns[$c]));
                    break;
            }
        }
        /** @var array $teams get teams list with selects above */
        $teams = call_user_func_array(array(Team::class, 'select'), $select)
            ->orderBy('name')
            ->get()
            ->toArray();

        /**
         * Here we get the amount of players possible per team
         * and we pass back a variable with a count of the team members.
         */
        foreach ($teams as $key => $team) {
            $maxPlayers = Tournament::where('tournaments.id', '=', $team['tournament_id'])->first();
            if(isset($maxPlayers->max_players) and $maxPlayers!='' and $maxPlayers!=null) {
                foreach ($playerTeam as $k => $p) {
                    if ($team['team_id'] == $p['team_id']) {
                        $teams[$key]['team_count'] = $p['team_count'];
                        $teams[$key]['team_max_players'] = $maxPlayers->max_players;
                    }
                    if ($playerTeam == []) {
                        $teams[$key]['team_count'] = 0;
                        $teams[$key]['team_max_players'] = $maxPlayers->max_players;
                    }
                }
            }else{
                $teams[$key]['team_count'] = 0;
                $teams[$key]['team_max_players'] = "0";
            }
        }
        return $teams;
    }

    /**
     * @todo What's going on is this method? How/where is it being used?
     * @param $players
     * @return mixed
     */
    public function individualPlayers($players)
    {

        dd($players);
        return $players;
    }

    /**
     * Get tournaments columns for view composer
     * Do not blindly get select columns from the database that a migration has not been run for
     *
     * @return array
     */
    public function tournaments()
    {
        $table = with(new Tournament)->getTable();
        $columns = \Schema::connection($this->connection)->getColumnListing($table);
        $select = [];
        for ($c = 0; $c < count($columns); $c++) {
            switch ($columns[$c]) {
                case ('name'):
                case ('id'):
                    array_push($select, $this->tableColumnAsTableColumn($table, $columns[$c]));
                    break;
                case ('game_id'):
                case ('max_players'):
                    array_push($select, $this->tableColumnAsColumn($table, $columns[$c]));
                    break;
            }
        }
        $tournaments = call_user_func_array(array(Tournament::class, 'select'), $select)
            ->orderBy('name')
            ->get()
            ->toArray();
        return $tournaments;
    }

    /**
     * @return mixed
     */
    public function games()
    {
        $table = with(new Game)->getTable();
        $columns = \Schema::connection($this->connection)->getColumnListing($table);
        $select = [];
        for ($c = 0; $c < count($columns); $c++) {
            switch ($columns[$c]) {
                case ('name'):
                case ('id'):
                case ('title'):
                case ('description'):
                case ('uri'):
                    array_push($select, $this->tableColumnAsTableColumn($table, $columns[$c]));
                    break;
            }
        }

        $games = call_user_func_array(array(Game::class, 'select'), $select)
            ->orderBy('name')
            ->get()
            ->toArray();
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
