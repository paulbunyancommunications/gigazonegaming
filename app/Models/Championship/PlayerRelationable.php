<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 9/13/16
 * Time: 15:43
 */


namespace App\Models\Championship;

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





}