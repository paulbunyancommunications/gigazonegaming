<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 9/13/16
 * Time: 15:43
 */


namespace App\Models\Championship;

use Illuminate\Support\Facades\DB;

trait PlayerRelationable
{
    public static function getTournamentRoute()
    {
        return "App\\Models\\Championship\\Tournament";
    }
    public static function getTeamRoute()
    {
        return "App\\Models\\Championship\\Team";
    }
    public function findPlayersRelations(){
        return $this->morphMany(
            PlayerRelation::class,
            'relation'
        );
    }

    /*
     * the function will check if there is a relation from the passed parameters
     * this function will accept 2 parameters and 2 of them are necessary
     * id => player_id => players.id
     * team_id or tournament_id => relation_id => teams or tournaments.id
     * from which we will select the third one team or tournament as type.
     */
    public static function doesThePlayerRelationExist($parameters){

        $relation = PlayerRelation::where("player_id", $parameters['id']);
        if(isset($parameters['team_id'])){
            return $relation->where("relation_id", $parameters['team_id'])
                ->where("relation_type", self::getTeamRoute())
                ->exists();
        }
        elseif(isset($parameters['tournament_id'])){
            return $relation->where("relation_id", $parameters['tournament_id'])
                ->where("relation_type", self::getTournamentRoute())
                ->exists();
        }
        return null;
    }
    /*
     * the intention of the function is to create a row on table for the passed relation
     * this function will accept 2 parameters and 2 of them are necessary
     * id => player_id => players.id
     * team_id or tournament_id => relation_id => teams or tournaments.id
     * from which we will select the third one team or tournament as type.
     */
    public static function createRelation($parameters){
        if(! PlayerRelationable::doesThePlayerRelationExist($parameters)) {
            $relation = new PlayerRelation();
            $relation->player_id = $parameters['id'];
            if (isset($parameters['team_id'])) {
                $relation->relation_id = $parameters['team_id'];
                $relation->relation_type = self::getTeamRoute();
                $relation->save();
            } elseif (isset($parameters['tournament_id'])) {
                $relation->relation_id = $parameters['tournament_id'];
                $relation->relation_type = self::getTournamentRoute();
                $relation->save();
            }
        }
    }
    public function findPlayerRelations($query, Player $player){
        return $query->whereHas('playerRelations', function ($query) use ($player) {
            $query->where('player_id', $player->id);
        });
    }
    public function hasPlayers(Player $player){
        return $this->findPlayersRelations()
            ->where('player_id', $player->id)
            ->exists();
    }



    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    private function playerRelationsToAnArrayOfObjectsOfTeamsAndTournaments()
    {
//        dd($this);
        $relations = PlayerRelation::where('player_id', '=', $this->id)->get();
//        dd($relations);
        $returnableArray = [];
        if ($relations != null and $relations != [] and $relations != '') {
//            dd($relations->toArray());
            $information = $relations->toArray();
            foreach ($information as $key => $someT) {
                $info = '';
//                dd("here");
//                dd($information);
                if ($someT['relation_type'] == self::getTournamentRoute()) {
                    if(!isset($returnableArray['tournaments'])){$returnableArray['tournaments']=[];}
                    $returnableArray['tournaments'][] = Tournament::where('id', '=', $someT['relation_id'])->get()->toArray()[0];
                } elseif ($someT['relation_type'] == self::getTeamRoute()) {
                    if(!isset($returnableArray['teams'])){$returnableArray['teams']=[];}
                    $returnableArray['teams'][] = Team::where('id', '=', $someT['relation_id'])->get()->toArray()[0];
                }else{
                    if(!isset($returnableArray['error'])){$returnableArray['error']=[];}
                    $returnableArray['error'][] = $someT;
                }
            }
        }
        return $returnableArray;
    }
    public function relationToTeamArray($id)
    {
        return Team::find($id)->get()->toArray();
    }

//    public function addPlayer(Player $player){
//        return $this->findPlayersRelations()
//            ->where('player_id', $player->id)
//            ->exists();
//    }

