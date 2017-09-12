<?php
namespace RiotDisplay\Game\Team;

use App\Http\Riot_Api\riot_api;
use App\Http\Game\Team\Summoner\summoner;

class team{

# Variables
#----------------------------------------------------------------------
protected $SummenerID;
protected $teamNum;
protected $teamJson;
protected $summoners = array();

function __construct($teamNum, $SummonerId)
{
    $this->setSummenerID($SummonerId);
    $this->setTeamNum($teamNum);
    $this->setTeamJson();
}

    /**
     * @param mixed $SummenerID
     */
    public function setSummenerID($SummenerID)
    {
        $this->SummenerID = $SummenerID;
    }

    /**
     * @param mixed $Team_num
     */
    public function setTeamNum($Team_num)
    {
        $this->Team_num = $Team_num;
    }

    /**
     * @param mixed $teamJson
     */
    public function setTeamJson()
    {
//        $request = new riot_api($this->SummenerID);
//        $this->teamJson = $request->getTeam($this->teamNum);
//        #pars json
//        #for loop through summuners
//
//        #array of args
//
//        $summoner = new summoner(array of args);
//        array_push($this->summoners,  )

    }


}