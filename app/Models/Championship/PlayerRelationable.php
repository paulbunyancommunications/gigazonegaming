<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 9/13/16
 * Time: 15:43
 */


namespace App\Models\Championship;

use Doctrine\DBAL\Schema\Schema;
use Illuminate\Support\Facades\DB;

trait PlayerRelationable
{
    public static function routables()
    {
        return ['Game','Team','Tournament','Player'];
    }

    public static function getGameRoute()
    {
        return 'App\\Models\\Championship\\Game';
    }

    public static function getPlayerRoute()
    {
        return 'App\\Models\\Championship\\Player';
    }
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
    /**
     * Get an 8 chars random string
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public static function generateRandomCode()
    {
        return str_random(8);
    }

    /*
     * the function will check if there is a relation from the passed parameters
     * this function will accept 2 parameters and 2 of them are necessary
     * id => player => players.id
     * team_id or tournament => relation_id => teams or tournaments.id
     * from which we will select the third one team or tournament as type.
     */
    public static function doesThePlayerRelationExist($parameters){
        if(isset($parameters['team']) and $parameters['team']!=='---'){ //check if tge team parameter exists
            $tour_id = Team::where('id', '=' ,$parameters['team'])->pluck('tournament_id')->first();
            $tournamentTeams=[];
            if($tournament=Tournament::where('id', '=' ,$tour_id)->first()!=null
                and
                PlayerRelation::where('player_id','=',$parameters['player'])
                    ->where('relation_id','=',$tour_id)
                    ->where('relation_type','=',Tournament::class)->exists()
            ) { //if the player have been added to this tournament get all the teams for next check, else
                $tournamentTeams = Tournament::where('id', '=' ,$tour_id)->first()->teams()->get();
            }else{ //create a tournament relation and after that create the team relation that wasnt created in the previous if
                self::createRelation(['tournament'=> Team::where('id', '=', $parameters['team'])->pluck('tournament_id')->first(), 'player' => $parameters['player']]);
                $tournamentTeams = Tournament::where('id', '=' ,$tour_id)->first()->teams()->get();
            }
            foreach ($tournamentTeams as $key => $team) { //go through the relations of this player and check if any of the teams we pull has him or her in it. if so return true to not create a new relation
                if($team->hasPlayerID($parameters['player'])){
                    return true;
                }
            }
            return false;
        }

        if(isset($parameters['tournament']) and $parameters['tournament']!=='---'){
            $game_id = Tournament::where('id','=',$parameters['tournament'])->pluck('game_id')->first();
            if(Game::where('id','=',$game_id)
                        ->first() != null
                and
                !PlayerRelation::where('player_id','=',$parameters['player'])
                    ->where('relation_id','=',$game_id)
                    ->where('relation_type','=',Game::class)->exists()
            ){
                self::createRelation(['game'=> $game_id, 'player' => $parameters['player']]);
            }
            return Tournament::where('id','=',$parameters['tournament'])->first()->hasPlayerID($parameters['player']);
        }
        elseif(isset($parameters['game']) and $parameters['game']!=='---'){
            return Game::where('id','=',$parameters['game'])->first()->hasPlayerID($parameters['player']);
        }

        return true; //if there was something sent that it shouldnt be sent, return true so stops them from a false comparison.
    }

    protected static function prepParameters(&$parameters)
    {
        // Go over each of the player connected
        // models and check if the parameter
        // key passed is a instance of
        // that the given controller name.
        foreach (self::routables() as $routable) {
            if (array_key_exists(strtolower($routable), $parameters)
                && is_a($parameters[strtolower($routable)], "App\\Models\\Championship\\{$routable}", true)
            ) {
                $parameters[strtolower($routable)] = $parameters[strtolower($routable)]->id;
            }
        }
    }