    /**
     * Display the specified resource.
     *
     * @param  can be a multidemsional array
     * key game for all games or one game
     * key tournament for all tournaments or one tournament
     * key team for all teams or one team
     * key player for all players or one player
     * key order_by for only one orderBy string value
     * @return array
     */
    public function getPlayersInfoBy($parameter = [])
    {
        $singlePlayers = false;
        $ga_array = false;
        $to_array = false;
        $te_array = false;
        $pl_array = false;
        $ga_value = "";
        $to_value = "";
        $te_value = "";
        $pl_value = "";
        $game = '';
        $tournament = '';
        $team = '';
        $player = '';
        $orderBy = '';
        if(isset($parameter["single_players"]) and $parameter["single_players"]){
            $singlePlayers = true;
        }
        if(is_array($parameter) and $parameter != [] or $parameter != null) {
            if(isset($parameter['game'])){
                $game = $parameter['game'];
            }
            if(isset($parameter['tournament'])){
                $tournament = $parameter['tournament'];
            }
            if(isset($parameter['team'])){
                $team = $parameter['team'];
            }
            if(isset($parameter['player'])){
                $player = $parameter['player'];
            }
            if(isset($parameter['order_by'])){
                $orderBy = $parameter['order_by'];
            }
        }
        if ($orderBy != "" and $orderBy != "---" and $orderBy != null and is_string($orderBy)) {
            $orderBy = trim($orderBy);
        }
        if ($player != [] and is_array($player)) {
            $pl_array = true;
        } elseif ($player != "" and $player != "---" and $player != null and (is_string($player) or is_numeric($player))) {
            $pl_value = trim($player);
        }
        if ($team != [] and is_array($team)) {
            $te_array = true;
        } elseif ($team != "" and $team != "---" and $team != null and (is_string($team) or is_numeric($team))) {
            $te_value = trim($team);
        }
        if ($tournament != [] and is_array($tournament)) {
            $to_array = true;
        } elseif ($tournament != "" and $tournament != "---" and $tournament != null and (is_string($tournament) or is_numeric($tournament))) {
            $to_value = trim($tournament);
        }
        if ($game != [] and is_array($game)) {
            $ga_array = true;
        } elseif ($game != "" and $game != "---" and $game != null and (is_string($game) or is_numeric($game))) {
            $ga_value = trim($game);
        }
//

        $player_query = call_user_func_array(array(Player::class, 'select'), $this->playerInfoSelect())
            ->leftJoin('player_relations AS team_relations', function ($join) {
                $join->on('players.id', '=', 'team_relations.player_id') //here we join player_relations but just the rows for Team
                ->where('team_relations.relation_type', '=', self::getTeamRoute());
            })
            ->leftJoin('player_relations AS tournament_relations', function ($join) {
                $join->on('players.id', '=', 'tournament_relations.player_id') //here we join player_relations but just the rows for Team
                ->where('tournament_relations.relation_type', '=', self::getTournamentRoute());
            })
            ->leftJoin('teams', 'team_relations.relation_id', '=' , 'teams.id')
            ->leftJoin('tournaments', 'tournament_relations.relation_id', '=' , 'tournaments.id')
            ->leftJoin('games', 'games.id', '=' , 'tournaments.game_id');

        //first, player
        if ($pl_array) {
            $or = false;
            foreach ($player as $key => $id) {
                if (is_array($id)) {
                    $id = $id['id'];
                } //check if it wasnt just an array of players ids and was an array of players
                if ($or) {
                    $player_query->orwhere('players.id', '=', $id);
                } else {
                    $or = true;
                    $player_query->where('players.id', '=', $id);
                }
            }
        } elseif ($pl_value != '') {
            $player_query->where('players.id', '=', $pl_value);
        }
        //second, team
        if ($te_array) {
            $or = false;
            foreach ($team as $key => $id) {
                if (is_array($id)) {
                    $id = $id['id'];
                } //check if it wasnt just an array of players ids and was an array of players
                if ($or) {
                    $player_query->orwhere('teams.id', '=', $id);
                } else {
                    $or = true;
                    $player_query->where('teams.id', '=', $id);
                }
            }
        } elseif ($te_value != '') {
            $player_query->where('teams.id', '=', $te_value);
        }

        //third, tournament
        if ($to_array) {
            $or = false;
            foreach ($tournament as $key => $id) {
                if (is_array($id)) {
                    $id = $id['id'];
                } //check if it wasnt just an array of players ids and was an array of players
                if ($or) {
                    $player_query->orwhere('tournaments.id', '=', $id);
                } else {
                    $or = true;
                    $player_query->where('tournaments.id', '=', $id);
                }
            }
        } elseif ($to_value != '') {
            $player_query->where('tournaments.id', '=', $to_value);
        }

        //fourth, game
        if ($ga_array) {
            $or = false;
            foreach ($game as $key => $id) {
                if (is_array($id)) {
                    $id = $id['id'];
                } //check if it wasnt just an array of players ids and was an array of players
                if ($or) {
                    $player_query->orwhere('games.id', '=', $id);
                } else {
                    $or = true;
                    $player_query->where('games.id', '=', $id);
                }
            }
        } elseif ($ga_value != '') {
            $player_query->where('games.id', '=', $ga_value);
        }

        if ($singlePlayers) {
            $player_query->where('teams.id', null);
        }
        if ($orderBy != '') {
            $player_query->orderBy($orderBy);
        }
        $players = $player_query->groupBy('player_id')->get()->toArray();



        $playerTeamCount = PlayerRelation::select(DB::raw("COUNT(player_id) as team_count"), "relation_id as team_id")->where('relation_type', '=', self::getTeamRoute())->groupBy('team_id')->get()->toArray();



        $teamIds = [];
        foreach ($playerTeamCount as $k => $t) {
            $teamIds[$t['team_id']] = $t['team_count'];
        }


        foreach ($players as $key => $player) {
            if (!array_key_exists($player['team_id'], $teamIds)) {
                $players[$key]['team_name'] = "S/HE Doesn't have a team or The Team Doesn't Exist Anymore!!!!!";
                $players[$key]['team_count'] = "x";
            } else {
                foreach ($teamIds as $k => $t) {
                    if ($player['team_id'] == $k) {
                        $players[$key]['team_count'] = $t;
                    }
                }
            }

        }
        return $players;
    }
    /**
     * Display the specified resource.
     *
     * @param  can be a multidemsional array
     * key game for all games or one game
     * key tournament for all tournaments or one tournament
     * key team for all teams or one team
     * key player for all players or one player
     * key order_by for only one orderBy string value
     * @return array
     */
    public function getSinglePlayersInfoBy($parameter = [])
    {
        if(isset($parameter['team'])){
            unset($parameter['team']);
        }
        $parameter['single_players'] = true;
        return $this->getPlayersInfoBy($parameter);
    }


