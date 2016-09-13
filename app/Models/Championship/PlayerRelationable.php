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
    public function playerRelations(){
        return $this->morphMany(
            PlayerRelation::class,
            'relation'
        );
    }
    public function scopePlayerRelationBy($query, Player $player){
        return $query->whereHas('playerRelations', function ($query) use ($player) {
            $query->where('player_id', $player->id);
        });
    }
    public function hasPlayerRelationBy(Player $player){
        return $this->playerRelations()
            ->where('player_id', $player->id)
            ->exists();
    }





}