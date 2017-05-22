<?php
namespace GameDisplay\RiotDisplay;


use GameDisplay\RiotDisplay\API\Api;

class Summoner{

# Variables
#----------------------------------------------------------------------
    protected $champion;
    protected $summonerName;
    protected $summonerID;
    protected $icon;
    protected $soloRank;
    protected $soloRankedWinLoss;
    protected $FLEXRank;
    protected $FLEXRankedWinLoss;
    protected $api;

# Constructor
#----------------------------------------------------------------------
    function __construct($summonerName)
    {

        #Initailize the name of the summoner
        $this->setSummonerName($summonerName);

        #Sets up api so that its ready for requests
        $this->setApi();

        #set Summoner properties
        $this->setSummonerID();
        $this->setIcon();
        $this->setSoloRankedWinLoss();
        $this->setSoloRank();
        $this->setFLEXRank();
        $this->setFLEXRankedWinLoss();


//        dd("Solo Rank: " . $this->soloRank . " Solo Win|Loss: " . $this->soloRankedWinLoss ." Flex Rank: " . $this->FLEXRank . " Flex Win|Loss: " . $this->FLEXRankedWinLoss);
//        $this->setChampion();
//        $this->setSoloRank();
    }
# Setters
#----------------------------------------------------------------------
    public function setSummonerName($summonerName){
        $this->summonerName = $summonerName;
    }

    public function setApi(){
        #creats a new api object
        $this->api = new Api($this->summonerName);
    }

    public function setSummonerID(){
        $this->summonerID = $this->api->getSummonerId();
    }

    public function  setIcon(){
        $this->icon = $this->api->getSummonerIcon();
    }

    public function setSoloRank(){
        $this->soloRank = $this->api->getSoloRank();
    }

    public function setSoloRankedWinLoss(){
        $this->soloRankedWinLoss = $this->api->getSoloRankedWinLoss();
    }

    public function setFLEXRank()
    {
        $this->FLEXRank = $this->api->getFLEXRank();
    }

    public function setFLEXRankedWinLoss()
    {
        $this->FLEXRankedWinLoss = $this->api->getFLEXRankedWinLoss();
    }



    public function setChampion(){
        $this->champion = $this->api->getChampion();
    }



# Getters
#----------------------------------------------------------------------
    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getChampion()
    {
        return $this->champion;
    }

    /**
     * @return mixed
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return mixed
     */
    public function getFLEXRank()
    {
        return $this->FLEXRank;
    }

    /**
     * @return mixed
     */
    public function getFLEXRankedWinLoss()
    {
        return $this->FLEXRankedWinLoss;
    }

    /**
     * @return mixed
     */
    public function getSoloRank()
    {
        return $this->soloRank;
    }

    /**
     * @return mixed
     */
    public function getSoloRankedWinLoss()
    {
        return $this->soloRankedWinLoss;
    }

    /**
     * @return mixed
     */
    public function getSummonerID()
    {
        return $this->summonerID;
    }

    /**
     * @return mixed
     */
    public function getSummonerName()
    {
        return $this->summonerName;
    }
}