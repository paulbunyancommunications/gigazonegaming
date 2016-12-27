<nav class="navbar navbar-default" role="navigation">
{{--<div class="container-fluid">--}}
<!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">

            <li>{{ Html::linkAction('Backend\Manage\GamesController@index',
                        'Games') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\TournamentsController@index',
                        'Tournaments') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\TeamsController@index',
                        'Team') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\PlayersController@index',
                        'Players') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\IndividualPlayersController@index',
                        'Individual Players') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\IndividualPlayersController@teamMake',
                        'Team Maker') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\ScoresController@index',
                        'Scores') }}
            </li>

            <li>{{ Html::linkAction('Backend\Manage\EmailController@email',
                        'Email Lists') }}
            </li>
        </ul>

        <div class="col-sm-3 col-md-3">

{{--            {{ Form::open(array('id' => 'page-text-search', 'action' => array('Backend\Manage\search@doSearch'), 'class' => "toForms")) }}
                <input name="_method" type="hidden" value="POST">
                <input name="tournament_sort" type="hidden" value="{{$tournament["tournament_id"]}}">
            {{ Form::close() }}--}}
            <div id="searchBar">
            {{  Form::open(array('method' => 'POST','id' => "searchbox", 'action' => array('Backend\Manage\SearchController@doSearch'), 'class' => 'navbar-form page-text-search doAjaxForm doForm', 'role' => 'search')) }}

                <div class="input-group">
                    {{ Form::input('search','search',null, ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'searchText'])  }}
{{--
                    <input id="searchText" class="form-control" type="text" placeholder="Search" name="search">
--}}
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit" id="doSearchBoxSubmit"><i class="fa fa-search" aria-hidden="true"></i>
                            <span style="display: none" class="progress-container"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i></span>
                        </button>
                    </div>
                </div>
            <div class="message-container"></div>
            {{ Form::close() }}
            </div>
        </div>
    </div><!-- /.navbar-collapse -->
    <div class="searchResults" id="searchResults">@include('game.partials.search')</div>

    {{--</div><!-- /.container-fluid -->--}}
</nav>