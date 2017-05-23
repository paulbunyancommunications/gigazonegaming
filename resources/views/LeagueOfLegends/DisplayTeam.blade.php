@extends('LeagueOfLegends/Layout')

@section('TeamName')
 <? echo $teamName; ?>
@stop
@section('Color')
    <? echo $color; ?>
@stop

<?php for($i = 0; $i < count($team); $i++){ ?>
    @section('Player' . $i . 'Name')
        <? echo "spartan7Warrior" ?>
    @stop
    @section('Player' . $i . 'Icon')
        <img class="icon" src=""/>
    @stop
    @section('Player' . $i . 'SoloWinLoss')
        <? echo "12|12" ?>
    @stop
    @section('Player' . $i . 'SoloRank')
        <? echo "SILVER III" ?>
    @stop
    @section('Player' . $i . 'FlexWinLoss')
        <? echo "12|12" ?>
    @stop
    @section('Player' . $i . 'FlexRank')
        <? echo "SILVER III" ?>
    @stop
    @section('Player' . $i . 'Champion')
        <img class="playerImage" src="\LeagueOfLegendsDisplay\Images\GZG-Atom-to-Animate.gif"/>
    @stop
<?php }; ?>