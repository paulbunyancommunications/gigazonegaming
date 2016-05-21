
@extends('game.base')

@section('content')
    <ul id="listOfGames" class="listing">
        @if(!isset($games) || $games == [])
            <li>There are no games yet</li>
        @else
            @foreach($games as $id => $game)
                <li>{{ Html::linkAction('Backend\Manage\GamesController@index', $game["name"], array('class' => 'btn list')) }}
                    &nbsp;&nbsp;
                    {{ Html::linkAction('Backend\Manage\GamesController@edit', 'Edit', array('game_id'=>$game["id"]), array('class' => 'btn list fa fa-pencil-square-o')) }}
                    &nbsp;&nbsp;
                    {{ Html::linkAction('Backend\Manage\GamesController@destroy', 'Delete', array('game_id'=>$game["id"]), array('class' => 'btn list fa fa-times')) }}
                </li>
            @endforeach
        @endif
    </ul>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(isset($cont_updated) and  $cont_updated)
        <div class="alert alert-success"><strong>Success!</strong> You have updated this Game.</div>
    @endif
    <form role="form" id="gameForm" @if(isset($theGame->name))action="/app/manage/game/edit/{{$theGame->id}}" @else action="/app/manage/game/new/" @endif method="post">

        <div class="form-group">
            @if(isset($theGame->name))
                <input name="_method" type="hidden" value="PUT">
            @else
                <input name="_method" type="hidden" value="POST">
            @endif
            <div class="form-group">
                <label for="name">Game Name: </label> &nbsp; <input type="text" name="name" id="name" placeholder="The name of the game" @if(isset($theGame->name))value="{{$theGame->name}}"@endif/>
            </div>
            <div class="form-group">
                <label for="uri">Game URI: </label> &nbsp; <input type="text" name="uri" id="uri" placeholder="The uri of the game" @if(isset($theGame->uri))value="{{$theGame->uri}}"@endif />
            </div>
            <div class="form-group">
                <label for="description">Game Description: </label> &nbsp; <textarea name="description" id="description" placeholder="The descrition of the game"  >@if(isset($theGame->description)){{$theGame->description}}@endif</textarea>
            </div>
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="submit" value="Save">
                <input type="button" name='reset' id='reset' value="Reset">
            </div>
        </div>
    </form>

@endsection
@section('js')
    $(document).ready(function() {
        $('.list.fa-times').click(function() {
            if (confirm('Are you sure?')) {
                var url = $(this).attr('href');
                $(document).load(url);
            }
        });
        $('#reset').click(function(){
            var url = "/app/manage/game/"
            $(document).load(url);
        });
    });
@endsection