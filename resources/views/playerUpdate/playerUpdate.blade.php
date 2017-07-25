@extends('playerUpdateLayout')

@section('Name')
{{$token->name}}
@stop

@section('Username')
{{$token->username}}
@stop

@section('Email')
{{$token->email}}
@stop

@section('Phone')
{{$token->phone}}
@stop

@section('Form')
    @if($tournaments || $games || $teams)
    <h3>Additional Information</h3>
    @endif
    @if($tournaments)
        @for($i=0;$i<count($tournaments);$i++)
            <h4>Tournament Entered: {{$tournaments[$i]->name}}</h4>
        @endfor
    @endif
    @if($games)
        @for($i=0;$i<count($games);$i++)
            <h4>Game Entered: {{$games[$i]->title}}</h4>
        @endfor
    @endif
    @if($teams)
        @for($i=0;$i<count($teams);$i++)
            <h5>Team Name: {{$teams[$i]->name}}</h5>
        @endfor
    @endif
@stop