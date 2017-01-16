@extends('game.base')

@section('css')
    .deletingForms, .toForms{
    display:inline-block;
    }
    .teamName{
    display:inline-block;
    min-width:280px;
    }
@endsection
@section('content')
    <div class="col-xs-6">
        @if(!isset($maxNumOfPlayers)) {{--*/ $maxNumOfPlayers = 5; /*--}}@endif
        @if(isset($tournaments) || $tournaments != [])
            @if(isset($theTeam->name))
                <h1 class="txt-color--shadow" id="gaming-page-title">Update Team: &#8220;{{ $theTeam->name }}&#8221;</h1>
                {{ Form::open(array('id' => "teamForm", 'action' => array('Backend\Manage\TeamsController@update', $theTeam->id), 'class' => 'form-horizontal')) }}
            @else
                <h1 class="txt-color--shadow" id="gaming-page-title">Create a new Team</h1>
                {{  Form::open(array('id' => "teamForm", 'action' => array('Backend\Manage\TeamsController@store'), 'class' => 'form-horizontal')) }}
            @endif
                @if(isset($theTeam->name))
                    <input name="_method" type="hidden" value="PUT">
                @else
                    <input name="_method" type="hidden" value="POST">
                @endif
                <div class="form-group">
                    <label for="name" class="control-label col-xs-3">Team Name: </label>
                    <div class="col-xs-9">
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               placeholder="The name of the team"
                               value="@if(isset($theTeam->name)){{$theTeam->name}}@else{{ old('name') }}@endif" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="emblem" class="control-label col-xs-3">Team Emblem: </label>
                    <div class="col-xs-9">
                        <input type="text" name="emblem" id="emblem" class="form-control"
                               placeholder="The url to the emblem of the team"
                               value="@if(isset($theTeam->emblem)){{$theTeam->emblem}}@else{{ old('emblem') }}@endif" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="captain" class="control-label col-xs-3">Team Captain: </label>
                    <div class="col-xs-9">
                        <select type="text" name="captain" id="captain" class="form-control">
                            @if(isset($theTeam->name))
                                <option>---</option>
                            @else
                                <option>If the team is a new team please add players to choose a captain</option>
                            @endif

                            @foreach($players as $key => $player)
                                @if(isset($theTeam->name))
                                    @if($theTeam->id == $player['team_id'])
                                        <option value="{{$player['player_id']}}"
                                                {{-- If current captain matches this player id or the old value matches this player id set this option to selected --}}
                                                @if( isset($theTeam->captain) and $theTeam->captain==$player['player_id']) selected @elseif(old('captain') == $player['player_id']) selected @endif>{{ $player['player_username'] }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tournament_id" class="control-label col-xs-3">Team Tournament ID: </label>

                    <div class="col-xs-9">
                        <select type="text" name="tournament_id" id="tournament_id" class="form-control">
                            <option>---</option>
                            @foreach($tournaments as $key => $tournament)
                                <option value="{{$tournament['tournament_id']}}"
                                        {{-- If current tournament matches this tournament option or the old tournament Id matches then set option to selected --}}
                                        @if(isset($theTeam['tournament_id']) and $theTeam['tournament_id'] == $tournament['tournament_id']) selected @elseif(old('tournament_id') == $tournament['tournament_id']) selected @endif>{{ $tournament['tournament_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-6">
                        {{ Html::link('/manage/player/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default btn-block btn-gz-default'))}}
                    </div>
                    <div class="col-xs-6">
                        <input type="submit" name="submit" id="submit" class="btn btn-default btn-primary btn-block btn-gz" value="{{ isset($theTeam->name) ? "Update Team" : "Save Team" }}">
                    </div>
                </div>

                {{ Form::close() }}

    </div>
    <div class="col-xs-6">
        <h2>Filter Team</h2>
        <div class="form-group">
            {{ Form::open(array('id' => "teamFilter", 'action' => array('Backend\Manage\TeamsController@filter'), 'class' => 'form-horizontal')) }}
            <input name="_method" type="hidden" value="POST">
            <div class="form-group">
                <label for="game_sort" class="control-label col-xs-3">Show options only for this Game: </label>
                <div class="col-xs-9">
                    <select name="game_sort" id="game_sort" class="form-control">
                        <option> ---</option>
                        @foreach($games as $g)
                            <option id="t_option{{$g['game_id']}}" value="{{$g['game_id']}}" class="gameSelector"
                                    @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
                            >{{$g['game_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="tournament_sort" class="control-label col-xs-3">Filter by Tournament: </label>
                <div class="col-xs-9">
                    <select name="tournament_sort" id="tournament_sort" class="form-control">
                        <option> ---</option>
                        @foreach($tournaments as $g)
                            <option id="t_option{{$g['game_id']}}_{{$g['tournament_id']}}"
                                    value="{{$g['tournament_id']}}"
                                    @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['tournament_id'] == $sorts->tournament_sort or $g['tournament_name'] == $sorts->tournament_sort)) selected="selected" @endif
                            >{{$g['tournament_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::submit( 'Filter', array('class'=>'form-control btn btn-success btn-block list')) !!}
                </div>
                <div class="col-md-6">
                    {{ Html::linkAction('Backend\Manage\TeamsController@index', 'Reset Filter', [], ['class' => 'btn btn-default btn-block'])  }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="col-xs-12">
        <h2>Team List</h2>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="col-md-3 text-center">Team</th>
                <th class="col-md-2 text-center">Players of Max</th>
                <th class="col-md-7 text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
                @if(!isset($teams_filter))
                    @if(!isset($teams) || $teams == [])
                        <tr>
                            <td colspan="3"><div class="alert alert-info">There are no Teams yet.</div></td>
                        </tr>
                    @else
                        @foreach($teams as $id => $team)
                            @include('game.partials.teams_displayer')
                        @endforeach
                    @endif
                @elseif($teams_filter == [] or $teams_filter == [ ])
                    <tr>
                        <td colspan="3"><div class="alert alert-info">There are no results with the selected filter.</div></td>
                    </tr>
                @else
                    @foreach($teams_filter as $id => $team)
                        @if(isset($team['team_id']) and $team['team_id']!=null and $team['team_id']>0)
                            @include('game.partials.teams_displayer')
                        @endif
                    @endforeach
                @endif
        </tbody>
        </table>

            @else
                <h1>Sorry, no tournaments where found on the database!, please create a tournament before proceding
                    with a
                    team</h1>
                {{ Html::link('/manage/tournament/', 'Create a Tournament', array('id' => 'new_tournament', 'class' => 'btn btn-default form-control'))}}
            @endif
        </div>
    </div>
@endsection
@section('js')
    $('#game_sort').on("change", function() {
        var val_g = $('#game_sort option:selected').val();
        var d_id = $('#game_sort option[value="'+val_g+'"]').attr("id");
        $('#tournament_sort option').prop("disabled", true);
        $('#tournament_sort option[id^="'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
        $('#tournament_sort option[id^="'+d_id+'_"]:first-child').attr("selected","selected");
        $('#tournament_sort').select2({
            allowClear: true
        });
    });
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/filterForm.js"></script>
@endsection