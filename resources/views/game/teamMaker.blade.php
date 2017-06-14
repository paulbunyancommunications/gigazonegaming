@extends('game.base')

@section('css')
    .grouper_team_maker{
    border:5px solid #000;
    background: #c9c9c9;
    padding:30px 10px 10px 10px;
    }
    #team_selected h1{
    border-bottom:5px solid #000;
    padding-bottom:5px;
    }
@endsection
@section('content')
    <div class="form-horizontal">
    <h1>Team Filler/Maker</h1>
    <div class="form-group">
        <label for="game_sort" style="text-align:right;" class="control-label col-xs-3">Pick a Game :</label>
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
        <label for="tournament_sort" style="text-align:right;" class="control-label col-xs-3">Tournament: </label>
        <div class="col-xs-9">
            <select name="tournament_sort" id="tournament_sort" class="form-control">
                <option> ---</option>
                @foreach($tournaments as $g)
                    <option id="t_option{{$g['game_id']}}_{{$g['tournament_id']}}" max_players="{{$g['max_players']}}"
                            value="{{$g['tournament_id']}}" class="fa"
                            @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['tournament_id'] == $sorts->tournament_sort or $g['tournament_name'] == $sorts->tournament_sort)) selected="selected" @endif
                    >{{$g['tournament_name']}} ({{$g['max_players']}} players)
                    </option>
                @endforeach
            </select>
        </div>
        </div>
    <div class="form-group">
        <div class="col-xs-6 col-xs-push-6">
            <input type="submit" value="Create Team for Selected Tournament" id="team_creator_btn"
                   class="btn btn-primary form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="team_sort" class="control-label col-xs-3">Fill Team: </label>
        <div class="col-xs-9">
            <select name="team_sort" id="team_sort" class="form-control">
                <option> ---</option>
                @foreach($teams as $g)
                    @if($g['team_max_players'] > $g['team_count'])
                        <option id="t_option{{$g['tournament_id']}}_{{$g['team_id']}}" value="{{$g['team_id']}}"
                                tournament="{{$g['tournament_id']}}" team="{{$g['team_id']}}"
                                needs_players="{{$g['team_max_players']-$g['team_count']}}"
                                @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['team_id'] == $sorts->tournament_sort or $g['team_name'] == $sorts->tournament_sort)) selected="selected" @endif
                        >{{$g['team_name']}} ({{$g['team_count']}} of {{$g['team_max_players']}})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        </div>
    <div class="form-group">
        <div class="col-xs-6 col-xs-push-6">
        <input type="submit" value="Fill selected Team" id="team_fill_btn" class="btn btn-primary form-control">
    </div>
    </div>
    <div class="col-xs-12">
        <div class="form-group">
            <div id="team_selected">
                {{ Form::open(array('id' => "teamFiller", 'action' => array('Backend\Manage\IndividualPlayersController@teamFill'))) }}
                <input name="_method" type="hidden" value="PUT">
                <h1 id="title_f" class='hidden'>Add These Players To The Selected Team</h1>
                <div id="teamFilling"></div><br/>
                <div id='submit_fill_team' class='btn btn-danger col-xs-12 hidden'>Save Team</div>
                {{ Form::close() }}
                {{ Form::open(array('id' => "teamCreator", 'action' => array('Backend\Manage\IndividualPlayersController@teamCreate'))) }}
                <input name="_method" type="hidden" value="POST">
                <h1 id="title_c" class='col-xs-12 hidden'>Add These Players To A New Team</h1>
                <div id="teamCreating"></div>
                <div id='submit_create_team' class='btn btn-danger col-xs-12 hidden'>Create Team</div>

                {{ Form::close() }}
            </div>
        </div>
        <div class="form-group">
            <div id="player_list">
                @foreach($individualPlayers as $k => $player)
                    @if(!$player['team_id'])
                        <div class="btn disabled btn-success player_buttons" style="width:330px;"
                             tournament="{{$player['tournament_id']}}"
                             player="{{$player['player_id']}}"
                             id="i_{{$player['player_id']}}_t_{{$player['tournament_id']}}"
                        >{{$player['player_username']}}</div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/teamMaker.js"></script>
@endsection