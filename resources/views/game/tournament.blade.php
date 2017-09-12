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
    <div class="row">
        <div class="col-xs-8">
        @if(isset($games) || $games != [])
            @if(isset($theTournament->name))
                @php ($pageTitle = $theTournament->name)
                <h1 class="txt-color--shadow" id="gaming-page-title">Edit Tournament</h1>
                <p class="txt-color--highlight" id="title-game-title">Editing: <span class="strong txt-color--primary-color-dark">&#8220;{{ $pageTitle }}&#8221;</span></p>
                {{ Form::open(array('id' => "tournamentForm", 'action' => array('Backend\Manage\TournamentsController@update', $theTournament->id), 'class' => 'form-horizontal')) }}
            @else
                @php ($pageTitle = "Create a new Tournament")
                <h1 class="txt-color--shadow" id="gaming-page-title">{{ $pageTitle }}</h1>
                {{  Form::open(array('id' => "tournamentForm", 'action' => array('Backend\Manage\TournamentsController@store'), 'class' => 'form-horizontal')) }}
            @endif

            @if(isset($theTournament->name))
                <input name="_method" type="hidden" value="PUT">
                @include('game.partials.form.tournament-required-fields', ['theTournament' => $theTournament, 'games' => $games])
            @else
                <input name="_method" type="hidden" value="POST">
                @include('game.partials.form.tournament-required-fields', ['theTournament' => [], 'games' => $games])
            @endif

                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        {{ Html::link('/manage/tournament/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-default btn-block btn-gz-default'))}}
                    </div>
                    <div class="col-xs-6">

                    <input type="submit" name="submit" id="submit" class="btn btn-default btn-primary btn-block btn-gz" value="{{ isset($theTournament->name) ? "Edit Tournament" : null  }} {{ $pageTitle  }}">
                    </div>
                </div>

            {{ Form::close() }}
    </div>
    <div class="col-xs-4">
        <h2>Filter Tournaments:</h2>
        <div class="form-group">
            {{ Form::open(array('id' => "tournamentFilter", 'action' => array('Backend\Manage\TournamentsController@filter'), 'class' => 'form-horizontal')) }}
            <input name="_method" type="hidden" value="POST">
            <div class="form-group">
                <label for="game_sort" class="control-label col-xs-4">Filter by Game: </label>
                <div class="col-xs-8">
                    <select name="game_sort" id="game_sort" class="form-control">
                        <option> ---</option>
                        @foreach($games as $g)
                            <option id="g_option{{$g['game_id']}}" value="{{$g['game_id']}}"
                                    @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
                            >{{$g['game_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::submit( 'Filter', array('class'=>'form-control btn btn-success btn-block list')) !!}
                </div>
                <div class="col-md-6">
                    {{ Html::linkAction('Backend\Manage\TournamentsController@index', 'Reset Filter', [], ['class' => 'btn btn-default btn-block'])  }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="well">
        <h2>Tournament List</h2>
        {{
            Form::button(
                'Printable Tournament List <i class="fa fa-print" aria-hidden="true"></i>',
                array('type' => 'button', 'id' => 'print-all-button-1', 'class'=>'btn btn-lg btn-gz margin-bottom toForm', 'title'=>"Print all tournament details")
            )
        }}
        {{ Html::linkAction('Backend\Manage\TournamentsController@printAll', 'Printable Tournament List <i class="fa fa-print" aria-hidden="true"></i>', [], array('target'=>'_blank','id'=>'print-all-button-form-1', 'class' => 'btn btn-default btn-lg btn-gz margin-bottom hidden', 'title'=>"Print all tournament details")) }}
        <div id="listOfTournaments" class="listing">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="col-md-4 text-center">Tournament</th>
                        <th class="col-md-2 text-center">Max Players Per Team</th>
                        <th class="col-md-2 text-center">Max Teams In Tournament</th>
                        <th class="col-md-4 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!isset($tournaments_filter))
                            @if(!isset($tournaments) || $tournaments == [])
                                <tr>
                                    <td colspan="3"><div class="alert alert-info">There are no Tournaments yet.</div></td>
                                </tr>
                            @else
                                @foreach($tournaments as $id => $tournament)
                                    @include('game.partials.tournaments_displayer')
                                @endforeach
                            @endif
                        @elseif($tournaments_filter == [] or $tournaments_filter == [ ])
                            <tr>
                                <td colspan="3">
                                    <div class="alert alert-info">There are no results with the selected filter.</div>
                                </td>
                            </tr>
                        @else
                            <h3>Filtered results:</h3>
                            @foreach($tournaments_filter as $id => $tournament)
                                @include('game.partials.tournaments_displayer')
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-info">Sorry, no games where found on the database!, please create a game before preceding with a
                    tournament</div>
                {{ Html::link('/manage/game/', 'Create a Game', array('id' => 'new_game', 'class' => 'btn btn-default')) }}
            @endif
        </div>
    </div>
    </div>

@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/filterForm.js"></script>
@endsection
@section('js')

@endsection