    /**
     * Setup columns that the getPlayersInfoBy method should be selecting
     * @return array
     */
    public function playerInfoSelect()
    {
        $select = [];
        /** @var array $models model name list */
        $models = ['Tournament', 'Player', 'Team', 'Game'];
        $modelNameSpace = 'App\\Models\\Championship\\';
        for ($m=0; $m < count($models); $m++) {
            $thisModel = $modelNameSpace.$models[$m];
            $tableName = with(new $thisModel)->getTable();
            $columns = \Schema::connection($this->connection)->getColumnListing($tableName);
            switch ($models[$m]) {
                case ('Tournament'):
                    for ($c=0; $c < count($columns); $c++) {
                        switch ($columns[$c]) {
                            case ('id'):
                            case ('name'):
                                array_push($select, $this->tableColumnAsTableColumn($tableName, $columns[$c]));
                                break;
                            case ('max_players'):
                                array_push($select, $this->tableColumnAsColumn($tableName, $columns[$c]));
                                break;
                        }
                    }
                    unset($c);
                    break;
                case ('Player'):
                    for ($c=0; $c < count($columns); $c++) {
                        switch ($columns[$c]) {
                            case ('name'):
                            case ('username'):
                            case ('phone'):
                            case ('email'):
                                array_push($select, $this->tableColumnAsTableColumn($tableName, $columns[$c]));
                                break;
                            case ('id'):
                                array_push($select, $this->tableColumnAsColumn($tableName, $columns[$c]));
                                array_push($select, $this->tableColumnAsTableColumn($tableName, $columns[$c]));
                                break;
                        }
                    }
                    unset($c);
                    break;
                case ('Team'):
                    for ($c=0; $c < count($columns); $c++) {
                        switch ($columns[$c]) {
                            case ('id'):
                            case ('name'):
                            case ('captain'):
                            case ('emblem'):
                                array_push($select, $this->tableColumnAsTableColumn($tableName, $columns[$c]));
                                break;
                            case ('verification_code'):
                                array_push($select, $this->tableColumnAsColumn($tableName, $columns[$c]));
                                break;
                        }
                    }
                    unset($c);
                    break;
                case ('Game'):
                    for ($c=0; $c < count($columns); $c++) {
                        switch ($columns[$c]) {
                            case ('id'):
                            case ('name'):
                            case ('title'):
                            case ('description'):
                            case ('uri'):
                                array_push($select, $this->tableColumnAsTableColumn($tableName, $columns[$c]));
                                break;
                        }
                    }
                    unset($c);
                    break;
            }
        }

        return $select;
    }

    /**
     * return select column string as "tables.column as table_column"
     * @param null $table
     * @param null $column
     * @return string
     */
    public function tableColumnAsTableColumn($table = null, $column = null)
    {
        return $table . '.' . $column . ' as ' . str_singular($table) . '_' . $column;
    }

    /**
     * return select column string as "tables.column as column"
     * @param null $table
     * @param null $column
     * @return string
     */
    public function tableColumnAsColumn($table = null, $column = null)
    {
        return $table . '.' . $column . ' as ' . $column;
    }
}
