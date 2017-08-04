<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Fixtures;

#Simon
#
$setTeamTestArray = array(
    "TeamName" => "Power Rangers",
    "TournamentName" => "gigazone-gaming-2016-league-of-legends"
);

Fixtures::add('TeamTestArray', $setTeamTestArray);
