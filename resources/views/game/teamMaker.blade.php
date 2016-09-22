
@extends('game.base')

@section('css')
@endsection
@section('content')

    <label for="game_sort" style="width:180px; text-align:right;">Pick a Game :</label>
    <select name="game_sort" id="game_sort" style="width:350px; text-align:left;">

        <option> --- </option>
        @foreach($games as $g)
            <option id="t_option{{$g['game_id']}}" value="{{$g['game_id']}}" class="gameSelector"
                    @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
            >{{$g['game_name']}}</option>
        @endforeach
    </select>
    <br />
    <label for="tournament_sort" style="width:180px; text-align:right;">Tournament: </label>
    <select name="tournament_sort" id="tournament_sort" style="width:350px; text-align:left;">
        <option> --- </option>
        @foreach($tournaments as $g)
            <option id="t_option{{$g['game_id']}}_{{$g['tournament_id']}}"  max_players="{{$g['max_players']}}" value="{{$g['tournament_id']}}" class="fa"
                    @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['tournament_id'] == $sorts->tournament_sort or $g['tournament_name'] == $sorts->tournament_sort)) selected="selected" @endif
            >{{$g['tournament_name']}} ({{$g['max_players']}} players)</option>
        @endforeach
    </select>
    <input type="submit" value="Create Team for Selected Tournament"  id="team_creator_btn" class="btn btn-primary">
    <br />
    <label for="team_sort" style="width:180px; text-align:right;">Filter by Team: </label>
    <select name="team_sort" id="team_sort" style="width:350px; text-align:left;">
        <option> --- </option>
        @foreach($teams as $g)
            @if($g['team_max_players'] > $g['team_count'])
            <option id="t_option{{$g['tournament_id']}}_{{$g['team_id']}}" value="{{$g['team_id']}}" max_players="{{$g['team_max_players']}}" team_players="{{$g['team_count']}}" needs_players="{{$g['team_max_players']-$g['team_count']}}"
                    @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['team_id'] == $sorts->tournament_sort or $g['team_name'] == $sorts->tournament_sort)) selected="selected" @endif
            >{{$g['team_name']}} ({{$g['team_count']}} of {{$g['team_max_players']}})</option>
            @endif
        @endforeach
    </select>
    <input type="submit" value="Fill selected Team" id="team_fill_btn" class="btn btn-primary">
    <br />
    <div id="team_selected">
        {{ Form::open(array('id' => "teamFiller", 'action' => array('Backend\Manage\IndividualPlayersController@teamFill'))) }}
        <input name="_method" type="hidden" value="PUT">
        {{ Form::close() }}
        {{ Form::open(array('id' => "teamCreator", 'action' => array('Backend\Manage\IndividualPlayersController@teamCreate'))) }}
        <input name="_method" type="hidden" value="POST">
        {{ Form::close() }}
    </div>
    <div id="player_list">
    @foreach($individualPlayers as $k => $player)
        <div class="btn disabled btn-success player_buttons"
                tournament="{{$player['tournament_id']}}"
                player="{{$player['player_id']}}"
        >{{$player['player_username']}}</div>
    @endforeach
    </div>
    <br />
@endsection
@section('js')

    $('#game_sort').on("change", function() {
    var val_g = $('#game_sort option:selected').val();
    var d_id = $('#game_sort option[value="'+val_g+'"]').attr("id");
    $('#tournament_sort option').prop("disabled", true);
    $('#tournament_sort option[id^="'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
    $('#tournament_sort option[id^="'+d_id+'_"]:first-child').attr("selected","selected");
    $('.player_buttons.button-primary').removeClass("button-primary").addClass("btn-success");
    $('#tournament_sort').select2({
    allowClear: true
    });
    });
    $('#tournament_sort').on("change", function() {
    var val_g = $('#tournament_sort option:selected').val();
    $('.player_buttons[tournament="'+val_g+'"]').removeClass("btn-success").addClass("btn-primary");
    var d_id = $('#tournament_sort option[value="'+val_g+'"]').attr("id");
    d_id = d_id.split('_')[2];
    $('#team_sort option').prop("disabled", true);
    $('#team_sort option[id^="t_option'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
    $('#team_sort option[id^="t_option'+d_id+'_"]:first-child').attr("selected","selected");
    $('#team_sort').select2({
    allowClear: true
    });
    });
@endsection