<?php

namespace RiotDisplay\Game;



class summuner{

# Variables
#----------------------------------------------------------------------
    protected $champion;
    protected $name;
    protected $icon;
    protected $rank;
    protected $winLoss;

# Constructor
#----------------------------------------------------------------------
    function __construct($summonerName)
    {
        $this->setName($summonerName);
        $this->setIcon();
        $this->setChampion();
        $this->setRank();
        $this->setRank();
    }
# Setters
#----------------------------------------------------------------------
    public function setName($summonerName){
        $this->name = $summonerName;
    }

    public function  setIcon(){

    }

    public function setRank(){

    }

    public function setWinLoss(){

    }

    public function setChampion(){

    }

# Getters
#----------------------------------------------------------------------
    public function getName(){

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