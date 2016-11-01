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
    <div class="col-md-12">
        @if(isset($theGame->title))
            @php ($pageTitle = $theGame->title)
            <h2>Edit Game</h2>
            <p class="txt-color--highlight" id="title-game-title">Editing: <span class="strong txt-color--primary-color-dark">{{ $pageTitle }}</span></p>
            {{ Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@update', $theGame->id), 'class' => 'form-horizontal')) }}
        @else
            @php ($pageTitle = "Create a new Game")
            <h2 class="txt-color--shadow">{{ $pageTitle }}</h2>
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
                    <input type="text" name="name" id="name" class="form-control" placeholder="The name of the game"
                           value="@if(isset($theGame->name)){{ $theGame->name }}@else{{ old('name') }}@endif"/>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="control-label col-xs-3">Game Title:</label>
                <div class="col-xs-9">
                    <input type="text" name="title" id="title" class="form-control" placeholder="The title of the game"
                           value="@if(isset($theGame->title)){{ $theGame->title }}@else{{ old('title') }}@endif"/>
                </div>
            </div>
            <div class="form-group">
                <label for="uri" class="control-label col-xs-3">Game URI:</label>
                <div class="col-xs-9">
                    <input type="text" name="uri" id="uri" class="form-control" placeholder="The uri of the game"
                           value="@if(isset($theGame->uri)){{ $theGame->uri }}@else{{ old('uri') }}@endif"/>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="control-label col-xs-3">Game Description:</label>
                <div class="col-xs-9">
                    <textarea name="description" id="description" class="form-control"
                              placeholder="The description of the game">@if(isset($theGame->description)){{ $theGame->description }}@else{{ old('description') }}@endif</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-6">
                    {{ Html::link('/manage/game/', (isset($theGame) ? 'Create a new game' : 'Clear'), array('id' => 'reset', 'class' => 'btn btn-default btn-block btn-gz-default '))}}
                </div>
                <div class="col-xs-6">
                    <input type="submit"
                           name="submit"
                           id="submit"
                           class='btn btn-default btn-primary btn-block btn-gz'
                           value="Edit Game {{ $pageTitle }}">
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-12">
        <h2>Game List</h2>
        <div id="listOfGames" class="listing">
            @if(!isset($games) || $games == [])
                <div class="alert alert-info">There are no games yet!</div>
            @else
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="col-md-5">Game</th>
                        <th class="col-md-5">Game's Tournament</th>
                        <th class="col-md-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                @foreach($games as $id => $game)

                        <tr>
                            <td class="text-left">
                                {{ Html::linkAction('Backend\Manage\GamesController@edit', $game["game_title"], array('game_id'=>$game["game_id"]), array('class' => 'btn btn-link text-left btn-wrap', 'id' => 'edit-'.$game["game_name"], 'title'=>"Edit")) }}
                            </td>
                            <td class="text-left">
                                {{ Form::open(array('id' => "toForm".$game["game_id"], 'action' => array('Backend\Manage\TournamentsController@filter'), 'class' => "toForms form")) }}
                                <input name="_method" type="hidden" value="POST">
                                <input name="game_sort" type="hidden" value="{{$game["game_id"]}}">
                                {!!
                                    Form::submit(
                                        ($game["game_name"] ? $game["game_name"] : $game["game_title"]),
                                        array('class'=>'gameName btn btn-link text-left btn-wrap')
                                    )
                                !!}
                                {{ Form::close() }}
                            </td>
                        &nbsp;&nbsp;<td class="text-center">
                        {{ Form::open(array('id' => "gameForm".$game["game_id"], 'action' => array('Backend\Manage\GamesController@destroy', $game["game_id"]), 'class' => "deletingForms")) }}
                        <input name="_method" type="hidden" value="DELETE">
                        {!!
                            Form::submit(
                                '&#xf014; &#xf1c0;',
                                array('class'=>'btn btn-danger list fa delete_message confirm_choice', 'title'=>"Delete From Database", 'id' => 'delete-'.$game["game_name"])
                            )
                        !!}
                        {{ Form::close() }}
                        </td>
                        </tr>
                @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection
@section('js')
    $(document).ready(function() {
    $('.confirm_choice').click(function() {
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