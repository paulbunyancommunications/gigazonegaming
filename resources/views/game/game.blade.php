
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
    @if(isset($cont_added) and  $cont_added!='')
        <div class="alert alert-success"><strong>Success!</strong> {{$cont_added}}</div>
    @endif
    @if(isset($theGame->name))
        <h1>Update game &#8220;{{$theGame->name}}&#8221;</h1>
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
                <input type="text" name="name" id="name" class="form-control" placeholder="The name of the game" @if(isset($theGame->name))value="{{$theGame->name}}"@endif/>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label col-xs-3">Game Title:</label>
            <div class="col-xs-9">
                <input type="text" name="title" id="title" class="form-control" placeholder="The title of the game" @if(isset($theGame->title))value="{{$theGame->title}}"@endif/>
            </div>
        </div>
        <div class="form-group">
            <label for="uri" class="control-label col-xs-3">Game URI:</label>
            <div class="col-xs-9">
                <input type="text" name="uri" id="uri" class="form-control" placeholder="The uri of the game" @if(isset($theGame->uri))value="{{$theGame->uri}}"@endif />
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="control-label col-xs-3">Game Description:</label>
            <div class="col-xs-9">
                <textarea name="description" id="description" class="form-control" placeholder="The description of the game"  >@if(isset($theGame->description)){{$theGame->description}}@endif</textarea>
            </div>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" id="submit" class='btn btn-default' value="Save">
            {{ Html::link('/manage/game/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default'))}}
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
                    {{ Html::linkAction('Backend\Manage\GamesController@create', '', array('game_id'=>$game["game_id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o', 'id' => 'edit-'.$game["game_name"], 'title'=>"Edit")) }}
                    &nbsp;&nbsp;
                    {{ Form::open(array('id' => "gameForm".$game["game_id"], 'action' => array('Backend\Manage\GamesController@destroy', $game["game_id"]), 'class' => "deletingForms")) }}
                    <input name="_method" type="hidden" value="DELETE">
                    {!!
                        Form::submit(
                            '&#xf014; &#xf1c0;',
                            array('class'=>'btn btn-danger list fa fa-times', 'title'=>"Delete From Database", 'id' => 'delete-'.$game["game_name"])
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
        $('.fa-times').click(function() {
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