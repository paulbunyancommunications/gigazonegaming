
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
                    @foreach($games as $key => $game)
                        <option>---</option>
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
                        {{--@if(!isset($theId) or $theId!=$tournament['game_id'])--}}
                            {{--*/ $theId = $tournament['game_id']; /*--}}
                            {{--@foreach($games as $key => $g)--}}
                                {{--@if($g['id'] ==  $tournament['game_id'])--}}
                                    {{--<li>{{$g['name']}}</li>--}}
                                {{--@endif--}}
                            {{--@endforeach--}}
                        {{--@endif--}}
                        <li>{{ Form::open(array('id' => "toForm".$tournament["id"], 'action' => array('Backend\Manage\TeamsController@filter'), 'class' => "toForms")) }}
                            <input name="_method" type="hidden" value="POST">
                            <input name="team_sort" type="hidden" value="{{$tournament["id"]}}">
                            {!!
                                Form::submit(
                                    $tournament["name"],
                                    array('class'=>'tournamentName btn btn-default list')
                                )
                            !!}
                            {{ Form::close() }}

                            <div class='btn btn-primary list fa' title='{{$tournament["max_players"]}}' disabled>{{$tournament["max_players"]}}</div>

                            {{ Html::linkAction('Backend\Manage\TournamentsController@edit', '', array('tournament_id'=>$tournament["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o', 'title'=>"Edit")) }}
                            &nbsp;&nbsp;
                            {{ Form::open(array('id' => "tournamentForm".$tournament["id"], 'action' => array('Backend\Manage\TournamentsController@destroy', $tournament["id"]), 'class' => "deletingForms")) }}
                            <input name="_method" type="hidden" value="DELETE">
                            {!!
                                Form::submit(
                                    '&#xf014; &#xf1c0;',
                                    array('class'=>'btn btn-danger list fa fa-times', 'title'=>"Delete From Database")
                                )
                            !!}
                            {{ Form::close() }}
                        </li>
                    @endforeach
                @endif
            @elseif($tournaments_filter == [] or $tournaments_filter == [ ])
                <li>There are no results with the selected filter.</li>
            @else
                <li>Filtered results: </li>
                @foreach($tournaments_filter as $id => $tournament)
                    @if($tournament!=[] and isset($tournament["tournament_id"]) and $tournament["tournament_id"]!='')
                    <li>
                        {{--<span>Game: {{ $tournament["game_name"]}}</span>--}}
                        {{ Form::open(array('id' => "toForm".$tournament["tournament_id"], 'action' => array('Backend\Manage\TeamsController@filter'), 'class' => "toForms")) }}
                        <input name="_method" type="hidden" value="POST">
                        <input name="team_sort" type="hidden" value="{{$tournament["tournament_id"]}}">
                        {!!
                            Form::submit(
                                $tournament["tournament_name"],
                                array('class'=>'tournamentName btn btn-default list')
                            )
                        !!}
                        {{ Form::close() }}
                        &nbsp;&nbsp;
                        {{ Html::linkAction('Backend\Manage\TournamentsController@edit', '', array('tournament_id'=>$tournament["tournament_id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o', 'title'=>"Edit")) }}
                        &nbsp;&nbsp;
                        {{ Form::open(array('id' => "tournamentForm".$tournament["tournament_id"], 'action' => array('Backend\Manage\TournamentsController@destroy', $tournament["tournament_id"]), 'class' => "deletingForms")) }}
                        <input name="_method" type="hidden" value="DELETE">
                        {!!
                            Form::submit(
                                '&#xf014; &#xf1c0;',
                                array('class'=>'btn btn-danger list fa fa-times', 'title'=>"Delete From Database")
                            )
                        !!}
                        {{ Form::close() }}
                    </li>
                    @endif
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