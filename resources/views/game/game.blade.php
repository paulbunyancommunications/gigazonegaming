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
            <h1 class="txt-color--shadow">Edit Game</h1>
            <p class="txt-color--highlight" id="title-game-title">Editing: <span class="strong txt-color--primary-color-dark">&#8220;{{ $pageTitle }}&#8221;</span></p>
            {{ Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@update', $theGame->id), 'class' => 'form-horizontal')) }}
        @else
            @php ($pageTitle = "Create a new Game")
            <h1 class="txt-color--shadow">{{ $pageTitle }}</h1>
            {{  Form::open(array('id' => "gameForm", 'action' => array('Backend\Manage\GamesController@store'), 'class' => 'form-horizontal')) }}
        @endif
            @if(isset($theGame->name))
                <input name="_method" type="hidden" value="PUT">
            @else
                <input name="_method" type="hidden" value="POST">
            @endif
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Game Name:</label>
                <div class="col-xs-8">
                    <input type="text" name="name" id="name" class="form-control" placeholder="The name of the game"
                           value="@if(isset($theGame->name)){{ $theGame->name }}@else{{ old('name') }}@endif"/>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="control-label col-xs-4">Game Title:</label>
                <div class="col-xs-8">
                    <input type="text" name="title" id="title" class="form-control" placeholder="The title of the game"
                           value="@if(isset($theGame->title)){{ $theGame->title }}@else{{ old('title') }}@endif"/>
                </div>
            </div>
            <div class="form-group">
                <label for="uri" class="control-label col-xs-4">Game URI:</label>
                <div class="col-xs-8">
                    <input type="text" name="uri" id="uri" class="form-control" placeholder="The uri of the game"
                           value="@if(isset($theGame->uri)){{ $theGame->uri }}@else{{ old('uri') }}@endif"/>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="control-label col-xs-4">Game Description:</label>
                <div class="col-xs-8">
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
                           value="{{ isset($theGame->title) ? "Edit Game" : null  }} {{ $pageTitle }}">
                </div>
            </div>

        {{ Form::close() }}
    </div>
    <div class="col-md-12">
        <div class="well">
        <h2>Game List</h2>
        <div id="listOfGames" class="listing">
            @if(!isset($games) || $games == [])
                <div class="alert alert-info">There are no games yet!</div>
            @else
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="col-md-4">Game</th>
                        <th class="col-md-4">Game's Tournament</th>
                        <th class="col-md-4"></th>
                    </tr>
                    </thead>
                    <tbody>
                @foreach($games as $id => $game)

                        <tr>
                            <td class="text-left">
                                {{ Html::linkAction('Backend\Manage\GamesController@edit', $game["game_title"], array('game_id'=>$game["game_id"]), array('class' => 'btn btn-link text-left btn-wrap', 'id' => 'edit-'.$game["game_name"], 'title'=>"Edit")) }}
                            </td>
                            <td class="text-left">
                                {{ Html::linkAction('Backend\Manage\GamesController@edit', $game["game_name"], array('game_id'=>$game["game_id"]), array('class' => 'btn btn-link text-left btn-wrap', 'id' => 'edit-'.$game["game_name"], 'title'=>"Edit")) }}
                            </td>
                        &nbsp;&nbsp;<td class="text-center">

                                <div class="btn-group" role="group" aria-label="Game Actions">
                                    {{
                                       Form::button(
                                           '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
                                           array('type' => 'button', 'id' => 'submit-toForm-edit-'.$game["game_id"], 'class'=>'btn btn-primary toForm','title'=>"Edit game ".$game['game_name'])
                                       )
                                   }}

                                    {{
                                       Form::button(
                                           '<i class="fa fa-print" aria-hidden="true"></i>',
                                           array('type' => 'button', 'id' => 'submit-toForm-print-'.$game["game_id"], 'class'=>'btn btn-primary btn-gz toForm','title'=>"Edit game ".$game['game_name'])
                                       )
                                    }}

                                    {{
                                        Form::button(
                                            '<i class="fa fa-trash-o" aria-hidden="true"></i>',
                                            array('type' => 'button', 'id' => 'submit-toForm-delete-'.$game["game_id"], 'class'=>'btn btn-danger toForm delete-'.str_replace(" ","", $game["game_name"]),'title'=>"Delete game ".$game['game_name'])
                                        )
                                    }}
                                    {{
                                        Form::button(
                                            'Tournaments <i class="fa fa-filter" aria-hidden="true"></i>',
                                            array('type' => 'button', 'id' => 'submit-toForm-filter-'.$game["game_id"], 'class'=>'btn btn-default toForm filter-'.str_replace(" ","", $game["game_name"]),'title'=>"Filter tournaments by game ".$game['game_name'])
                                        )
                                    }}
                                </div> {{--Submit the edit tournament link--}}
                                {{ Html::linkAction('Backend\Manage\GamesController@edit', 'Edit', array('game_id'=>$game["game_id"]), array('id'=>'submit-toForm-edit-form-'.$game['game_id'], 'class' => 'btn btn-default hidden', 'title'=>"Edit tournament ".$game['game_name'])) }}

                                {{--Load printable view--}}
                                {{ Html::linkAction('Api\Championship\PrintingController@printGame', 'Edit', array('game_id'=>$game["game_id"]), array('id'=>'submit-toForm-print-form-'.$game['game_id'], 'class' => 'btn btn-default hidden', 'title'=>"Print game details for ".$game['game_name'])) }}

                                {{ Form::open(array('id' => "submit-toForm-filter-form-".$game["game_id"], 'action' => array('Backend\Manage\TournamentsController@filter'), 'class' => "toForms")) }}
                                <input name="_method" type="hidden" value="POST">
                                <input name="game_sort" type="hidden" value="{{$game["game_id"]}}">
                                {{ Form::close() }}

                                {{ Form::open(array(
                                    'id' => "submit-toForm-delete-form-".$game["game_id"],
                                    'action' => [
                                        'Backend\Manage\GamesController@destroy',
                                        $game["game_id"]
                                        ],
                                    'class' => "deletingForms delete_message'",
                                    'onsubmit'=>"return confirm('Are you sure? Deleting the game ". htmlentities($game['game_name']) ." will erase the game and all dependent tournaments, teams and players relations to such game, tournaments and teams');")) }}
                                <input name="_method" type="hidden" value="DELETE">
                                {{ Form::close() }}

                            </td>
                        </tr>
                @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        </div>
    </div>

@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/filterForm.js"></script>
@endsection