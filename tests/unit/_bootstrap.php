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
    0 => 'KingMorpheus2131',
    1 => 'Juanpablomontoya',
    2 => 'ThatBoy18',
    3 => 'manklar',
    4 => 'ReckonStuff'
];
$team1iconArray = [
    0 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/1665.png',
    1 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/1310.png',
    2 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/10.png',
    3 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/26.png',
    4 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/21.png'
];
$team1soloRankArray = [
    0 => 'BRONZE I',
    1 => 'SILVER IV',
    2 => 'Unranked',
    3 => 'Unranked',
    4 => 'Unranked'
];
$team1soloWinLossArray = [
    0 => '9 | 15',
    1 => '8 | 8',
    2 => 'Unranked',
    3 => 'Unranked',
    4 => 'Unranked'
];
$team1flexRankArray = [
    0 => 'Unranked',
    1 => 'SILVER III',
    2 => 'Unranked',
    3 => 'Unranked',
    4 => 'Unranked'
];
$team1flexWinLossArray = [
    0 => 'Unranked',
    1 => '10 | 8',
    2 => 'Unranked',
    3 => 'Unranked',
    4 => 'Unranked'
];
$team1top3ChampionIcons = [
    0 =>[
        0 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Diana.png',
        1 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Malzahar.png',
        2 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Morgana.png'
    ],
    1 => [
        0 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Annie.png',
        1 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Blitzcrank.png',
        2 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Jinx.png'
    ],
    2 => false,
    3 => [
        0 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Ashe.png',
        1 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Ziggs.png',
        2 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/Taric.png'
    ],
    4 => false];
$team1top3ChampionImages = [
    0 => [
        0 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Diana_0.png',
        1 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Malzahar_0.png',
        2 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Morgana_0.png'
    ],
    1 =>[
        0 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Annie_0.png',
        1 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Blitzcrank_0.png',
        2 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_0.png'
    ],
    2 => false,
    3 => [
        0 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ashe_0.png',
        1 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ziggs_0.png',
        2 => 'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Taric_0.png'
    ],
    4 => false
];
$team1top3ChampionRanks = [
    0 => [
        0 => 5,
        1 => 5,
        2 => 5
    ],
    1 => [
        0 => 4,
        1 => 4,
        2 => 4
    ],
    2 => false,
    3 => [
        0 => 2,
        1 => 2,
        2 => 2
    ],
    4 => false
];
$team1top3ChampionPionts = [
    0 => [
        0 => 51852,
        1 => 44805,
        2 => 37771
    ],
    1 =>[
        0 => 20083,
        1 => 19793,
        2 => 16854
    ],
    2 => false,
    3 =>[
        0 => 3461,
        1 => 3325,
        2 => 2430
    ],
    4 => false];

Fixtures::add('team1teamName', $team1teamName);
Fixtures::add('team1color', $team1color);
Fixtures::add('team1summonerArray', $team1summonerArray);
Fixtures::add('team1iconArray', $team1iconArray);
Fixtures::add('team1soloWinLossArray', $team1soloWinLossArray);
Fixtures::add('team1soloRankArray', $team1soloRankArray);
Fixtures::add('team1flexRankArray', $team1flexRankArray);
Fixtures::add('team1flexWinLossArray', $team1flexWinLossArray);
Fixtures::add('team1top3ChampionIcons', $team1top3ChampionIcons);
Fixtures::add('team1top3ChampionImages', $team1top3ChampionImages);
Fixtures::add('team1top3ChampionRanks', $team1top3ChampionRanks);
Fixtures::add('team1top3ChampionPionts', $team1top3ChampionPionts);

#-----Team 2#-----#

$team2teamName = "Team Awesome";
$team2color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
$team2summonerArray = [
    0 => 'Spartan7Warrior',
    1 => 'CacheMeOuside',
    2 => 'DragonDefeater1',
    3 => 'SlySkeever',
    4 => 'ChaChing77'
];
$team2iconArray = [
    0 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/25.png',
    1 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/26.png',
    2 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/22.png',
    3 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/7.png',
    4 => 'http://ddragon.leagueoflegends.com/cdn/7.17.2/img/profileicon/22.png'
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
$team2top3ChampionIcons = [
    0 => false,
    1 => false,
    2 => false,
    3 => false,
    4 => false];
$team2top3ChampionImages = [
    0 => false,
    1 => false,
    2 => false,
    3 => false,
    4 => false
];
$team2top3ChampionRanks = [
    0 => false,
    1 => false,
    2 => false,
    3 => false,
    4 => false
];
$team2top3ChampionPionts = [
    0 => false,
    1 => false,
    2 => false,
    3 => false,
    4 => false];
Fixtures::add('team2teamName', $team2teamName);
Fixtures::add('team2color', $team2color);
Fixtures::add('team2summonerArray', $team2summonerArray);
Fixtures::add('team2iconArray', $team2iconArray);
Fixtures::add('team2soloWinLossArray', $team2soloWinLossArray);
Fixtures::add('team2soloRankArray', $team2soloRankArray);
Fixtures::add('team2flexRankArray', $team2flexRankArray);
Fixtures::add('team2flexWinLossArray', $team2flexWinLossArray);
Fixtures::add('team2top3ChampionIcons', $team2top3ChampionIcons);
Fixtures::add('team2top3ChampionImages', $team2top3ChampionImages);
Fixtures::add('team2top3ChampionRanks', $team2top3ChampionRanks);
Fixtures::add('team2top3ChampionPionts', $team2top3ChampionPionts);

########################################################################################