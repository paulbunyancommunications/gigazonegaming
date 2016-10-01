
@extends('game.base')

@section('css')
    .separator{
        font-weight:bold;
        font-size:2em;
        font-color:red;
        text-decoration:underline;
    }
    .username, .name{
        min-width:220px!important;
        width:220px!important;
        max-width:220px!important;
        display:inline-block;
    }
    hr{
        height: 12px;
        border: 0;
        box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
    }
    .deletingForms, .toForms{
        display:inline-block;
    }
    .playerName{
        display:inline-block;
    min-width:300px;
    }
    .playerTeam{
        display:inline-block;
        min-width:50px;
    }
@endsection
@section('content')
    @if(!isset($maxNumOfPlayers)) {{--*/ $maxNumOfPlayers = 5; /*--}}@endif
    @if(isset($teams) || $teams != [])
        @if(isset($thePlayer['player_name']) || isset($thePlayer['player_username']) )
            @if(isset($thePlayer['player_name']) and trim($thePlayer['player_name']) != '')
                <h1>Update Player: &#8220;{{ $thePlayer['player_name'] }}&#8221;</h1>
            @elseif(isset($thePlayer['player_username']) and trim($thePlayer['player_username']) != '')
                <h1>Update Player: &#8220;{{ $thePlayer['player_username'] }}&#8221;</h1>
            @else
                <h1>Update Player</h1>
            @endif
            {{ Form::open(array('id' => "playerForm", 'action' => array('Backend\Manage\PlayersController@update', $thePlayer['player_id']))) }}
        @else
            <h1>Create a new Player</h1>
            {{  Form::open(array('id' => "playerForm", 'action' => array('Backend\Manage\PlayersController@store'))) }}
        @endif

        <div class="form-group">
            @if(isset($thePlayer['player_name']))
                <input name="_method" type="hidden" value="PUT">
            @else
                <input name="_method" type="hidden" value="POST">
            @endif
            <div class="form-group">
                <label for="name">Player Name: </label> &nbsp; <input type="text" name="name" id="name"   placeholder="The name of the player" @if(isset($thePlayer['player_name']))value="{{$thePlayer['player_name']}}"@endif/>
            </div>
            <div class="form-group">
                <label for="username">Player Username: </label> &nbsp; <input type="text" name="username" id="username"  placeholder="The username of the player" @if(isset($thePlayer['player_username']))value="{{$thePlayer['player_username']}}"@endif/>
            </div>
            <div class="form-group">
                <label for="email">Player Email: </label> &nbsp; <input type="text" name="email" id="email"  placeholder="The email of the player" @if(isset($thePlayer['player_email']))value="{{$thePlayer['player_email']}}"@endif/>
            </div>
            <div class="form-group">
                <label for="phone">Player Phone: </label> &nbsp; <input type="text" name="phone" id="phone"  placeholder="The phone of the player" @if(isset($thePlayer['player_phone']))value="{{$thePlayer['player_phone']}}"@endif/>
            </div>
            <div class="form-group">
                <label for="game_id">Attach to Game: </label> &nbsp;
                <select type="text" name="game_id" id="game_id" multiple="multiple" >
                    <option> --- </option>
                    @foreach($games as $g)
                        <option id="t_option{{$g['game_id']}}" value="{{$g['game_id']}}" class="gameSelector"
                                @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
                        >{{$g['game_name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tournament_id">Attach to Tournament: </label>
                <select name="tournament_id" id="tournament_id" multiple="multiple" >
                    <option> --- </option>
                    @foreach($tournaments as $g)
                        <option id="t_option{{$g['game_id']}}_{{$g['tournament_id']}}" value="{{$g['tournament_id']}}"
                                @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['tournament_id'] == $sorts->tournament_sort or $g['tournament_name'] == $sorts->tournament_sort)) selected="selected" @endif
                        >{{$g['tournament_name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="team_id">Attach to Team: </label> &nbsp;
                <select type="text" name="team_id" id="team_id" multiple="multiple" >
                    <option>---</option>
                    @foreach($teams as $key => $team)
                        <option value="{{$team['team_id']}}" @if(isset($thePlayer['team_id']) and $thePlayer['team_id'] == $team['team_id']) selected @endif>{{ $team['team_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="submit" class='btn btn-primary' value=
                @if(isset($thePlayer['player_name']))
                        "Update"
                @else
                    "Save"
                @endif
                >
                {{ Html::link('/manage/player/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default'))}}
            </div>
        </div>
        </form>
        {{ Form::open(array('id' => "playerFilter", 'action' => array('Backend\Manage\PlayersController@filter'))) }}
        <input name="_method" type="hidden" value="POST">
        <label for="game_sort">Show options only for this Game: </label>
        <select name="game_sort" id="game_sort" >

            <option> --- </option>
            @foreach($games as $g)
                <option id="t_option{{$g['game_id']}}" value="{{$g['game_id']}}" class="gameSelector"
                        @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
                >{{$g['game_name']}}</option>
            @endforeach
        </select>
        <br />
        <label for="tournament_sort">Filter by Tournament: </label>
        <select name="tournament_sort" id="tournament_sort" >
            <option> --- </option>
            @foreach($tournaments as $g)
                <option id="t_option{{$g['game_id']}}_{{$g['tournament_id']}}" value="{{$g['tournament_id']}}"
                        @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['tournament_id'] == $sorts->tournament_sort or $g['tournament_name'] == $sorts->tournament_sort)) selected="selected" @endif
                >{{$g['tournament_name']}}</option>
            @endforeach
        </select>
        <br />
        <label for="team_sort">Filter by Team: </label>
        <select name="team_sort" id="team_sort" >
            <option> --- </option>
            @foreach($teams as $g)
                <option id="t_option{{$g['tournament_id']}}_{{$g['team_id']}}" value="{{$g['team_id']}}"
                        @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['team_id'] == $sorts->tournament_sort or $g['team_name'] == $sorts->tournament_sort)) selected="selected" @endif
                >{{$g['team_name']}}</option>
            @endforeach
        </select>
        <br />
        {!! Form::submit( 'Filter', array('class'=>'btn btn-default list fa fa-search')) !!}
        {{ Form::close() }}
        <ul id="listOfPlayers" class="listing">
            @if(!isset($players_filter))
                @if(!isset($players) || $players == [])
                    <li>There are no Players yet</li>
                @else
                        @include('game.partials.player_displayer', ['play'=> $players])
                @endif
            @elseif($players_filter == [] or $players_filter == [ ])
                <li>There are no results with the selected filter.</li>
            @else
                <li>Filtered results: </li>
                    @include('game.partials.player_displayer', ['play'=> $players_filter])
            @endif
        </ul>

    @else
        <h1>Sorry, no players where found on the database!, please create a player before proceding with a player</h1>
        {{ Html::link('/manage/player/', 'Create a Player', array('id' => 'new_player', 'class' => 'btn btn-default'))}}
    @endif

@endsection
@section('js')
    $(document).ready(function() {
        $('#game_sort').on("change", function() {
            var val_g = $('#game_sort option:selected').val();
            var d_id = $('#game_sort option[value="'+val_g+'"]').attr("id");
            $('#tournament_sort option').prop("disabled", true);
            $('#tournament_sort option[id^="'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
            $('#tournament_sort option[id^="'+d_id+'_"]:first-child').attr("selected","selected");
            {{--$('#tournament_sort').select2({--}}
                {{--allowClear: true--}}
            {{--});--}}
        });
        $('#tournament_sort').on("change", function() {
            var val_g = $('#tournament_sort option:selected').val();
            var d_id = $('#tournament_sort option[value="'+val_g+'"]').attr("id");
            d_id = d_id.split('_')[2];
            $('#team_sort option').prop("disabled", true);
            $('#team_sort option[id^="t_option'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
            $('#team_sort option[id^="t_option'+d_id+'_"]:first-child').attr("selected","selected");
            {{--$('#team_sort').select2({--}}
            {{--allowClear: true--}}
            {{--});--}}
        });
        $('.delete-message').click(function() {
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