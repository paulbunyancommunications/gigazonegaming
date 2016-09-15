
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
    @if(isset($games) || $games != [])
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
            <div class="alert alert-success"><strong>Success!</strong> You have updated this Tournament.</div>
        @endif
        @if(isset($theTournament->name))
            {{ Form::open(array('id' => "tournamentForm", 'action' => array('Backend\Manage\TournamentsController@update', $theTournament->id))) }}
        @else
            {{  Form::open(array('id' => "tournamentForm", 'action' => array('Backend\Manage\TournamentsController@create'))) }}
        @endif

        <div class="form-group">
            @if(isset($theTournament->name))
                <input name="_method" type="hidden" value="PUT">
            @else
                <input name="_method" type="hidden" value="POST">
            @endif
            <div class="form-group">
                <label for="name" style="width:180px; text-align:right;">Tournament Name: </label> &nbsp; <input type="text" name="name" id="name" style="width:350px; text-align:left;" placeholder="The name of the tournament" @if(isset($theTournament->name))value="{{$theTournament->name}}"@endif/>
            </div>
            <div class="form-group">
                <label for="max_players" style="width:180px; text-align:right;">Players per Team: </label> &nbsp; <input type="text" name="max_players" id="max_players" style="width:350px; text-align:left;" placeholder="The maximum amount of players per team" @if(isset($theTournament->max_players))value="{{$theTournament->max_players}}"@endif/>
            </div>
            <div class="form-group">
                <label for="game_id" style="width:180px; text-align:right;">Tournament Game ID: </label> &nbsp;
                <select type="text" name="game_id" id="game_id"  style="width:350px; text-align:left;">
                    <option>---</option>
                    @foreach($games as $key => $game)
                        <option value="{{$game['id']}}"
                                @if(isset($theTournament['game_id']) and $theTournament['game_id'] == $game['id']) selected @endif
                        >{{ $game['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="submit" class='btn btn-default' value="Save">
                {{ Html::link('/manage/tournament/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default'))}}
            </div>
        </div>
        {{ Form::close() }}
        {{ Form::open(array('id' => "tournamentFilter", 'action' => array('Backend\Manage\TournamentsController@filter'))) }}
        <input name="_method" type="hidden" value="POST">
        <label for="game_sort" style="width:180px; text-align:right;">Filter by Game: </label>
        <select name="game_sort" id="game_sort" style="width:280px; text-align:left;">
            <option> --- </option>
            @foreach($games as $g)
                <option id="g_option{{$g['id']}}" value="{{$g['id']}}"
                @if(isset($sorts) and isset($sorts->game_sort) and ($g['id'] == $sorts->game_sort or $g['name'] == $sorts->game_sort)) selected="selected" @endif
                >{{$g['name']}}</option>
            @endforeach
        </select>
        {!! Form::submit( 'Filter', array('class'=>'btn btn-default list fa fa-search', 'style'=>'width:70px; text-align:center;')) !!}
        {{ Form::close() }}
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
                <li>Filtered results: </li>
                @foreach($tournaments_filter as $id => $tournament)
                    @include('game.partials.tournaments_displayer')
                @endforeach
            @endif
        </ul>
    @else
        <h1>Sorry, no games where found on the database!, please create a game before proceding with a tournament</h1>
        {{ Html::link('/manage/game/', 'Create a Game', array('id' => 'new_game', 'class' => 'btn btn-default')) }}
    @endif

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