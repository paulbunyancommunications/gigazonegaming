
@extends('game.base')

@section('content')
    <ul id="listOfGames" class="listing">
        @if(!isset($games) || $games == [])
            <li>There are no games yet</li>
        @else
            @foreach($games as $id => $game)
                <li><a class="list" href="/app/game/{{$game["id"]}}" >{{$game["name"]}}</a> &nbsp;&nbsp;<a class="list fa fa-pencil-square-o" href="/app/game/edit/{{$game["id"]}}" ></a> &nbsp;&nbsp;<a class="list fa fa-times" href="/app/game/delete/{{$game["id"]}}" ></a></li>
            @endforeach
        @endif
    </ul>
    <form id="gameForm" @if(isset($theGame['name']))action="/app/game/edit/{{$id}}" @else action="/app/game/new/" @endif method="post">
        <label for="name">Game Name: </label><input type="text" name="name" placeholder="The name of the game" @if(isset($theGame['name']))value="{{$theGame['name']}}"@endif/>
        <label for="uri">Game URI: </label><input type="text" name="uri" placeholder="The uri of the game" @if(isset($theGame['uri']))value="{{$theGame['uri']}}"@endif />
        <label for="description">Game Description: </label><textarea name="description" placeholder="The descrition of the game"  >@if(isset($theGame['description'])){{$theGame['description']}}@endif</textarea>
        <input type="submit" value="Save">
    </form>

@endsection
