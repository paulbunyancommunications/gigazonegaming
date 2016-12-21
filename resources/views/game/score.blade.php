@extends('game.base')

@section('css')
    .offset-to-label {
        padding-left: 5px;
        padding-right: 5px;
    }
    .font--display {
        font-size: 1.375em;
    }
@endsection

@section('content')
    {{ Form::hidden('old_player', old('player'), ['id' => 'old_player'])  }}
    {{ Form::hidden('old_tournament', old('tournament'), ['id' => 'old_tournament'])  }}
    <div class="col-md-12">
        <h1 class="txt-color--shadow">Score Board</h1>
    </div>
    <div class="col-xs-6">
        @if(isset($score))
            <form-title id="update-player-score-title" label="Update Player Score"></form-title>
            {!! Form::open(['action' => ['Backend\Manage\ScoresController@update', $score->id], 'class' => 'form-horizontal']) !!}
            <field-just-value label="Tournament"
                              field="tournament"
                              value="{{ $score->tournament->title ? $score->tournament->title : $score->tournament->name }}"></field-just-value>

            <field-just-value label="Player"
                              field="player"
                              value="{{ $score->player->name ? $score->player->name : $score->player->username }}"></field-just-value>
        @else
            <form-title id="create-player-score-title" label="Create a Player Score"></form-title>

            {!! Form::open(['action' => 'Backend\\Manage\\ScoresController@store', 'class' => 'form-horizontal']) !!}

            <select-list field="tournament"
                         label="Tournament"
                         :loading="loadingTournaments"
                         :options="tournaments"
                         :selected="tournament"
                         model="tournament"></select-list>

            <select-list field="player"
                         label="Player"
                         :loading="loadingPlayers"
                         :options="players"
                         :selected="player"
                         model="player"></select-list>
        @endif
            <div class="form-group">
                <label for="score" class="control-label col-xs-3">Score</label>
                <div class="col-xs-9">
                    {!!  Form::text('score', ( isset($score) ? $score->score : null ), ['id' => 'score', 'class' => 'form-control']) !!}
                </div>
            </div>
            @if(isset($score))
                <div class="row">
                    <div class="col-xs-4">{{ link_to_action('Backend\\Manage\\ScoresController@index', 'Go Back', [], ['class'=> 'btn btn-default btn-primary btn-block btn-info'])  }}</div>
                    <div class="col-xs-4">@include('game.partials.form.submit-button', ['value' => 'Update Player Score'])</div>
                    <div class="col-xs-4">{{ link_to_action('Backend\Manage\ScoresController@destroy', 'Delete Player Score', [$score->id], ['class'=> 'btn btn-default btn-primary btn-block btn-danger'])  }}</div>
                </div>
            @else
                @include('game.partials.form.submit-button', ['value' => 'Create a New Player Score'])
            @endif
        {!! Form::close() !!}
    </div>
    <div class="col-xs-6">
        <form-title id="current-score-title" label="Current Scores"></form-title>
        <select-list-scores field="scores"
                     label="Tournament With Scores"
                     :loading="loadingScores"
                     :options="uniqueScoresByTournament"
                     model="scores"></select-list-scores>
    </div>
@endsection

@section('js')
@endsection

@section('js-sheet')
    <script type="text/javascript" src="@autoVersion('/app/content/js/scoresApp.js')"></script>
@endsection
