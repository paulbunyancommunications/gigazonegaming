<?php
namespace RiotDisplay\Game;

class game{

# Variables
#----------------------------------------------------------------------
    protected $SummenerId;

    protected $teamArray = array();

# Variables
#----------------------------------------------------------------------

    function __construct($SummenerID)
    {
        $this->setSummenerId($SummenerID);

        $this->setTeamArray();
    }

# Setters
#----------------------------------------------------------------------

    /**
     * @param mixed $SummenerId
     */
    public function setSummenerId($SummenerId)
    {
        $this->SummenerId = $SummenerId;
    }

    /**
     * @param array $teamArray
     */
    public function setTeamArray()
    {
        for($i = 0; $i < 2; $i++){
            $Team = new team($i, $this->SummenerId);
            array_push($this->teamArray, $Team);
        }
    }

# Getters
#----------------------------------------------------------------------

    /**
     * @return array
     */
    public function getTeamArray()
    {
        return $this->teamArray;
    }
}