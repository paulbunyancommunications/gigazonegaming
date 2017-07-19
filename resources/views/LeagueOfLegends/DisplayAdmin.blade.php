@extends('LeagueOfLegends/admin')
@section("Tournament")
   <option id="defaultTournament"  value="default">Select a Tournament</option>
    @for($i=0;$i<count($tournaments);$i++)
        <option value="{{$tournaments[$i]['id']}}">{{$tournaments[$i]['name']}}</option>
    @endfor
@stop

@section("Team")
    <option id="defaultTeam" value="default">Select a Team</option>
    @for($i=0;$i<count($teams);$i++)
        <option id="{{$teams[$i]['tournament_id']}}" value="{{$teams[$i]['id']}}">{{$teams[$i]['name']}}</option>
    @endfor
@stop

@section("Team-1")
    <option id="defaultTeam-1" value="default">Select a Team</option>
    @for($i=0;$i<count($teams);$i++)
        <option id="{{$teams[$i]['tournament_id']}}" value="{{$teams[$i]['id']}}">{{$teams[$i]['name']}}</option>
    @endfor
@stop

@section("Color")
    <option id="defaultColor" value="default">Select a Color</option>
    <option>Blue</option>
    <option>Red</option>
@stop

@section("Color-1")
    <option id="defaultColor-1" value="default">Select a Color</option>
    <option>Blue</option>
    <option>Red</option>
@stop
@section('info')
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@stop



