<?php

namespace GameDisplay\RiotDisplay\API;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Client;
use Mockery\Exception;

class Api{

# Variables
#----------------------------------------------------------------------
    private $apiKey;
    private $summoner;
    private $summonerID;
    private $LeagueV3Json;
    private $currentGameInfo;
    private $championId;
    private $championName;
    private $championImg;
    private $currentGameStatus = false;
    private $IconId;
    private $DDragonVersion;

    #Request Counter
    private $counter = 0;



# Constructor
#----------------------------------------------------------------------
    /**
     * Api constructor.
     * @param $ApiKeyNumber
     */
    function __construct()
    {
        #set up api key ready for requests
        $this->setApiKey();
    }


# Methods
#----------------------------------------------------------------------

    public function apiRequest($Url, $counter = 0){
        #Set up client
        $client = new Client();
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
                    throw new Exception("Calling Api Key Too Soon $this->apiKey summoner: $this->summoner");
                }
                $counter++;
                sleep(1);
                return $this->apiRequest($Url, $counter);
            case 503:
                throw new Exception("Riot's Api is Down" . $this->apiKey . "ID:" . $this->summonerID . " The Code:" . $response->getStatusCode() . ' Counter: ' . $this->counter);
                break;
            default:
                throw new Exception("Unknown Riot Api Error code:" . $response->getStatusCode() . " ApiKey: " . $this->apiKey . " Summoner: " .$this->summoner);
                break;
        }
    }

    /**Injects summoner and profiles this api for further requests
     * @param $summoner
     */
    public function injectSummoner($summoner){
        $this->setSummoner($summoner);

        #intailize summoner info for requests
        $this->requestSummonerIDAndIconId();

        #get the most updated version to grab icons from
        $this->requestDDragonVersion();

        #Grabes json array from states api for
        $this->requestLeagueV3Json();

    }

    public function requestDDragonVersion(){
        #Gets players states json
        $Url = 'https://ddragon.leagueoflegends.com/api/versions.json';
        $info = $this->apiRequest($Url);

        $this->DDragonVersion = $info[0];
    }

    public function requestLeagueV3Json()
    {
        #Gets players states json
        $Url = 'https://na1.api.riotgames.com/lol/league/v3/positions/by-summoner/' . $this->summonerID . '?api_key=' . $this->apiKey;
        $Info = $this->apiRequest($Url);

        $this->LeagueV3Json = $Info;
    }

    public function requestSummonerIDAndIconId()
    {
        $Url = 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $this->summoner . '?api_key='. $this->apiKey;
        $info = $this->apiRequest($Url);
        if($info){
            try{
                $this->summonerID = $info->id;
                $this->IconId = $info->profileIconId;
            }catch( \Exception $e){
                $this->summonerID = null;
                $this->IconId = null;
                throw new Exception("Summoner ID and IconId not found in json response for: $this->summoner");
            }
        }
        else{
            throw new Exception("Summoner '$this->summoner' is not a valid name in North America");
        }

        #sets summoner ID for further use with the api.


    }

    public function checkCurrentGameStatus(){
        $Url = 'https://na1.api.riotgames.com/lol/spectator/v3/active-games/by-summoner/' . $this->summonerID . '?api_key=' . $this->apiKey;
        $Info = $this->apiRequest($Url);

        if($Info){
            $this->currentGameStatus = true;
            $this->currentGameInfo = $Info;
        }

        return $this->currentGameStatus;
    }

    public function __sleep()
    {
    return array('summoner', 'summonerID', 'apiKey', 'counter');
    }


# Setters
#----------------------------------------------------------------------
    /**
     * @param mixed $apiKey
     */
    public function setApiKey()
    {
        $this->apiKey = env("RIOT_API_KEY", 'null');
    }
    /**
    * @param mixed $summoner
    */
    public function setSummoner($summoner)
    {
        $this->summoner = $summoner;
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
        $Info = $this->apiRequest($Url);
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


    /**
     * @return mixed
     */
    public function getIconId()
    {
        return $this->IconId;
    }

    public function getSummonerIcon(){

        return "http://ddragon.leagueoflegends.com/cdn/$this->DDragonVersion/img/profileicon/$this->IconId.png";
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

    /**
     * @return mixed
     */
    public function getLeagueV3Json()
    {
        return $this->LeagueV3Json;
    }

    /**
     * @return mixed
     */
    public function getSummoner()
    {
        return $this->summoner;
    }

    /**
     * @return mixed
     */
    public function getDDragonVersion()
    {
        return $this->DDragonVersion;
    }


}