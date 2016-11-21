@extends('game.base')

@section('css')
    .form-group{
    min-height:60px;
    margin-bottom:0;
    padding: 15px;
    }
@endsection
@section('content')
    {{ Form::open(array('id' => "email-getter", 'action' => array('Backend\Manage\EmailController@email_get'), 'class' => 'form-horizontal')) }}
    <div class="col-xs-10 col-xs-push-1" >
        <h2>Email Filter</h2>
         <div class="form-group">
            <input name="_method" type="hidden" value="POST">
            <div class="form-group bg-success">
                <label for="game_sort" class="control-label col-xs-3">Get Game: </label>
                <div class="col-xs-7">
                    <select name="game_sort" id="game_sort" class="form-control">

                        <option> ---</option>
                        @foreach($games as $g)
                            <option id="game#{{$g['game_id']}}" value="{{$g['game_id']}}" class="gameSelector"
                                    @if(isset($sorts) and isset($sorts['game_sort']) and ($g['game_id'] == $sorts['game_sort']  or $g['game_name'] == $sorts['game_sort'])) selected="selected" @endif
                            >{{$g['game_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-2">
                    {!! Form::submit( '&#xf002;', array('class'=>'btn btn-success list fa fa-search form-control', 'name'=>'get_game', 'id'=>'get_game')) !!}
                </div>
            </div>
            <div class="form-group bg-info">
                <label for="tournament_sort" class="control-label col-xs-3">Get Tournament: </label>
                <div class="col-xs-7">
                    <select name="tournament_sort" id="tournament_sort" class="form-control">
                        <option> ---</option>
                        @foreach($tournaments as $g)
                            <option id="tournament#{{$g['tournament_id']}}"
                                    value="{{$g['tournament_id']}}"
                                    @if(isset($sorts) and isset($sorts['tournament_sort']) and ($g['tournament_id'] == $sorts['tournament_sort']  or $g['tournament_name'] == $sorts['tournament_sort'])) selected="selected" @endif
                            >{{$g['tournament_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-2">
                    {!! Form::submit( '&#xf002;', array('class'=>'btn btn-info list fa fa-search form-control', 'name'=>'get_tournament', 'id'=>'get_tournament')) !!}
                </div>
            </div>
            <div class="form-group bg-warning">
                <label for="team_sort" class="control-label col-xs-3">Get Team: </label>
                <div class="col-xs-7">
                    <select name="team_sort" id="team_sort" class="form-control">
                        <option> ---</option>
                        @foreach($teams as $g)
                            <option id="team#{{$g['team_id']}}" value="{{$g['team_id']}}"
                                    @if(isset($sorts) and isset($sorts['team_sort']) and ($g['team_id'] == $sorts['team_sort']  or $g['team_name'] == $sorts['team_sort'])) selected="selected" @endif
                            >{{$g['team_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-2">
                    {!! Form::submit( '&#xf002;', array('class'=>'btn btn-warning list fa fa-search form-control', 'name'=>'get_team', 'id'=>'get_team')) !!}
                </div>
            </div>
            <div class="form-group bg-danger">
                <label for="team_sort" class="control-label col-xs-3">Get Player: </label>
                <div class="col-xs-7">
                    <select name="player_sort" id="player_sort" class="form-control">
                        <option> ---</option>
                        @foreach($players as $g)
                            <option id="player#{{$g['id']}}" value="{{$g['id']}}"
                                    @if(isset($sorts) and isset($sorts['player_sort']) and ($g['id'] == $sorts['player_sort']  or $g['name'] == $sorts['player_sort'])) selected="selected" @endif
                            >{{$g['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-2">
                    {!! Form::submit( '&#xf002;', array('class'=>'btn btn-danger list fa fa-search form-control', 'name'=>'get_player', 'id'=>'get_player')) !!}
                </div>
            </div>

        </div>
    </div>
    {{ Form::close() }}
@endsection
@section('js')
    $(document).ready(function() {
    });
@endsection