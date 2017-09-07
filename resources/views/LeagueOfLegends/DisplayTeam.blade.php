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
    @section('Player' . $i . 'MasteriesIcons')
        @if($top3ChampionIcons[$i] === false)
            <div class="championRankMinimize" id="Rank3{{$i}}">
                <br/>
                <img class="I3" src="/LeagueOfLegendsDisplay/Images/Amumu.png" alt id="I3{{$i}}">
            </div>
            <div class="championRank" id="Rank1{{$i}}">
                <br/>
                <img class="I1" src="/LeagueOfLegendsDisplay/Images/Amumu.png" alt id="I1{{$i}}">
            </div>
            <div class="championRankMinimize" id="Rank2{{$i}}">
                <br/>
                <img class="I2" src="/LeagueOfLegendsDisplay/Images/Amumu.png" alt id="I2{{$i}}">
            </div>
        @else
                <div class="championRankMinimize" id="Rank3{{$i}}">
                    <br/>
                    <img class="I3" src="{{ $top3ChampionIcons[$i][2]}}" alt id="I3{{$i}}">
                </div>
                <div class="championRank" id="Rank1{{$i}}">
                    <br/>
                    <img class="I1" src="{{ $top3ChampionIcons[$i][0]}}" alt id="I1{{$i}}">
                </div>
                <div class="championRankMinimize" id="Rank2{{$i}}">
                    <br/>
                    <img class="I2" src="{{ $top3ChampionIcons[$i][1]}}" alt id="I2{{$i}}">
                </div>
        @endif
    @stop
    @section('Player'. $i. 'MasterieRankAndPoints')
        @if($top3ChampionIcons[$i] === false)
            <div id="imageInfo1{{$i}}" >Summoner level too low to have masteries</div>
            <div id="imageInfo2{{$i}}" class="hidden">Summoner level too low to have masteries</div>
            <div id="imageInfo3{{$i}}" class="hidden">Summoner level too low to have masteries</div>
        @else
            <div id="imageInfo1{{$i}}" ><b class="summonerName">{{explode('/',explode('.',$top3ChampionIcons[$i][0])[4])[3]}}</b><br/><img src="/LeagueOfLegendsDisplay/Images/ChampionMastery/ChampMastery_{{ $top3ChampionRanks[$i][0]}}.png">{{ $top3ChampionRanks[$i][0]}}</div>
            <div id="imageInfo2{{$i}}" class="hidden"><b class="summonerName">{{explode('/',explode('.',$top3ChampionIcons[$i][1])[4])[3]}}</b><br/><img src="/LeagueOfLegendsDisplay/Images/ChampionMastery/ChampMastery_{{ $top3ChampionRanks[$i][1]}}.png">{{ $top3ChampionRanks[$i][1]}}</div>
            <div id="imageInfo3{{$i}}" class="hidden"><b class="summonerName">{{explode('/',explode('.',$top3ChampionIcons[$i][2])[4])[3]}}</b><br/><img src="/LeagueOfLegendsDisplay/Images/ChampionMastery/ChampMastery_{{ $top3ChampionRanks[$i][2]}}.png">{{ $top3ChampionRanks[$i][2]}}</div>
        @endif
    @stop
    @section('Player'.$i.'MasteriesSplashArt')
        <div style="width:100%; height:100%;position:absolute;">
            <img id='splash1{{$i}}' class="back-splash" src="{{$top3ChampionImages[$i][0]}}"/>
            <img id='splash2{{$i}}' class="hidden back-splash" src="{{$top3ChampionImages[$i][1]}}"/>
            <img id='splash3{{$i}}' class="hidden back-splash" src="{{$top3ChampionImages[$i][2]}}"/>
        </div>
    @stop
@endfor