    /*
     * the intention of the function is to create a row on table for the passed relation
     * this function will accept 2 parameters and 2 of them are necessary
     * id => player => players.id
     * team_id or tournament => relation_id => teams or tournaments.id
     * from which we will select the third one team or tournament as type.
     */
    public static function createRelation($parameters)
    {
        $ret = array(
            'game'=>array(
                'success'=>"",
                'fail'=>""
            ),
            'tournament'=>array(
                'success'=>"",
                'fail'=>""
            ),
            'team'=>array(
                'success'=>"",
                'fail'=>""
            ),
            'success'=>'',
            'fail'=>''
        );
        self::prepParameters($parameters);
        if (isset($parameters['game']) and !is_array($parameters['game'])) {
            if (!self::doesThePlayerRelationExist(['player' => $parameters['player'], 'game' => $parameters['game']])) {
                $relation = new PlayerRelation();
                $relation->player_id = $parameters['player'];
                $relation->relation_id = $parameters['game'];
                $relation->relation_type = Game::class;
                $relation->save();
                $ret['game']['success'] .= Game::where('id', '=', $parameters['game'])->pluck("name")->first();
//                $ret['game'] = "The game relation was successfully created.";
            }else{
                $ret['game']['fail'] .= Game::where('id', '=', $parameters['game'])->pluck("name")->first();
//                $ret['game'] = "Error: The game relation already existed.";
            }
        }elseif (isset($parameters['game']) and is_array($parameters['game'])){
            foreach ($parameters['game'] as $k => $v){
                if (!self::doesThePlayerRelationExist(['player' => $parameters['player'], 'game' => $v])) {
                    $relation = new PlayerRelation();
                    $relation->player_id = $parameters['player'];
                    $relation->relation_id = $v;
                    $relation->relation_type = Game::class;
                    $relation->save();
                    $ret['game']['success'] .= Game::where('id', '=', $v)->pluck("name")->first().", ";
                }else{
                    $ret['game']['fail'] .= Game::where('id', '=', $parameters['game'])->pluck("name")->first().", ";
//                $ret['game'] = "Error: The game relation already existed.";
                }
                $ret['game']['success'] = trim($ret['game']['success'], ', ');
                $ret['game']['fail'] = trim($ret['game']['fail'], ', ');
            }
        }
        if (isset($parameters['tournament']) and !is_array($parameters['tournament'])) {
            if (!self::doesThePlayerRelationExist(['player' => $parameters['player'], 'tournament' => $parameters['tournament']])) {
                $relation = new PlayerRelation();
                $relation->player_id = $parameters['player'];
                $relation->relation_id = $parameters['tournament'];
                $relation->relation_type = Tournament::class;
                $relation->save();
                $ret['tournament']['success'] = Tournament::where('id', '=', $parameters['tournament'])->pluck("name")->first();
            }else{
                $ret['tournament']['fail'] =  Tournament::where('id', '=', $parameters['tournament'])->pluck("name")->first();
            }
        }elseif (isset($parameters['tournament']) and is_array($parameters['tournament'])){
            foreach ($parameters['tournament'] as $k => $v){
                if (!self::doesThePlayerRelationExist(['player' => $parameters['player'], 'tournament' => $v])) {
                    $relation = new PlayerRelation();
                    $relation->player_id = $parameters['player'];
                    $relation->relation_id = $v;
                    $relation->relation_type = Tournament::class;
                    $relation->save();
                    $ret['tournament']['success'] .= Tournament::where('id', '=', $v)->pluck("name")->first().", ";
                }else{
                    $ret['tournament']['fail'] .= Tournament::where('id', '=', $v)->pluck("name")->first().", ";
                }
            }
            $ret['tournament']['success'] = trim($ret['tournament']['success'], ', ');
            $ret['tournament']['fail'] = trim($ret['tournament']['fail'], ', ');
        }
        if (isset($parameters['team']) and !is_array($parameters['team'])) {
            if (!self::doesThePlayerRelationExist(['player' => $parameters['player'], 'team' => $parameters['team']]) and Team::where('id','=',$parameters['team'])->first()->isTeamNotFull()) {
                $relation = new PlayerRelation();
                $relation->player_id = $parameters['player'];
                $relation->relation_id = $parameters['team'];
                $relation->relation_type = Team::class;
                $relation->save();
                $ret['team']['success'] = Team::where('id', '=', $parameters['team'])->pluck("name")->first();
            }else{
                $it_is_full = '';
                if(Team::where('id','=',$parameters['team'])->first()->isTeamFull()){$it_is_full = ' (the team '.Team::where('id','=',$parameters['team'])->first()->name.' is full)';}
                $ret['team']['fail'] = Team::where('id', '=', $parameters['team'])->pluck("name")->first().$it_is_full;
            }
        }elseif (isset($parameters['team']) and is_array($parameters['team'])){
            foreach ($parameters['team'] as $k => $v){
                if (!self::doesThePlayerRelationExist(['player' => $parameters['player'], 'team' => $v]) and Team::where('id','=',$v)->first()->isTeamNotFull()) {
                    $relation = new PlayerRelation();
                    $relation->player_id = $parameters['player'];
                    $relation->relation_id = $v;
                    $relation->relation_type = Team::class;
                    $relation->save();
                    $ret['team']['success'] .= Team::where('id', '=', $v)->pluck("name")->first().", ";
                }else{
                    $it_is_full = '';
                    if(Team::where('id','=',$v)->first()->isTeamFull()){$it_is_full = ' (the team '.Team::where('id','=',$v)->first()->name.' is full)';}
                    $ret['team']['fail'] .= Team::where('id', '=', $v)->pluck("name")->first()."$it_is_full, ";
                }
                $ret['team']['success'] = trim($ret['team']['success'], ', ');
                $ret['team']['fail'] = trim($ret['team']['fail'], ', ');
            }
        }
        if($ret['team']['success']=='') {
            unset($ret['team']['success']);
        }else{
            $ret['success'] .= "Teams: ".$ret['team']['success'].". ";
        }
        if($ret['team']['fail']=='') {
            unset($ret['team']['fail']);
        }else{
            $ret['fail'] .= "Teams: ".$ret['team']['fail'].". ";
        }
        if($ret['tournament']['success']=='') {
            unset($ret['tournament']['success']);
        }else{
            $ret['success'] .= "Tournaments: ".$ret['tournament']['success'].". ";
        }
        if($ret['tournament']['fail']=='') {
            unset($ret['tournament']['fail']);
        }else{
            $ret['fail'] .= "Tournaments: ".$ret['tournament']['fail'].". ";
        }
        if($ret['game']['success']=='') {
            unset($ret['game']['success']);
        }else{
            $ret['success'] .= "Games: ".$ret['game']['success'].". ";
        }
        if($ret['game']['fail']=='') {
            unset($ret['game']['fail']);
        }else{
            $ret['fail'] .= "Games: ".$ret['game']['fail'].". ";
        }

        if($ret['fail'] ==''){
            unset($ret['fail']);
        }
        if($ret['success'] ==''){
            unset($ret['success']);
        }

            unset($ret['game']);
            unset($ret['tournament']);
            unset($ret['team']);

        return $ret;
    }
    public function findPlayerRelations($query, $player){
        return $query->whereHas('playerRelations', function ($query) use ($player) {
            $query->where('player_id', $player->id);
        });
    }
    public function hasPlayer(Player $player){
        return $this->findPlayersRelations()
            ->where('player_id', $player->id)
            ->exists();
    }
    public function hasPlayerId($playerID){
        return $this->findPlayersRelations()
            ->where('player_id', $playerID)
            ->exists();
    }

