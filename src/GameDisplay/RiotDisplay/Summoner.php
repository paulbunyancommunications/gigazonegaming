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
    protected $rank;
    protected $winLoss;
    protected $api;

# Constructor
#----------------------------------------------------------------------
    function __construct($summonerName)
    {

        $this->setSummonerName($summonerName);

        #Sets up api so that its ready for requests
        $this->setApi();

        #set Summoner properties
        $this->setSummonerID();
        $this->setIcon();
        $this->setChampion();
        dd($this->icon);
        $this->setChampion();
        $this->setRank();
        $this->setRank();
    }
# Setters
#----------------------------------------------------------------------
    public function setSummonerName($summonerName){
        $this->summonerName = $summonerName;
    }

    public function setApi(){
        $this->api = new Api($this->summonerName);
    }

    public function setSummonerID(){
        $this->summonerID = $this->api->getSummonerId();
    }

    public function  setIcon(){
        $this->icon = $this->api->getSummonerIcon();
    }

    public function setRank(){

    }

    public function setWinLoss(){

    }

    public function setChampion(){
        $this->champion = $this->api->getChampion();
    }



# Getters
#----------------------------------------------------------------------
    public function getSummonerName(){

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
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @return mixed
     */
    public function getWinLoss()
    {
        return $this->winLoss;
    }

}