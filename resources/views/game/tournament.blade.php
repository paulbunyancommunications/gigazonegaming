@extends('game.base')

@section('css')
    .deletingForms, .toForms{
    display:inline-block;
    }
    .tournamentName{
    display:inline-block;
    min-width:300px;
    }
@endsection
@section('content')
    <div class="col-xs-6">
        @if(isset($games) || $games != [])
            @if(isset($theTournament->name))
                <h1>Update Tournament: &#8220;{{ $theTournament->name }}&#8221;</h1>
                {{ Form::open(array('id' => "tournamentForm", 'action' => array('Backend\Manage\TournamentsController@update', $theTournament->id), 'class' => 'form-horizontal')) }}
            @else
                <h1>Create a new Tournament</h1>
                {{  Form::open(array('id' => "tournamentForm", 'action' => array('Backend\Manage\TournamentsController@store'), 'class' => 'form-horizontal')) }}
            @endif

            <div class="form-group">
                @if(isset($theTournament->name))
                    <input name="_method" type="hidden" value="PUT">
                @else
                    <input name="_method" type="hidden" value="POST">
                @endif
                <div class="form-group">
                    <label for="name" class="control-label col-xs-3">Tournament Name: </label>
                    <div class="col-xs-9">
                        <input type="text" name="name" id="name" class="form-control"
                               placeholder="The name of the tournament"
                               @if(isset($theTournament->name))value="{{$theTournament->name}}"@endif/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="max_players" class="control-label col-xs-3">Players per Team: </label>
                    <div class="col-xs-9">
                        <input type="number" min="1" max="20" name="max_players" id="max_players" class="form-control"

                               placeholder="The maximum amount of players per team"
                               @if(isset($theTournament->max_players))value="{{$theTournament->max_players}}"@endif/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="game_id" class="control-label col-xs-3">Tournament Game ID: </label>
                    <div class="col-xs-9">
                        <select type="text" name="game_id" id="game_id" class="form-control">
                            <option>---</option>
                            @foreach($games as $key => $game)
                                <option value="{{$game['game_id']}}"
                                        @if(isset($theTournament['game_id']) and $theTournament['game_id'] == $game['game_id']) selected @endif
                                >{{ $game['game_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
                <div class="form-group">
                    {{ Html::link('/manage/tournament/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default col-sm-6'))}}

                    <input type="submit" name="submit" id="submit" class='btn btn-default btn-primary col-sm-6' value=
                    @if(isset($theTournament->name))
                            "Update"
                    @else
                        "Save"
                    @endif
                    >
                </div>
            </div>
            {{ Form::close() }}
    </div>
    <div class="col-xs-6">
        <h2>Filter Tournaments:</h2>
        <div class="form-group">
            {{ Form::open(array('id' => "tournamentFilter", 'action' => array('Backend\Manage\TournamentsController@filter'), 'class' => 'form-horizontal')) }}
            <input name="_method" type="hidden" value="POST">
            <div class="form-group">
                <label for="game_sort" class="control-label col-xs-3">Filter by Game: </label>
                <div class="col-xs-9">
                    <select name="game_sort" id="game_sort" class="form-control">
                        <option> ---</option>
                        @foreach($games as $g)
                            <option id="g_option{{$g['game_id']}}" value="{{$g['game_id']}}"
                                    @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
                            >{{$g['game_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6 col-xs-push-6">
                    {!! Form::submit( 'Filter', array('class'=>'form-control btn btn-success list fa fa-search')) !!}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h2>Tournament List</h2>
        <div class="form-group"><br/>
            <ul id="listOfTournaments" class="listing">
                @if(!isset($tournaments_filter))
                    @if(!isset($tournaments) || $tournaments == [])
                        <li>There are no Tournaments yet.</li>
                    @else
                        @foreach($tournaments as $id => $tournament)
                            @include('game.partials.tournaments_displayer')
                        @endforeach
                    @endif
                @elseif($tournaments_filter == [] or $tournaments_filter == [ ])
                    <li>There are no results with the selected filter.</li>
                @else
                    <li>Filtered results:</li>
                    @foreach($tournaments_filter as $id => $tournament)
                        @include('game.partials.tournaments_displayer')
                    @endforeach
                @endif
            </ul>
            @else
                <h1>Sorry, no games where found on the database!, please create a game before proceding with a
                    tournament</h1>
                {{ Html::link('/manage/game/', 'Create a Game', array('id' => 'new_game', 'class' => 'btn btn-default')) }}
            @endif
        </div>
    </div>

@endsection
@section('js')
    $(document).ready(function() {
    $('.delete_message').click(function() {
    var conf = confirm('Are you sure? Deleting the tournament will erase all teams and players relations to such
    tournament and teams (but not to the game)');
    if (conf) {
    var url = $(this).attr('href');
    $(document).load(url);
    }else{
    return false;
    }
    });
    });
@endsection