@extends('Layout')

@section('content')
    <?php $team2 = filter_input(INPUT_GET,"team2");?>
    <h1 style="text-align:center;font-size:3vw; color:#FBB042;"><?php echo $team2; ?></h1>
    <table style="margin-left:auto; margin-right:auto; min-width:100%;">
        <tr>
            <?php
            $playerNames = array(0=> 'KingMorpheus2131','Spartan7Warrior','TheDestroyerOfWorlds','CoolCat56','Dominator6789');
            $icons = array(0=> 'http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg','http://news.cdn.leagueoflegends.com/public/images/articles/2014/november_2014/sru-beta/Red.jpg');
            for ($i=0;$i<count($playerNames);$i++){
                echo '<td style=" text-align:center;"><b style="color:#FBB042; font-size: 1vw; vertical-align:5px;">'.$playerNames[$i].'</b><img style="max-width:30px; padding: 0px 0px 0px 10px;" src="'. $icons[$i] .'"/></td>';
            }
            ?>

        </tr>
        <tr>
            <?php
            $rank = array(0=>'1','2','3','4','5');
            $winLoss = array(0=>'1/1','2/2','3/3','4/4','5/5');
            for ($i=0;$i<count($playerNames);$i++){
                echo '<td style="font-size: 1vw;color:#FBB042;"><b style="float:left;margin-left:20px;">Win/Loss: '.$winLoss[$i].'</b> <b style="float:right;margin-right:20px;">Rank: '.$rank[$i].'</b></td>';
            }
            ?>
        </tr>
        <tr>
            <?php
            $images = array(0=>'Diana','Rengar','Ezreal','LeeSin','Alistar');
            for ($i=0;$i<count($playerNames);$i++){
                echo '<td style="text-align:center;" ><img src="http://ddragon.leagueoflegends.com/cdn/img/champion/loading/'.$images[$i].'_0.jpg" style="max-width:100%; height:auto;"/></td>';
            };
            ?>
        </tr>
    </table>
@stop

@section('overlay')
    style="background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:100%"
@stop
