
@extends('game.base')

@section('css')
    .deletingForms{
        display:inline-block;
    }
@endsection
@section('content')
    <ul id="listOfGames" class="listing">
        @if(!isset($games) || $games == [])
            <li>There are no games yet</li>
        @else
            @foreach($games as $id => $game)
                <li>
                    {{ Html::linkAction('Backend\Manage\GamesController@index', $game["name"]."   url: ".$game["uri"], array('class' => 'btn btn-default list')) }}
                    &nbsp;&nbsp;
                    {{ Html::linkAction('Backend\Manage\GamesController@edit', 'Edit', array('game_id'=>$game["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o')) }}
                    &nbsp;&nbsp;
                    {{ Form::open(array('id' => "gameForm".$game["id"], 'action' => array('Backend\Manage\GamesController@destroy', $game["id"]), 'class' => "deletingForms")) }}
                    <input name="_method" type="hidden" value="DELETE">
                    {!!
                        Form::submit(
                            'Delete',
                            array('class'=>'btn btn-danger list fa fa-times')
                        )
                    !!}
                    {{ Form::close() }}
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
    @if(isset($theGame->name))
        {{ Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@update', $theGame->id))) }}
    @else
        {{  Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@create'))) }}
    @endif

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
                <input type="submit" name="submit" id="submit" class='btn btn-default' value="Save">
                {{ Html::link('/manage/game/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default'))}}
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
    });
@endsection