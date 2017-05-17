@extends('Layout')

@section('content')
    <?php $team2 = filter_input(INPUT_GET,"team2");?>
    <h1><?php echo $team2; ?></h1>
    <?php
    $playerNames = array(0=> 'KingMorpheus2131','Spartan7Warrior','TheDestroyerOfWorlds','CoolCat56','Dominator6789');
    $icons = array(0=> 'http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg');
    $rank = array(0=>'1','2','3','4','5');
    $winLoss = array(0=>'1/1','2/2','3/3','4/4','5/5');
    $images = array(0=>'http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Diana_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Rengar_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ezreal_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/LeeSin_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Alistar_0.jpg');
    for ($i=0;$i<count($playerNames);$i++){
        echo "<table class='col-lg'>";
        echo '<tr><td><b class="summonerName">'.$playerNames[$i].'</b><img class="icon" src="'. $icons[$i] .'"/></td></tr>';
        echo '<tr><td class="playerInfo"><b class="WinLoss">Win/Loss: '.$winLoss[$i].'</b> <b class="rank">Rank: '.$rank[$i].'</b></td></tr>';
        echo '<tr><td><img class="playerImage" src="'.$images[$i].'"/></td></tr>';
        echo "</table>";
    }
    ?>
@stop

@section('overlay')
    style="background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%"
@stop
