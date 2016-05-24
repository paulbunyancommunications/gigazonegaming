
@extends('game.base')

@section('css')
    .deletingForms{
    display:inline-block;
    }
@endsection
@section('content')

    @if(isset($teams) || $teams != [])
        <ul id="listOfPlayers" class="listing">
            @if(!isset($players) || $players == [])
                <li>There are no Players yet</li>
            @else
                @foreach($players as $id => $player)
                    <li>
                        <span class='username' id='{{$player["username"]}}'> Username: {{$player["username"]}}</span> <span class='name' id='{{$player["name"]}}'>Name: {{$player["name"]}}</span> <span class='team_id' id='{{$player["team_id"]}}'>Team: {{$player["team_id"]}}</span>
                        &nbsp;&nbsp;
                        {{ Html::linkAction('Backend\Manage\PlayersController@edit', 'Edit', array('player_id'=>$player["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o')) }}
                        &nbsp;&nbsp;
                        {{ Form::open(array('id' => "playerForm".$player["id"], 'action' => array('Backend\Manage\PlayersController@destroy', $player["id"]), 'class' => "deletingForms")) }}
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
            <div class="alert alert-success"><strong>Success!</strong> You have updated this Player.</div>
        @endif
        @if(isset($thePlayer->name))
            {{ Form::open(array('id' => "playerForm", 'action' => array('Backend\Manage\PlayersController@update', $thePlayer->id))) }}
        @else
            {{  Form::open(array('id' => "playerForm", 'action' => array('Backend\Manage\PlayersController@create'))) }}
        @endif

        <div class="form-group">
            @if(isset($thePlayer->name))
                <input name="_method" type="hidden" value="PUT">
            @else
                <input name="_method" type="hidden" value="POST">
            @endif
            <div class="form-group">
                <label for="name">Player Name: </label> &nbsp; <input type="text" name="name" id="name" placeholder="The name of the player" @if(isset($thePlayer->name))value="{{$thePlayer->name}}"@endif/>
            </div>
            <div class="form-group">
                <label for="emblem">Player Emblem: </label> &nbsp; <input type="text" name="emblem" id="emblem" placeholder="The url to the emblem of the player" @if(isset($thePlayer->emblem))value="{{$thePlayer->emblem}}"@endif/>
            </div>
            <div class="form-group">
                <label for="team_id">Player Team ID: </label> &nbsp;
                <select type="text" name="team_id" id="team_id" >
                    @foreach($teams as $key => $team)
                        <option value="{{$team['id']}}" @if(isset($thePlayer['team_id']) and $thePlayer['team_id'] == $team['id']) selected @endif>{{ $team['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="submit" class='btn btn-default' value="Save">
                {{ Html::link('/manage/player/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default'))}}
            </div>
        </div>
        </form>
    @else
        <h1>Sorry, no teams where found on the database!, please create a team before proceding with a player</h1>
        {{ Html::link('/manage/team/', 'Create a Team', array('id' => 'new_team', 'class' => 'btn btn-default'))}}
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