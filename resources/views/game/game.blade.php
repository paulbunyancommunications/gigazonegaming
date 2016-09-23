
@extends('game.base')

@section('css')
    .deletingForms, .toForms{
        display:inline-block;
    }
    .gameName{
        display:inline-block;
    min-width:300px;
    }
@endsection
@section('content')

    @if(isset($theGame->title))
        <h1>Update game &#8220;{{ $theGame->title }}&#8221;</h1>
        {{ Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@update', $theGame->id), 'class' => 'form-horizontal')) }}
    @else
        <h1>Create a new game</h1>
        {{  Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@store'), 'class' => 'form-horizontal')) }}
    @endif

    <div class="form-group">
        @if(isset($theGame->name))
            <input name="_method" type="hidden" value="PUT">
        @else
            <input name="_method" type="hidden" value="POST">
        @endif
        <div class="form-group">
            <label for="name" class="control-label col-xs-3">Game Name:</label>
            <div class="col-xs-9">
                <input type="text" name="name" id="name" class="form-control" placeholder="The name of the game" value="@if(isset($theGame->name)){{ $theGame->name }}@else{{ old('name') }}@endif"/>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label col-xs-3">Game Title:</label>
            <div class="col-xs-9">
                <input type="text" name="title" id="title" class="form-control" placeholder="The title of the game" value="@if(isset($theGame->title)){{ $theGame->title }}@else{{ old('title') }}@endif"/>
            </div>
        </div>
        <div class="form-group">
            <label for="uri" class="control-label col-xs-3">Game URI:</label>
            <div class="col-xs-9">
                <input type="text" name="uri" id="uri" class="form-control" placeholder="The uri of the game" value="@if(isset($theGame->uri)){{ $theGame->uri }}@else{{ old('uri') }}@endif" />
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="control-label col-xs-3">Game Description:</label>
            <div class="col-xs-9">
                <textarea name="description" id="description" class="form-control" placeholder="The description of the game"  >@if(isset($theGame->description)){{ $theGame->description }}@else{{ old('description') }}@endif</textarea>
            </div>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" id="submit" class='btn btn-default btn-primary' value="{{ isset($theGame) ? 'Update '. $theGame->title : 'Create' }}">
            {{ Html::link('/manage/game/', (isset($theGame) ? 'Create a new game' : 'Clear'), array('id' => 'reset', 'class' => 'btn btn-default'))}}
        </div>
    </div>
    </form>
    <h2>Game List</h2>
    <div id="listOfGames" class="listing">
        @if(!isset($games) || $games == [])
            <p>There are no games yet</p>
        @else
            @foreach($games as $id => $game)
                <div class="margin-sm-bottom">
                    {{ Form::open(array('id' => "toForm".$game["game_id"], 'action' => array('Backend\Manage\TournamentsController@filter'), 'class' => "toForms form")) }}
                    <input name="_method" type="hidden" value="POST">
                    <input name="game_sort" type="hidden" value="{{$game["game_id"]}}">
                    {!!
                        Form::submit(
                            ($game["game_title"] ? $game["game_title"] : $game["game_name"]),
                            array('class'=>'gameName btn btn-default btn-block list')
                        )
                    !!}
                    {{ Form::close() }}
                    &nbsp;&nbsp;
                    {{ Html::linkAction('Backend\Manage\GamesController@edit', '', array('game_id'=>$game["game_id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o', 'id' => 'edit-'.$game["game_name"], 'title'=>"Edit")) }}
                    &nbsp;&nbsp;
                    {{ Form::open(array('id' => "gameForm".$game["game_id"], 'action' => array('Backend\Manage\GamesController@destroy', $game["game_id"]), 'class' => "deletingForms")) }}
                    <input name="_method" type="hidden" value="DELETE">
                    {!!
                        Form::submit(
                            '&#xf014; &#xf1c0;',
                            array('class'=>'btn btn-danger list fa fa-times confirm-choice', 'title'=>"Delete From Database", 'id' => 'delete-'.$game["game_name"])
                        )
                    !!}
                    {{ Form::close() }}
                </div>
            @endforeach
        @endif
    </div>

@endsection
@section('js')
    $(document).ready(function() {
        $('.confirm-choice').click(function() {
            var conf = confirm('Are you sure?');
            if (conf) {
                var url = $(this).attr('href');
                $(document).load(url);
            }else{
                return false;
            }
        });
    });

@endsection