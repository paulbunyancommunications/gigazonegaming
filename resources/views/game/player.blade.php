
@extends('game.base')

@section('css')
    .deletingForms{
    display:inline-block;
    }
@endsection
@section('content')

    @if(isset($tournaments) || $tournaments != [])
        <ul id="listOfTeams" class="listing">
            @if(!isset($teams) || $teams == [])
                <li>There are no Teams yet</li>
            @else
                @foreach($teams as $id => $team)
                    <li>
                        {{ Html::linkAction('Backend\Manage\TeamsController@index', $team["name"], array('class' => 'btn btn-default list')) }}
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
    @else
        <h1>Sorry, no tournaments where found on the database!, please create a tournament before proceding with a team</h1>
        {{ Html::link('/manage/tournament/', 'Create a Tournament', array('id' => 'new_tournament', 'class' => 'btn btn-default'))}}
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