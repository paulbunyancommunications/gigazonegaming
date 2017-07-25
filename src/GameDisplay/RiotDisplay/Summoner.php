<?php
namespace GameDisplay\RiotDisplay;


use GameDisplay\RiotDisplay\API\Api;
use Mockery\Exception;

/**
 * Class Summoner
 * @package GameDisplay\RiotDisplay
 */
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
    /**
     * Summoner constructor.
     * @param $summonerName
     * @param $api
     *
     */
    function __construct($summonerName, $api)
    {
        #Initailize the name of the summoner
        $this->setSummonerName($summonerName);

        #Sets up api so that its is injected with a summoner ready for requests
        $this->setApi($api);

        #set Summoner properties
        $this->setSummonerID($this->api->getSummonerId());
        $this->setIcon($this->api->getSummonerIcon());
        $this->setSoloRankedWinLoss($this->api->getSoloRankedWinLoss());
        $this->setSoloRank($this->api->getSoloRank());
        $this->setFLEXRank($this->api->getFLEXRank());
        $this->setFLEXRankedWinLoss($this->api->getFLEXRankedWinLoss());



        #dd("Solo Rank: " . $this->soloRank . " Solo Win|Loss: " . $this->soloRankedWinLoss ." Flex Rank: " . $this->FLEXRank . " Flex Win|Loss: " . $this->FLEXRankedWinLoss);
//
    }
# Methods
#----------------------------------------------------------------------
    /**
     * @return mixed
     */
    public function checkCurrentGameStatus(){
        $status = $this->api->checkCurrentGameStatus();
        return $status;
    }

# Setters
#----------------------------------------------------------------------
    /**
     * @param $summonerName
     */
    public function setSummonerName($summonerName){
        $this->summonerName = $summonerName;
    }

    /**
     * @param $api
     */
    public function setApi($api){
        #Sets this api
        $this->api = $api;

        #Injects summoner into api so that this api can uniquely request data for this summoner.
        $this->api->injectSummoner($this->summonerName);

    }

    /**
     * @param $summonerId
     */
    public function setSummonerID($summonerId){
        $this->summonerID = $summonerId;
    }

    /**
     * @param $icon
     */
    public function  setIcon($icon){
        $this->icon = $icon;
    }

    /**
     * @param $soloRank
     */
    public function setSoloRank($soloRank){
        $this->soloRank = $soloRank;
    }

    /**
     * @param $soloRankedWinLoss
     */
    public function setSoloRankedWinLoss($soloRankedWinLoss){
        $this->soloRankedWinLoss = $soloRankedWinLoss;
    }

    /**
     * @param $flexRank
     */
    public function setFLEXRank($flexRank)
    {
        $this->FLEXRank = $flexRank;
    }

    /**
     * @param $flexRankWinLoss
     */
    public function setFLEXRankedWinLoss($flexRankWinLoss)
    {
        $this->FLEXRankedWinLoss = $flexRankWinLoss;
    }


    /**
     *
     */
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