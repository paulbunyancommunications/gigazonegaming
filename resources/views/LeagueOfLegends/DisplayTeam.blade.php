@extends('LeagueOfLegends/Layout')

@section('TeamName')
    {{$teamName}}
@stop
@section('Color')
    {{$color}}

@stop

@for($i = 0; $i < count($summonerArray); $i++)
    @section('Player' . $i . 'Name')
        {{ $summonerArray[$i]}}
    @stop
    @section('Player' . $i . 'Icon')
        <img class="icon" src="{{ $iconArray[$i]}}"/>
    @stop
    @section('Player' . $i . 'SoloWinLoss')
        {{$soloRankArray[$i]}}
    @stop
    @section('Player' . $i . 'SoloRank')
        {{$soloWinLossArray[$i]}}
    @stop
    @section('Player' . $i . 'FlexWinLoss')
        {{$flexRankArray[$i]}}
    @stop
    @section('Player' . $i . 'FlexRank')
        {{$flexWinLossArray[$i]}}
    @stop
    @section('Player' . $i . 'Champion')
        <img class="playerImage" src="\LeagueOfLegendsDisplay\Images\GZG-Atom-to-Animate.gif"/>
    @stop
@endfor

{{--@section('js')--}}
    {{--{{print_r($team)}}--}}
    {{--foreach({!! json_encode($team) !!} as var player){--}}
        {{--alert(player);--}}
        {{--break();--}}
    {{--}--}}

{{--@stop--}}