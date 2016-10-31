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

            <li>{{ Html::linkAction('Backend\Manage\EmailController@email',
                        'Email Lists') }}
            </li>
        </ul>

        <div class="col-sm-3 col-md-3">
            <form class="navbar-form" role="search" id="searchbox" action="">
                <div class="input-group">
                    <input id="searchText" class="form-control" type="text" placeholder="Search">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div><!-- /.navbar-collapse -->
    {{--</div><!-- /.container-fluid -->--}}
</nav>