<?php

namespace GameDisplay\RiotDisplay\API;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Client;
use Mockery\Exception;
use Illuminate\Support\Facades\Cache;

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
    private $championMasteries;

    #Request Counter
    private $counter = 0;



# Constructor
#----------------------------------------------------------------------
    /**
     * Api constructor.
     */
    function __construct()
    {
        #set up api key ready for requests
        $this->setApiKey();
    }


# Methods
#----------------------------------------------------------------------

    /**All Request pass through this method and returns json encoded
     * @param $Url
     * @param int $counter
     * @return array|bool|mixed|object
     */
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
                    throw new Exception("Calling Api Key Too Soon summoner: $this->summoner");
                }
                $counter++;
                sleep(1);
                return $this->apiRequest($Url, $counter);
            case 503:
                throw new Exception("Riot's Api is Down ID:" . $this->summonerID . " The Code:" . $response->getStatusCode() . ' Counter: ' . $this->counter);
                break;
            default:
                throw new Exception("Unknown Riot Api Error code:" . $response->getStatusCode() . " Summoner: " .$this->summoner);
                break;
        }
    }

    /**Injects summoner and profiles this api for further requests
     * @param $summoner, $preFill
     */
    public function injectSummoner($summoner, $preFill){
        $this->setSummoner($summoner);

        #intailize summoner info for requests
        $this->requestSummonerIDAndIconId();
        If($preFill){
            #get the most updated version to grab icons from
            $this->requestDDragonVersion();

            #Grabes json array from states api for
            $this->requestLeagueV3Json();

            #Grabes json data for Champion Masteries
            $this->requestChampionMasterData();
        }
    }

    /**
     *Gets Current version of Data Dragon so that we can grabe the most updated images from there URL
     */
    public function requestDDragonVersion(){
        #Gets players states json
        $Url = 'https://ddragon.leagueoflegends.com/api/versions.json';
        $info = $this->apiRequest($Url);

        $this->DDragonVersion = $info[0];
    }

    /**
     * Gets the initial summoner static data with in one request to then be parsed latter by
     * getSoloRankedWinLoss(), getSoloRank(), getFLEXRankedWinLoss(), getFLEXRank()
     */
    public function requestLeagueV3Json()
    {
        if(Cache::has($this->summonerID.'LeagueV3Data')){
            $this->LeagueV3Json = Cache::get($this->summonerID.'LeagueV3Data');
        }else{
            #Gets players states json
            $Url = 'https://na1.api.riotgames.com/lol/league/v3/positions/by-summoner/' . $this->summonerID . '?api_key=' . $this->apiKey;
            $Info = $this->apiRequest($Url);
            Cache::put($this->summonerID.'LeagueV3Data', $Info, 120);
            $this->LeagueV3Json = $Info;
        }

    }

    /**
     *When the api is injected this method is called to get the initial ID and Icon ID need to make requests for the summoner
     */
    public function requestSummonerIDAndIconId()
    {
        if(!isset($this->summoner)){
            throw new Exception("Set summoner before calling this method");
        }
        if(Cache::has($this->summoner.'SummonerData')) {
            $info = Cache::get($this->summonerID.'SummonerData');
        }
        else{
            $Url = 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $this->summoner . '?api_key='. $this->apiKey;
            $info = $this->apiRequest($Url);
            Cache::put($this->summonerID.'SummonerData', $info, 90);
        }
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

    public function requestChampionMasterData(){
        if(Cache::has($this->summonerID.'MasterieData')){
            $this->championMasteries = Cache::get($this->summonerID.'MasterieData');
        }else{
            $Url = 'https://na1.api.riotgames.com/lol/champion-mastery/v3/champion-masteries/by-summoner/' . $this->summonerID . '?api_key=' . $this->apiKey;
            $Info = $this->apiRequest($Url);
            if($Info === []){
                $Info = false;
            }
            Cache::put($this->summonerID.'MasterieData', $Info, 120);
            $this->championMasteries = $Info;
        }
    }

    /**
     * Returns True if summoner is in game
     * @return bool
     */
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
        $this->apiKey = env("RIOT_API_KEY1", 'null');
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

    /**
     * @param $ChampionId
     */
    Public function setChampionName($ChampionId){
        if(Cache::has($ChampionId . 'Name')){
            $this->championName = Cache::get($ChampionId . 'Name');
        }else{
            $Url = "https://na1.api.riotgames.com/lol/static-data/v3/champions/". $ChampionId ."?api_key=" . $this->apiKey;
            $Info = $this->apiRequest($Url);
            Cache::put($ChampionId . 'Name', $Info->key, 1440);
            $this->championName = $Info->key;
        }
    }

    /**
     * @param $ChampionName
     */
    public function setChampionIMG($ChampionName){

        $this->championImg = "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . $ChampionName . "_0.jpg";
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

    /**
     * @return string
     */
    public function getSummonerIcon(){

        return "http://ddragon.leagueoflegends.com/cdn/$this->DDragonVersion/img/profileicon/$this->IconId.png";
    }

    /**
     * @return string
     */
    public function getSoloRankedWinLoss(){
        $soloRankWinsLosses = "";
        if(!isset($this->LeagueV3Json)) {
            throw new Exception("LeagueV3Json data is not set. Make sure to call requestLeagueV3Json() before getting this data");
        }
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

    /**
     * @return string
     */
    public function getSoloRank(){
        $soloRank = "";
        if(!isset($this->LeagueV3Json)) {
            throw new Exception("LeagueV3Json data is not set. Make sure to call requestLeagueV3Json() before getting this data");
        }
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

    /**
     * @return string
     */
    public function getFLEXRankedWinLoss(){
        $FLEXRankWinsLosses = "";
        if(!isset($this->LeagueV3Json)) {
            throw new Exception("LeagueV3Json data is not set. Make sure to call requestLeagueV3Json() before getting this data");
        }
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

    /**
     * @return string
     */
    public function getFLEXRank(){
        $FLEXRank = "";
        if(!isset($this->LeagueV3Json)) {
            throw new Exception("LeagueV3Json data is not set. Make sure to call requestLeagueV3Json() before getting this data");
        }
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

    /**
     * @return mixed
     */
    public function getChampion(){
        if($this->currentGameStatus){
            $this->setChampionId();
            $this->setChampionName($this->championId);
            $this->setChampionIMG($this->championName);
            return $this->championImg;
        }
        throw new Exception("Call checkCurrentGameStatus before calling this method. $this->summoner is not in game");
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

    public function getChampionMasteries(){
        return $this->championMasteries;
    }

    /**
     *The top 3 champions in order from rank [1]to [3]
     * Returns two arrays. [0] is the champion Icon [1] is the champion splash art
     */
    public function getTop3Champions(){
        if($this->championMasteries === false){
            return [false,false,false,false];
        }
        $championImgSplashArt = [];
        $championImgIconArray = [];
        $championRank = [];
        $championPoints = [];
        for($i = 0; $i < 3; $i++){
            $this->setChampionName($this->championMasteries[$i]->championId);
            array_push($championImgIconArray, "http://ddragon.leagueoflegends.com/cdn/$this->DDragonVersion/img/champion/$this->championName.png");
            array_push($championImgSplashArt, 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/'.$this->championName.'_0.jpg');
            array_push($championRank, $this->championMasteries[$i]->championLevel);
            array_push($championPoints, $this->championMasteries[$i]->championPoints);
        }
        return [$championImgIconArray,$championImgSplashArt,$championRank,$championPoints];
    }


}