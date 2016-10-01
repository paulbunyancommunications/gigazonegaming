<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controller</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/app/content/css/app.css">
    <style type="text/css" href="">
        @yield('css')
        body{
            overflow: scroll;
        }
        .fa,
        .fa:before{
            font-family: "FontAwesome"!important;
        }
        .form-group, .listing, form{
            text-align: center;
        }
        ul, li{
            list-style: none;
        }
    </style>
    <script src="https://use.typekit.net/bhh0sxx.js"></script>
    <script>try{Typekit.load({ async: true });}catch(e){}</script>
</head>
<body>
<div class="container" id="page-content">
    <div class="row">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
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

                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
    <div class="messages-container">
        {!! $messageHtml !!}
    </div>
    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>
<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
{{--<script type="text/javascript" src="/bower_components/select2/dist/js/select2.full.min.js"></script>--}}
<script type="text/javascript">

    $(document).ready(function() {
//        $('select').select2({
//            allowClear: true
//        });

        $(document).ready(function () {
            $('#theIframe', window.parent.document).height($('#page-content').height()).css('min-height', 700);
//            $('#wpfooter', window.parent.document).addClass('hidden');
            var toAdd = '</div><div id="pageLinker2" class="btn-group btn-group-justified" role="group" aria-label="Justified button group">';
        });
        @yield('js')
    });
</script>
@yield('js-sheet')
</body>
</html>