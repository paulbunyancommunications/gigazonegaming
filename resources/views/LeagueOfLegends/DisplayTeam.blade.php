@extends('LeagueOfLegends/Layout')

@section('TeamName')
 <? echo $teamName; ?>
@stop
@section('Color')
    <? echo $color; ?>
@stop

<?php for($i = 0; $i < count($team); $i++){ ?>
    @section('Player' . $i . 'Name')
        <? echo $team[$i]->getSummonerName(); ?>
    @stop
    @section('Player' . $i . 'Icon')
        <img class="icon" src="<? echo $team[$i]->getIcon(); ?>"/>
    @stop
    @section('Player' . $i . 'SoloWinLoss')
        <? echo $team[$i]->getSoloRankedWinLoss(); ?>
    @stop
    @section('Player' . $i . 'SoloRank')
        <? echo $team[$i]->getSoloRank(); ?>
    @stop
    @section('Player' . $i . 'FlexWinLoss')
        <? echo $team[$i]->getFLEXRankedWinLoss(); ?>
    @stop
    @section('Player' . $i . 'FlexRank')
        <? echo $team[$i]->getFLEXRank(); ?>
    @stop
    @section('Player' . $i . 'Champion')
        <img class="playerImage" src="\LeagueOfLegendsDisplay\Images\GZG-Atom-to-Animate.gif"/>
    @stop


<?php }; ?>