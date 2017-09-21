<?php

namespace RiotDisplay\API;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class api{

    protected $client;
    protected $staticSummoner;

    function __construct($staticSummoner)
    {
        $this->staticSummoner = $staticSummoner;
        $this->client = new Client();

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