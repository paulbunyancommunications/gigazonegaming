<?php

namespace GameDisplay\RiotDisplay\API;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Client;
use Mockery\Exception;

class Api{

# Variables
#----------------------------------------------------------------------
    private $apiKey;
    private $Summoner;
    private $summonerID;
    private $Icon;
    private $LeagueV3Json;
    private $currentGameInfo;
    private $championId;
    private $championName;
    private $championImg;
    private $currentGameStatus = false;

    #Request Counter
    private $counter = 0;



# Constructor
#----------------------------------------------------------------------
    function __construct($SummonerName, $ApiKey)
    {

        $this->Summoner = $SummonerName;

        #set up api client ready for requests
        $this->apiKey = $ApiKey;

        #intailize summoner info for requests
        $this->setSummonerID();
        #States of players rank.
        $this->setLeagueV3Json();

    }


# Methods
#----------------------------------------------------------------------
    public function ApiRequest($Url, $counter = 0){
        #Set up client
        $client = new Client();
        $string = "";
        #Request Info From Api
        $request = new Request('Get', $Url);
        $response = $client->send($request);
        #If Request Goes Through Return Json Response, Else Try 10 Times then through exception.
        switch ((int)$response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody());
                break;
            case 404:
                return false;
                break;
            case 429:
                if ($counter > 2) {
                    throw new Exception("Calling Api Key Too Soon $this->apiKey summoner: $this->Summoner");
                }
                $counter++;
                sleep(1);
                return $this->ApiRequest($Url, $counter);
            case 503:
                throw new Exception("Riot's Api is Down" . $this->apiKey . "ID:" . $this->summonerID . " The Code:" . $response->getStatusCode() . ' Counter: ' . $this->counter);
                break;
            default:
                throw new Exception("Unknown Riot Api Error code:" . $response->getStatusCode() . " ApiKey: " . $this->apiKey);
                break;
        }
    }

    public function checkCurrentGameStatus(){
        $Url = 'https://na1.api.riotgames.com/observer-mode/rest/consumer/getSpectatorGameInfo/NA1/' . $this->summonerID . '?api_key=' . $this->apiKey;
        $Info = $this->ApiRequest($Url);

        if($Info){
            $this->currentGameStatus = true;
            $this->currentGameInfo = $Info;
        }

        return $this->currentGameStatus;
    }
    public function __sleep()
    {
        return array('Summoner', 'summonerID', 'apiKey', 'counter');
    }



# Setters
#----------------------------------------------------------------------
    public function setLeagueV3Json()
    {
        #Gets players states json
        $Url = 'https://na1.api.riotgames.com/lol/league/v3/positions/by-summoner/' . $this->summonerID . '?api_key=' . $this->apiKey;
        $Info = $this->ApiRequest($Url);

        $this->LeagueV3Json = $Info;
    }

    public function setSummonerID()
    {
        $Url = 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $this->Summoner . '?api_key='. $this->apiKey;
        $info = $this->ApiRequest($Url);
        if($info){
            try{
                $this->summonerID = $info->id;
            }catch( \Exception $e){
                if($this->counter > 10){
                    $this->summonerID = null;
                }
                else{
                    $this->setSummonerID();
                    throw new Exception("Summoner ID not found in json response for: $this->Summoner");
                }
                $this->counter++;
            }
        }
        else{
            throw new Exception("Summoner '$this->Summoner' is not a valid name in North America");
        }

        #sets summoner ID for further use with the api.


    }

    public function setChampionId(){
        foreach($this->currentGameInfo->participants as $player){
            if($player->summonerId == $this->summonerID){
                $this->championId = $player->championId;
            }
        }
    }

    Public function setChampionName($ChampionId){
        $Url = "https://na.api.riotgames.com/api/lol/static-data/na/v1.2/champion/". $ChampionId ."?api_key=" . $this->apiKey;
        $Info = $this->ApiRequest($Url);
        $this->championName = $Info->key;

    }

    public function setChampionIMG($ChampionName){

        $this->championImg = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . $ChampionName . "_0.jpg";
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

        #if player has a rank
        if($this->LeagueV3Json != []){
            foreach ($this->LeagueV3Json as $RankType){
                #if player has solo rank
                if($RankType->queueType == "RANKED_SOLO_5x5"){
                    $soloRankWinsLosses = $RankType->wins . " | " . $RankType->losses;
                    break;
                }
                #not solo ranked
                else{
                    $soloRankWinsLosses = "Unranked";
                }
            }
        }
        #player does not have a rank
        else{
            $soloRankWinsLosses = "Unranked";
        }
        return $soloRankWinsLosses;
    }

    public function getSoloRank(){
        $soloRank = "";

        #if player has a rank
        if($this->LeagueV3Json != []){
            foreach ($this->LeagueV3Json as $RankType){
                #if player has solo rank
                if($RankType->queueType == "RANKED_SOLO_5x5"){
                    $soloRank = $RankType->tier . " " . $RankType->rank;
                    break;
                }
                #not solo ranked
                else{
                    $soloRank = "Unranked";
                }
            }
        }
        #player does not have a rank
        else{
            $soloRank = "Unranked";
        }
        return $soloRank;
    }

    public function getFLEXRankedWinLoss(){


        $FLEXRankWinsLosses = "";

        #if player has a rank
        if($this->LeagueV3Json != []){
            foreach ($this->LeagueV3Json as $RankType){
                #if player has solo rank
                if($RankType->queueType == "RANKED_FLEX_SR"){
                    $FLEXRankWinsLosses = $RankType->wins . " | " . $RankType->losses;
                    break;
                }
                #not flex ranked
                else{
                    $FLEXRankWinsLosses = "Unranked";
                }
            }
        }
        #player does not have a rank
        else{
            $FLEXRankWinsLosses = "Unranked";
        }
        return $FLEXRankWinsLosses;
    }

    public function getFLEXRank(){

        $FLEXRank = "";

        #if player has a rank
        if($this->LeagueV3Json != []){
            foreach ($this->LeagueV3Json as $RankType){
                #if player has solo rank
                if($RankType->queueType == "RANKED_FLEX_SR"){
                    $FLEXRank = $RankType->tier . " " . $RankType->rank;
                    break;
                }
                #not flex ranked
                else{
                    $FLEXRank = "Unranked";
                }
            }
        }
        #player does not have a rank
        else{
            $FLEXRank = "Unranked";
        }
        return $FLEXRank;
    }

    public function getChampion(){
        $this->setChampionId();
        $this->setChampionName($this->championId);
        $this->setChampionIMG($this->championName);
        return $this->championImg;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }


}