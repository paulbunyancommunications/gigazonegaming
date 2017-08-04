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
    @section('Player' . $i . 'SoloRank')
        @if(strpos($soloRankArray[$i],"BRONZE" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/bronzei.png"/><br/>{{$soloRankArray[$i]}}
        @elseif(strpos($soloRankArray[$i],"SILVER" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/1_3.png"/><br/>{{$soloRankArray[$i]}}
        @elseif(strpos($soloRankArray[$i],"GOLD" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/goldv.png"/><br/>{{$soloRankArray[$i]}}
        @elseif(strpos($soloRankArray[$i],"PLATINUM" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/platinumv.png"/><br/>{{$soloRankArray[$i]}}
        @elseif(strpos($soloRankArray[$i],"DIAMOND" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/diamondi.png"/><br/>{{$soloRankArray[$i]}}
        @elseif(strpos($soloRankArray[$i],"MASTER" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/master.png"/><br/>{{$soloRankArray[$i]}}
        @elseif(strpos($soloRankArray[$i],"CHALLENGER" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/challenger.png"/><br/>{{$soloRankArray[$i]}}
        @else
            <img class="rank unranked" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/bronzei.png"/><br/>{{$soloRankArray[$i]}}
        @endif
    @stop
    @section('Player' . $i . 'SoloWinLoss')
        {{$soloWinLossArray[$i]}}
    @stop
    @section('Player' . $i . 'FlexRank')
        @if(strpos($flexRankArray[$i],"BRONZE" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/bronzei.png"/><br/>{{$flexRankArray[$i]}}
        @elseif(strpos($flexRankArray[$i],"SILVER" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/1_3.png"/><br/>{{$flexRankArray[$i]}}
        @elseif(strpos($flexRankArray[$i],"GOLD" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/goldv.png"/><br/>{{$flexRankArray[$i]}}
        @elseif(strpos($flexRankArray[$i],"PLATINUM" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/platinumv.png"/><br/>{{$flexRankArray[$i]}}
        @elseif(strpos($flexRankArray[$i],"DIAMOND" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/diamondi.png"/><br/>{{$flexRankArray[$i]}}
        @elseif(strpos($flexRankArray[$i],"MASTER" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/master.png"/><br/>{{$flexRankArray[$i]}}
        @elseif(strpos($flexRankArray[$i],"CHALLENGER" )!==false)
            <img class="rank" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/challenger.png"/><br/>{{$flexRankArray[$i]}}
        @else
            <img class="rank unranked" src="https://www.lol-smurfs.com/blog/wp-content/uploads/2017/01/bronzei.png"/><br/>{{$flexRankArray[$i]}}
        @endif
    @stop
    @section('Player' . $i . 'FlexWinLoss')
        {{$flexWinLossArray[$i]}}
    @stop
    @section('Player' . $i . 'Champion')
    @stop
@endfor