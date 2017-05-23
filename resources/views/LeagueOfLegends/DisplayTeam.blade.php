@extends('LeagueOfLegends/Layout')

@section('content')
    <?php
        $count = 1;
        $playerNames = array(0=> 'KingMorpheus2131','Spartan7Warrior','TheDestroyerOfWorlds','CoolCat56','Dominator6789');
        $icons = array(0=> 'http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg');
        $rank = array(0=>'1','2','3','4','5');
        $winLoss = array(0=>'1/1','2/2','3/3','4/4','5/5');
        $flexRank = array(0=>'1','2','3','4','5');
        $flexWinLoss = array(0=>'1/1','2/2','3/3','4/4','5/5');
        if ($count == 1)
            $images = array(0=>'http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Diana_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Rengar_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ezreal_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/LeeSin_0.jpg','http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Alistar_0.jpg');
        else
            $images = array(0=>'/LeagueOfLegendsDisplay/Images/GZG-Atom-to-Animate.gif','/LeagueOfLegendsDisplay/Images/GZG-Atom-to-Animate.gif','/LeagueOfLegendsDisplay/Images/GZG-Atom-to-Animate.gif','/LeagueOfLegendsDisplay/Images/GZG-Atom-to-Animate.gif','/LeagueOfLegendsDisplay/Images/GZG-Atom-to-Animate.gif');
        for ($i=0;$i<count($playerNames);$i++){
            echo "<div class='col-lg' style='display:inline-block;float:none;'>";
            echo '<div><div><b class="summonerName">'.$playerNames[$i].'</b><img class="icon" src="'. $icons[$i] .'"/></div></div>';
            echo '<div><div class="playerInfo"><b class="WinLoss">Solo:</b></div></div>';
            echo '<div><div class="playerInfo"><b class="WinLoss">Win/Loss: '.$winLoss[$i].'</b> <b class="rank">Rank: '.$rank[$i].'</b></div></div>';
            echo '<div><div class="playerInfo"><b class="WinLoss">Flex:</b></div></div>';
            echo '<div><div class="playerInfo"><b class="WinLoss">Win/Loss: '.$flexWinLoss[$i].'</b> <b class="rank">Rank: '.$flexRank[$i].'</b></div></div>';
            echo '<div><div class="spinner-container"><img class="image" src="'.$images[$i].'"/></div></div>';
            echo "</div>";
        }
    ?>
@stop
