
@extends('game.base')

@section('css')
    .deletingForms, .toForms{
        display:inline-block;
    }
    .teamName{
        display:inline-block;
        min-width:300px;
    }
@endsection
@section('content')
    @if(!isset($maxNumOfPlayers)) {{--*/ $maxNumOfPlayers = 5; /*--}}@endif
    @if(isset($tournaments) || $tournaments != [])
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
            <div class="alert alert-success"><strong>Success!</strong> You have updated this Team.</div>
        @endif
        @if(isset($theTeam->name))
            {{ Form::open(array('id' => "teamForm", 'action' => array('Backend\Manage\TeamsController@update', $theTeam->id))) }}
        @else
            {{  Form::open(array('id' => "teamForm", 'action' => array('Backend\Manage\TeamsController@create'))) }}
        @endif
        <div class="form-group">
            @if(isset($theTeam->name))
                <input name="_method" type="hidden" value="PUT">
            @else
                <input name="_method" type="hidden" value="POST">
            @endif
            <div class="form-group">
                <label for="name">Team Name: </label> &nbsp; <input type="text" name="name" id="name" placeholder="The name of the team" @if(isset($theTeam->name))value="{{$theTeam->name}}"@endif/>
            </div>
            <div class="form-group">
                <label for="emblem">Team Emblem: </label> &nbsp; <input type="text" name="emblem" id="emblem" placeholder="The url to the emblem of the team" @if(isset($theTeam->emblem))value="{{$theTeam->emblem}}"@endif/>
            </div>
            <div class="form-group">
                <label for="tournament_id">Team Tournament ID: </label> &nbsp;
                <select type="text" name="tournament_id" id="tournament_id" >
                    @foreach($tournaments as $key => $tournament)
                        <option value="{{$tournament['id']}}" @if(isset($theTeam['tournament_id']) and $theTeam['tournament_id'] == $tournament['id']) selected @endif>{{ $tournament['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="submit" class='btn btn-default' value="Save">
                {{ Html::link('/manage/team/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default'))}}
            </div>
        </div>
        </form>
        {{ Form::open(array('id' => "teamFilter", 'action' => array('Backend\Manage\TeamsController@filter'))) }}
        <input name="_method" type="hidden" value="POST">
        <label for="game_sort">Show options only for this Game: </label> <select name="game_sort" id="game_sort">

            <option> --- </option>
            @foreach($games as $g)
                <option id="t_option{{$g['id']}}" value="{{$g['id']}}" class="gameSelector"
                        @if(isset($sorts) and isset($sorts->game_sort) and ($g['id'] == $sorts->game_sort or $g['name'] == $sorts->game_sort)) selected="selected" @endif
                >{{$g['name']}}</option>
            @endforeach
        </select>
        <br />
        <label for="tournament_sort">Filter by Tournament: </label> <select name="tournament_sort" id="tournament_sort">
            <option> --- </option>
            @foreach($tournaments as $g)
                <option id="t_option{{$g['game_id']}}_{{$g['id']}}" value="{{$g['id']}}"
                        @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['id'] == $sorts->tournament_sort or $g['name'] == $sorts->tournament_sort)) selected="selected" @endif
                >{{$g['name']}}</option>
            @endforeach
        </select>
        {!! Form::submit( 'Filter', array('class'=>'btn btn-default list fa fa-search')) !!}
        {{ Form::close() }}
        <ul id="listOfTeams" class="listing">
            @if(!isset($teams_filter))
                @if(!isset($teams) || $teams == [])
                    <li>There are no Teams yet</li>
                @else
                    @foreach($teams as $id => $team)
                        <li>{{ Form::open(array('id' => "toForm".$team["id"], 'action' => array('Backend\Manage\PlayersController@filter'), 'class' => "toForms")) }}
                            <input name="_method" type="hidden" value="POST">
                            <input name="team_sort" type="hidden" value="{{$team["id"]}}">
                            {!!
                                Form::submit(
                                    $team["name"],
                                    array('class'=>'teamName btn btn-default list')
                                )
                            !!}
                            <div class="btn disabled
                            @if(!isset($team['team_count']) or $team['team_count'] < $maxNumOfPlayers) btn-danger
                            @else btn-success
                            @endif ">
                            @if(isset($team['team_count'])){{$team['team_count']}}
                            @else 0
                            @endif / {{$maxNumOfPlayers}}
                            </div>
                            {{ Form::close() }}

                            &nbsp;&nbsp;
                            {{ Html::linkAction('Backend\Manage\TeamsController@edit', 'Edit', array('team_id'=>$team["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o')) }}
                            &nbsp;&nbsp;
                            {{ Form::open(array('id' => "teamForm".$team["id"], 'action' => array('Backend\Manage\TeamsController@destroy', $team["id"]), 'class' => "deletingForms")) }}
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
            @elseif($teams_filter == [] or $teams_filter == [ ])
                <li>There are no results with the selected filter.</li>
            @else
                <li>Filtered results: </li>
                @foreach($teams_filter as $id => $team)
                    <li>{{ Form::open(array('id' => "toForm".$team["id"], 'action' => array('Backend\Manage\PlayersController@filter'), 'class' => "toForms")) }}
                        <input name="_method" type="hidden" value="POST">
                        <input name="team_sort" type="hidden" value="{{$team["id"]}}">
                        {!!
                            Form::submit(
                                $team["name"],
                                array('class'=>'teamName btn btn-default list')
                            )
                        !!}
                        <div class="btn disabled
                            @if(!isset($team['team_count']) or $team['team_count'] < $maxNumOfPlayers) btn-danger
                            @else btn-success
                            @endif ">
                            @if(isset($team['team_count'])){{$team['team_count']}}
                            @else 0
                            @endif / {{$maxNumOfPlayers}}
                        </div>
                        {{ Form::close() }}
                        &nbsp;&nbsp;
                        {{ Html::linkAction('Backend\Manage\TeamsController@edit', 'Edit', array('team_id'=>$team["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o')) }}
                        &nbsp;&nbsp;
                        {{ Form::open(array('id' => "teamForm".$team["id"], 'action' => array('Backend\Manage\TeamsController@destroy', $team["id"]), 'class' => "deletingForms")) }}
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

    @else
        <h1>Sorry, no tournaments where found on the database!, please create a tournament before proceding with a team</h1>
        {{ Html::link('/manage/tournament/', 'Create a Tournament', array('id' => 'new_tournament', 'class' => 'btn btn-default'))}}
    @endif

@endsection
@section('js')
    $(document).ready(function() {
        $('#game_sort').on("change", function() {
            var val_g = $('#game_sort option:selected').val();
            var d_id = $('#game_sort option[value="'+val_g+'"]').attr("id");
            $('#tournament_sort option').hide();
            $('#tournament_sort option[id^="'+d_id+'_"]').show();
            $('#tournament_sort option[id^="'+d_id+'_"]:first-child').attr("selected","selected");
        });
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