    public static function playersRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames($filter = [])
    {
        $players = Player::all();
        foreach ($players as $k => $player){
            $players[$k]=$player->playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames();
        }
        return $players;
    }

    /**
     * Get the tournament in which the team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames($filter = [])
    {
        if(isset($this) and isset($this->id)) {
            $relations = PlayerRelation::where('player_id', '=', $this->id);
            if ($filter != [] and is_array($filter)) {
                if (isset($filter['team'])) {
                    $relations->where('relation_id', '=', $filter['team'])
                        ->where('relation_type', '=', Team::class);
                } elseif (isset($filter['tournament'])) {
                    $relations->where('relation_id', '=', $filter['tournament'])
                        ->where('relation_type', '=', Tournament::class);
                } elseif (isset($filter['game'])) {
                    $relations->where('relation_id', '=', $filter['game'])
                        ->where('relation_type', '=', Game::class);
                }
            }
            $relations = $relations->get();
            $returnableArray = $this->attributesToArray();

            if ($relations != null and $relations != [] and $relations != '') {
                $information = $relations->toArray();
                foreach ($information as $key => $someT) {
                    if ($someT['relation_type'] == Tournament::class) {
                        if (!isset($returnableArray['tournaments'])) {
                            $returnableArray['tournaments'] = [];
                        }
                        $returnableArray['tournaments'][] = Tournament::where('id', '=', $someT['relation_id'])->get()->toArray()[0];
                    } elseif ($someT['relation_type'] == Team::class) {
                        if (!isset($returnableArray['teams'])) {
                            $returnableArray['teams'] = [];
                        }
                        $returnableArray['teams'][] = Team::where('id', '=', $someT['relation_id'])->get()->toArray()[0];
                    } elseif ($someT['relation_type'] == Game::class) {
                        if (!isset($returnableArray['games'])) {
                            $returnableArray['games'] = [];
                        }
                        $returnableArray['games'][] = Game::where('id', '=', $someT['relation_id'])->get()->toArray()[0];
                    } else {
                        if (!isset($returnableArray['error'])) {
                            $returnableArray['error'] = [];
                        }
                        $returnableArray['error'][] = $someT;
                    }
                }
            }
            return $returnableArray;
        }
        return false;
    }
    public function relationToTeamArray($id)
    {
        return Team::find($id)->get()->toArray();
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
    public function getPlayersInfoBy($parameter = []){

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
            if ($orderBy != "" and $orderBy != "---" and $orderBy != null) {
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

        $player_query= Player::rightJoin('player_relations AS tore', function($join){
                $join->on('tore.player_id','=','players.id')
                    ->where('tore.relation_type', '=', Tournament::class);
            })
            ->leftJoin('tournaments', function($join){
                $join->on('tore.relation_id','=','tournaments.id');
            })
            ->leftJoin('games', function($join){
                $join->on('tournaments.game_id','=','games.id');
            })
            ->leftJoin('player_relations AS tere', function($join){
                $join->on('tere.player_id','=','players.id')
                    ->where('tere.relation_type', '=', Team::class);
            })
            ->leftJoin('teams', function($join){
                $join->on('tere.relation_id','=','teams.id');
            });
//

            //first, player
            if ($pl_array) {
                $player_query->where(function($player_query) use ($player)
                    {
                    $or = false;
                    foreach ($player as $key => $id) {
                        if (is_array($id)) {
                            $id = $id['id'];
                        } //check if it wasnt just an array of players ids and was an array of players
                        if ($or) {
                            $player_query->orwhere('players.id', $id);
                        } else {
                            $or = true;
                            $player_query->where('players.id',  $id);
                        }
                    }

                });
            } elseif ($pl_value != '') {
                $player_query->where('players.id',  $pl_value);
            }
            //second, team
            if ($te_array) {
                $player_query->where(function($player_query) use ($team) {
                    $or = false;
                    foreach ($team as $key => $id) {
                        if (is_array($id)) {
                            $id = $id['id'];
                        } //check if it wasnt just an array of players ids and was an array of players
                        if ($or) {
                            $player_query->orwhere('teams.id', $id);
                        } else {
                            $or = true;
                            $player_query->where('teams.id', $id);
                        }
                    }
                });
            } elseif ($te_value != '') {
                $player_query->where('teams.id', $te_value);
            }

            //third, tournament
            if ($to_array) {
                $player_query->where(function($player_query) use ($tournament) {
                    $or = false;
                    foreach ($tournament as $key => $id) {
                        if (is_array($id)) {
                            $id = $id['id'];
                        } //check if it wasnt just an array of players ids and was an array of players
                        if ($or) {
                            $player_query->orwhere('tournaments.id', $id);
                        } else {
                            $or = true;
                            $player_query->where('tournaments.id', $id);
                        }
                    }
                });
            } elseif ($to_value != '') {
                $player_query->where('tournaments.id', $to_value);
            }

            //fourth, game
            if ($ga_array) {
                $player_query->where(function($player_query) use ($game) {
                    $or = false;
                    foreach ($game as $key => $id) {
                        if (is_array($id)) {
                            $id = $id['id'];
                        } //check if it wasnt just an array of players ids and was an array of players
                        if ($or) {
                            $player_query->orwhere('games.id', $id);
                        } else {
                            $or = true;
                            $player_query->where('games.id', $id);
                        }
                    }
                });
            } elseif ($ga_value != '') {
                $player_query->where('games.id', $ga_value);
            }
        $player_query->addSelect(
            'players.id as id',
            'players.id as player_id',
            'players.username as player_username',
            'players.name as player_name',
            'players.email as player_email',
            'players.phone as player_phone',
            'players.user_id as player_user_id',
            'tore.player_id as to_pl_id',
            'tore.relation_id as to_re_id',
            'tournaments.id as tournament_id',
            'tournaments.game_id',
            'tournaments.name as tournament_name',
            'tournaments.max_players as team_max_players',
            'tere.player_id as te_pl_id',
            'tere.relation_id as te_re_id',
            'teams.id as team_id',
            'teams.name as team_name',
            'teams.verification_code as team_verification_code',
            'teams.emblem as team_emblem',
            'teams.captain as team_captain',
            'games.id as game_id',
            'games.name as game_name',
            'games.title as game_title',
            'games.description as game_description',
            'games.uri as game_uri'
        );
        $player_query->whereNotNull('players.id');
        $player_query->whereNotNull('tournaments.id');
        $player_query->whereNotNull('games.id');
        if ($orderBy != '') {
            $player_query->orderBy($orderBy);
        }
        $player_query->groupBy('tore.player_id');

        $players =  $player_query->get()->toArray();



        $playerTeamCount = PlayerRelation::select(DB::raw("COUNT(player_id) as team_count"), "relation_id as team_id")->where('relation_type', '=', Team::class)->groupBy('team_id')->get()->toArray();


//        dd($playerTeamCount);

        $teamIds = [];
        foreach ($playerTeamCount as $k => $t) {
            $teamIds[$t['team_id']] = $t['team_count'];
        }

        foreach ($players as $key => $player) {
            if (!isset($player['team_id']) or !array_key_exists($player['team_id'], $teamIds)) {
                $players[$key]['team_name'] = null;
                $players[$key]['team_count'] = null;
                $players[$key]['team_id'] = null;
            } else {
                foreach ($teamIds as $k => $t) {
                    if ($player['team_id'] == $k) {
                        $players[$key]['team_count'] = $t;
                    }
                }
            }
            unset($players[$key]['te_pl_id']);
            unset($players[$key]['te_re_id']);
            unset($players[$key]['to_re_id']);
            unset($players[$key]['to_pl_id']);
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
        if(isset($parameter) and isset($parameter['team'])){
            unset($parameter['team']);
        }
        $parameter['single_players'] = true;
        return $this->getPlayersInfoBy($parameter);
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
    public function getTeamPlayersInfoBy($parameter = [])
    {
        $parameter['order_by'] = 'team_name';
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
            if($models[$m] = 'TournamentRelation' or $models[$m] = 'TeamsRelation'){
                $thisModel = $modelNameSpace . 'PlayerRelation';
            }else {
                $thisModel = $modelNameSpace . $models[$m];
            }
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
