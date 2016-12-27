@extends('game.base')

@section('css')
    .offset-to-label {
        padding-left: 5px;
        padding-right: 5px;
    }
    .font--display {
        font-size: 1.375em;
    }

    .score-button-modal-open {
        margin-top: 5px;
    }
@endsection

@section('content')
    {{--
    ============================================================================
    Create player and tournament modal popup
    ============================================================================
    --}}
    @foreach(['player', 'tournament'] as $type)
    <template id="create{{ ucfirst($type) }}">
        <div class="modal fade" id="create{{ ucfirst($type) }}Modal" tabindex="-1" role="dialog" aria-labelledby="create{{ ucfirst($type) }}ModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="create{{ ucfirst($type)  }}ModalLabel">Create a New {{ ucfirst($type)  }}</h4>
                    </div>
                    <div class="modal-body">
                        {{--
                        ============================================================================
                        Message container for each modal handled by vue
                        ============================================================================
                        --}}
                        @foreach(['error', 'success'] as $message)
                            <template id="{{ $message }}{{  ucfirst($type) }}ModalTemplate">
                                <div class="alert alert-{{ $message }} alert-dismissible" v-show="{{ $type }}Modal{{ ucfirst($message) }}.length > 0" style="display: none;" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i
                                                    class="fa fa-times"></i></span></button>
                                    <p v-for="message in {{ $type }}Modal{{ ucfirst($message) }}">
                                        @{{ message | stripHtmlTags }}
                                    </p>
                                </div>
                            </template>
                        @endforeach
                        {{--
                        ============================================================================
                        Form includes for each modal
                        ============================================================================
                        --}}
                        @if($type === 'player')
                            {!! Form::open(['action' => 'Backend\Manage\PlayersController@store', 'method' => 'post', 'id' => 'createNewPlayerForm', 'class' => 'form-horizontal']) !!}
                            @include('game.partials.form.player-required-fields', ['thePlayer' => []])
                        @elseif($type === 'tournament')
                            {!! Form::open(['action' => 'Backend\Manage\TournamentsController@store', 'method' => 'post', 'id' => 'createNewTournamentForm', 'class' => 'form-horizontal']) !!}
                            @include('game.partials.form.tournament-required-fields', ['theTournament' => [], 'games' => $games])
                        @endif
                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" data-form="{{ $type }}" data-formid="createNew{{ ucfirst($type) }}Form" data-formtype="modal" v-on:click="createNewForm" class="btn btn-primary btn-gz">Save New {{ $type  }}<span v-show="loadingNew{{ ucfirst($type) }}Form" class="txt-color--branding">&nbsp;<i class="fa fa-refresh fa-spin"></i></span></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </template>
    @endforeach

    {{--
    ============================================================================
    Message container handled by Vue
    ============================================================================
    --}}
    @foreach(['error', 'success'] as $message)
        <template id="{{ $message }}MessageTemplate">
            <div class="alert alert-{{ $message }} alert-dismissible" v-show="{{ $message }}.length > 0" style="display: none;" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i
                                class="fa fa-times"></i></span></button>
                <p v-for="message in {{ $message }}">
                    @{{ message | stripHtmlTags }}
                </p>
            </div>
        </template>
    @endforeach
    {{--
    ============================================================================
    New score / update score forms
    ============================================================================
    --}}
    <div class="col-md-12">
        <h1 class="txt-color--shadow">Score Board</h1>
    </div>
    <div class="col-xs-6">

        {{--  Old/current score, player and tournament fields value  --}}
        {{ Form::hidden('oldScore', ( isset($score) ? $score->score : old('score') ), ['id' => 'oldScore']) }}
        {{ Form::hidden('oldPlayer', old('player'), ['id' => 'oldPlayer']) }}
        {{ Form::hidden('oldTournament', old('tournament'), ['id' => 'oldTournament']) }}
        {{-- If score is set (we're on either the update or view route, then display form with just score editable) --}}
        @if(isset($score))

            <section-title id="update-player-score-title" label="Update Player Score"></section-title>
            {{ Form::open(['action' => ['Backend\Manage\ScoresController@update', $score->id], 'method' => 'put', 'id' => 'updateScoreForm', 'class' => 'form-horizontal']) }}

            @include('game/partials/form/vue/vue-field-just-value', [
                'label' => 'Tournament',
                'field' => 'tournamentSelect',
                'value' => $score->tournament->title ? $score->tournament->title : $score->tournament->name
            ])
            @include('game/partials/form/vue/vue-field-just-value', [
                'label' => 'Player',
                'field' => 'playerSelect',
                'value' => $score->player->name ? $score->player->name : $score->player->username
            ])
        {{-- Else show the new score entry form --}}
        @else
            <section-title id="create-player-score-title" label="Create a Player Score"></section-title>

            {{ Form::open(['action' => 'Backend\\Manage\\ScoresController@store', 'id' => 'createNewScoreForm', 'class' => 'form-horizontal', 'method' => 'post' ]) }}
            {{-- Tournament field --}}
            <div class="row">
                <div class="col-xs-10">
                    @include('game/partials/form/vue/vue-select', [
                        'field' => 'tournament',
                        'label' => 'Tournament',
                        'loading'=>'loadingTournaments',
                        'options'=> 'tournaments',
                        'selected' => 'tournamentOld',
                        'model' => 'tournamentSelect'
                    ])
                </div>
                <div class="col-xs-2">
                    <button type="button"
                            tabindex="-1"
                            class="btn btn-default btn-block btn-xs btn-gz score-button-modal-open"
                            data-toggle="modal"
                            data-target="#createTournamentModal">Create</button>
                </div>
            </div>
            {{-- Player field --}}
            <div class="row">
                <div class="col-xs-10">
                    @include('game/partials/form/vue/vue-select', [
                        'field' => 'player',
                        'label' => 'Player',
                        'loading'=>'loadingPlayers',
                        'options'=> 'players',
                        'selected' => 'playerOld',
                        'model' => 'playerSelect'
                    ])
                </div>
                <div class="col-xs-2">
                    <button type="button"
                            tabindex="-1"
                            class="btn btn-default btn-block btn-xs btn-gz score-button-modal-open"
                            data-toggle="modal"
                            data-target="#createPlayerModal">Create</button>
                </div>
            </div>
        @endif
            {{-- Score field used in both the edit and create routes --}}
            <div class="form-group">
                @if(isset($score))
                    <label for="score" class="control-label col-xs-3">Score</label>
                    <div class="col-xs-9">
                        {{  Form::text('score', '', ['id' => 'score', 'v-model' => 'score', 'class' => 'form-control']) }}
                    </div>
                @else
                    <div class="col-sm-10">
                        <div class="row">
                            <label for="score" class="control-label col-xs-3">Score</label>
                            <div class="col-xs-9">
                                {{  Form::text('score', '', ['id' => 'score', 'v-model' => 'score', 'class' => 'form-control']) }}
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-2">&nbsp;</div>
                @endif
            </div>
            {{-- if score is availible, then show the back/edit/delete buttons --}}
            @if(isset($score))
                <div class="row">
                    <div class="col-xs-4">{{ link_to_action('Backend\\Manage\\ScoresController@index', 'Create a new score', [], ['class'=> 'btn btn-default btn-primary btn-block btn-info btn-wrap text-uppercase'])  }}</div>
                    <div class="col-xs-4"><a href="#" v-on:click="createNewForm" data-form="score" data-formtype="update" data-formid="updateScoreForm" class="btn btn-default btn-primary btn-block btn-gz btn-wrap" :disabled="!score">
                        Update Player Score<span v-show="loadingNewScoreForm" class="txt-color--branding">&nbsp;<i class="fa fa-refresh fa-spin"></i></span>
                        </a></div>

                    <div class="col-xs-4">{{ link_to_action('Backend\Manage\ScoresController@destroy', 'Delete player Score', [$score->id], ['class'=> 'btn btn-default btn-primary btn-block btn-danger btn-wrap text-uppercase'])  }}</div>
                </div>
            {{-- otherwise just show the create button --}}
            @else
                    <a href="#" v-on:click="createNewForm" data-form="score" data-formtype="create" data-formid="createNewScoreForm" class="btn btn-default btn-primary btn-block btn-gz btn-wrap" :disabled="!playerSelect || !tournamentSelect || !score" class="">
                        Create a New Player Score<span v-show="loadingNewScoreForm" class="txt-color--branding">&nbsp;<i class="fa fa-refresh fa-spin"></i></span>
                    </a>
            @endif
        {!! Form::close() !!}
    </div>
    {{--
    ============================================================================
    Filter scores by tournament
    ============================================================================
    --}}
    <div class="col-xs-6">
        <section-title id="current-score-title" label="Current Scores"></section-title>

        @include('game/partials/form/vue/vue-select', [
            'field' => 'uniqueTournamentSelect',
            'label' => 'Tournament With Scores',
            'loading'=>'loadingTournaments',
            'options'=> 'uniqueScoresByTournament',
            'selected' => '',
            'model' => 'uniqueTournamentScoreSelect'
        ])

    </div>

@endsection

@section('js')
@endsection

@section('js-sheet')
    <script type="text/javascript" src="@autoVersion('/app/content/js/scoresApp.js')"></script>
@endsection
