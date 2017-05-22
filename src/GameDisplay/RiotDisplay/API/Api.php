<?php

namespace GameDisplay\RiotDisplay\API;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Client;
use Mockery\Exception;

class Api{

# Variables
#----------------------------------------------------------------------
    protected $client;
    protected $Summoner;
    protected $summonerID;
    protected $Icon;
    protected $LeagueV3Json;

# Constructor
#----------------------------------------------------------------------
    function __construct($SummonerName)
    {

        $this->Summoner = $SummonerName;

        #set up api client ready for requests
        $this->apiKey = $_ENV['RIOT_API_KEY'];
        $this->client = new Client();

        #intailize summoner info for requests
        $this->setSummonerID();

        #States of players rank.
        $this->setLeagueV3Json();

    }

# Setters
#----------------------------------------------------------------------
    public function setLeagueV3Json()
    {
        #Gets players states json
        $request = new Request('Get', 'https://na1.api.riotgames.com/lol/league/v3/positions/by-summoner/' . $this->summonerID . '?api_key=' . $this->apiKey);
        $response = $this->client->send($request);
        $Info = json_decode($response->getBody());
        $this->LeagueV3Json = $Info;
    }

    public function setSummonerID()
    {
        $request = new Request('Get', 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $this->Summoner . '?api_key='. $this->apiKey);
        $response = $this->client->send($request);
        $Info = json_decode($response->getBody());
        #sets summoner ID for further use with the api.
        $this->summonerID = $Info->id;
    }

# Getters
#----------------------------------------------------------------------
    public function getSummonerId(){
        return $this->summonerID;
    }

    public function getSummonerIcon(){
        return "https://avatar.leagueoflegends.com/NA1/".$this->Summoner.".png";
    }

    public function getSoloRankedWinLoss(){
        $soloRankWinsLosses = "";

        foreach ($this->LeagueV3Json as $RankType){
            if($RankType->queueType == "RANKED_SOLO_5x5"){
                $soloRankWinsLosses = $RankType->wins . " | " . $RankType->losses;
            }
            elseif ($soloRankWinsLosses == ""){
                $soloRankWinsLosses = "Unranked";
            }
        }
        return $soloRankWinsLosses;
    }

    public function getSoloRank(){
        $soloRank = "";

        foreach ($this->LeagueV3Json as $RankType){
            if($RankType->queueType == "RANKED_SOLO_5x5"){
                $soloRank = $RankType->tier . " " . $RankType->rank;
            }
            elseif ($soloRank == ""){
                $soloRank = "Unranked";
            }
        }

        return $soloRank;
    }

    public function getFLEXRankedWinLoss(){
        $FLEXRankWinsLosses = "";

        foreach ($this->LeagueV3Json as $RankType){
            if($RankType->queueType == "RANKED_FLEX_SR"){
                $FLEXRankWinsLosses = $RankType->wins . " | " . $RankType->losses;
            }
            elseif ($FLEXRankWinsLosses == ""){
                $FLEXRankWinsLosses = "Unranked";
            }

        }

        return $FLEXRankWinsLosses;
    }

    public function getFLEXRank(){
        $FLEXRank = "";

        foreach ($this->LeagueV3Json as $RankType){
            if($RankType->queueType == "RANKED_FLEX_SR"){
                $FLEXRank = $RankType->tier . " " . $RankType->rank;
            }
            elseif ($FLEXRank == ""){
                $FLEXRank = "Unranked";
            }
        }

        return $FLEXRank;
    }

#These will be called every 2 seconds till this info has been grabbed successfully
    Public function getChampionName($ChampionId){
        $apiKey = $_ENV['RIOT_API_KEY'];

        $request = new Request('GET', "https://na.api.riotgames.com/api/lol/static-data/na/v1.2/champion/". $ChampionId ."?api_key=" . $apiKey);
        $response = $this->client->send($request);
        $Info = json_decode($response->getBody());

        ///return champion name

    }

    public function getChampionIMG($ChampionName){

        return "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . $ChampionName . "_0.jpg";
    }


}