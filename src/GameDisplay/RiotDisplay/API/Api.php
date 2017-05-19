<?php

namespace GameDisplay\RiotDisplay\API;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Client;

class Api{

    protected $client;
    protected $Summoner;
    protected $summonerID;
    protected $Icon;

    function __construct($SummonerName)
    {
        $this->Summoner = $SummonerName;
        $this->apiKey = $_ENV['RIOT_API_KEY'];
        $this->client = new Client();

    }

    public function getSummonerId(){
        $request = new Request('Get', 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $this->Summoner . '?api_key='. $this->apiKey);
        $response = $this->client->send($request);
        $Info = json_decode($response->getBody());

        #sets summoner ID for further use with the api.
        $this->summonerID = $Info->id;

        return $this->summonerID;
    }

    public function getSummonerIcon(){
        return "https://avatar.leagueoflegends.com/NA1/".$this->Summoner.".png";
    }


    public function getTeam($teamNumber){
        $apiKey = $_ENV['RIOT_API_KEY'];
        $summonerID = $this->staticSummoner; #ID for Game

        $request = new Request('GET', "https://na1.api.riotgames.com/observer-mode/rest/consumer/getSpectatorGameInfo/NA1/". $summonerID ."?api_key=" . $apiKey);
        $response = $this->client->send($request);
        $Info = json_decode($response->getBody());

        ///return team json occording to the number
    }

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