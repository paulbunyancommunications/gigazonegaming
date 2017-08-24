<?php
// Here you can initialize variables that will be available to your tests
require_once __DIR__ . '/App/Http/Requests/WpRequestsBase.php';
use Codeception\Util\Fixtures;


# GameDisplayControllerFixtures
########################################################################################
#-----Team 2#-----#

$team1teamName = "That Team";
$team1color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
$team1summonerArray = [
    0 => "ThatBoy18",
    1 => "ReckonStuff",
    2 => "KingMorpheus2131",
    3 => "Juanpablomontoya",
    4 => "manklar"
];
$team1iconArray = [
    0 => "https://avatar.leagueoflegends.com/na/ThatBoy18.png",
    1 => "https://avatar.leagueoflegends.com/na/ReckonStuff.png",
    2 => "https://avatar.leagueoflegends.com/na/KingMorpheus2131.png",
    3 => "https://avatar.leagueoflegends.com/na/Juanpablomontoya.png",
    4 => "https://avatar.leagueoflegends.com/na/manklar.png"
];
$team1soloRankArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "BRONZE I",
    3 => "SILVER IV",
    4 => "Unranked"
];
$team1soloWinLossArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "9 | 15",
    3 => "8 | 8",
    4 => "Unranked"
];
$team1flexRankArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "Unranked",
    3 => "SILVER III",
    4 => "Unranked"
];
$team1flexWinLossArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "Unranked",
    3 => "10 | 8",
    4 => "Unranked"
];

Fixtures::add('team1teamName', $team1teamName);
Fixtures::add('team1color', $team1color);
Fixtures::add('team1summonerArray', $team1summonerArray);
Fixtures::add('team1iconArray', $team1iconArray);
Fixtures::add('team1soloWinLossArray', $team1soloWinLossArray);
Fixtures::add('team1soloRankArray', $team1soloRankArray);
Fixtures::add('team1flexRankArray', $team1flexRankArray);
Fixtures::add('team1flexWinLossArray', $team1flexWinLossArray);

#-----Team 2#-----#

$team2teamName = "Team Awesome";
$team2color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
$team2summonerArray = [
    0 => "SlySkeever",
    1 => "ChaChing77",
    2 => "DragonDefeater1",
    3 => "CacheMeOuside",
    4 => "Spartan7Warrior"
];
$team2iconArray = [
    0 => "https://avatar.leagueoflegends.com/na/SlySkeever.png",
    1 => "https://avatar.leagueoflegends.com/na/ChaChing77.png",
    2 => "https://avatar.leagueoflegends.com/na/DragonDefeater1.png",
    3 => "https://avatar.leagueoflegends.com/na/CacheMeOuside.png",
    4 => "https://avatar.leagueoflegends.com/na/Spartan7Warrior.png",
];
$team2soloRankArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "Unranked",
    3 => "Unranked",
    4 => "Unranked"
];
$team2soloWinLossArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "Unranked",
    3 => "Unranked",
    4 => "Unranked"
];
$team2flexRankArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "Unranked",
    3 => "Unranked",
    4 => "Unranked"
];
$team2flexWinLossArray = [
    0 => "Unranked",
    1 => "Unranked",
    2 => "Unranked",
    3 => "Unranked",
    4 => "Unranked"
];
Fixtures::add('team2teamName', $team2teamName);
Fixtures::add('team2color', $team2color);
Fixtures::add('team2summonerArray', $team2summonerArray);
Fixtures::add('team2iconArray', $team2iconArray);
Fixtures::add('team2soloWinLossArray', $team2soloWinLossArray);
Fixtures::add('team2soloRankArray', $team2soloRankArray);
Fixtures::add('team2flexRankArray', $team2flexRankArray);
Fixtures::add('team2flexWinLossArray', $team2flexWinLossArray);

########################################################################################