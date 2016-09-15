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
    public function findPlayersRelations(){
        return $this->morphMany(
            PlayerRelation::class,
            'relation'
        );
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
        $player_query = Player::leftJoin('player_relations AS team_relations', function ($join) {
            $join->on('players.id', '=', 'team_relations.player_id') //here we join player_relations but just the rows for Team
                ->where('team_relations.relation_type', 'like', '%Team');
            })
            ->leftJoin('player_relations AS tournament_relations', function ($join) {
            $join->on('players.id', '=', 'tournament_relations.player_id') //here we join player_relations but just the rows for Team
                ->where('tournament_relations.relation_type', 'like', '%Tournament');
            })
            ->leftJoin('teams', 'team_relations.relation_id', '=' , 'teams.id')
            ->leftJoin('tournaments', 'tournament_relations.relation_id', '=' , 'tournaments.id')
            ->leftJoin('games', 'games.id', '=' , 'tournaments.game_id')
            ->select(
                'players.id as id',
                'players.id as player_id',
                'players.name as player_name',
                'players.username as player_username',
                'players.email as player_email',
                'players.phone as player_phone',
                'teams.id as team_id',
                'teams.name as team_name',
                'teams.captain as team_captain',
                'teams.verification_code as verification_code',
                'teams.emblem as team_emblem',
                'tournaments.id as tournament_id',
                'tournaments.name as tournament_name',
                'tournaments.max_players as max_players',
                'games.id as game_id',
                'games.name as game_name',
                'games.title as game_title',
                'games.description as game_description',
                'games.uri as game_uri'
            );

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
        if ($orderBy != '') {
            $player_query->orderBy($orderBy);
        }
        $players = $player_query->groupBy('player_id')->get()->toArray();



        $player_team_count = PlayerRelation::select(DB::raw("COUNT(player_id) as team_count"), "relation_id as team_id")->where('relation_type','like','%Team')->groupBy('team_id')->get()->toArray();



        $teamIds = [];
        foreach ($player_team_count as $k => $t) {
            $teamIds[$t['team_id']] = $t['team_count'];
        }


        foreach ($players as $key => $player) {
            if(!array_key_exists($player['team_id'], $teamIds)){
                $players[$key]['team_name'] = "S/HE Doesn't have a team or The Team Doesn't Exist Anymore!!!!!";
                $players[$key]['team_count'] = "x";
            }else {
                foreach ($teamIds as $k => $t) {
                    if ($player['team_id'] == $k) {
                        $players[$key]['team_count'] = $t;
                    }
                }
            }

        }
        return $players;
    }




}