<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controller</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.css">
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
        /** ----------------------------------- **/
        #search,
        #submit {
            float: left;
        }

        #search {
            padding: 5px 9px;
            height: 23px;
            width: 180px;
            border: 1px solid #fab04f;
            color: #000000;
            font: normal 13px 'trebuchet MS', arial, helvetica;
            background: #f1f1f1;
            border-radius: 50px 3px 3px 50px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
        }

        /* ----------------------- */

        #submit
        {
            background-color: #fab04f;
            background-image: linear-gradient(#fab04f, #b06430);
            border-radius: 3px 50px 50px 3px;
            border-width: 1px;
            border-style: solid;
            border-color: #fefa86 #fed678 #fab04f;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.3),
            0 1px 0 rgba(255, 255, 255, 0.3) inset;
            height: 23px;
            margin: 0 0 0 -1px;
            padding: 0;
            width: 90px;
            cursor: pointer;
            font: bold 14px Arial, Helvetica;
            color: #7b4a19;
            text-shadow: 0 1px 0 rgba(255,255,255,0.5);
        }

        #submit:hover {
            background-color: #fefa86;
            background-image: linear-gradient(#b06430, #fab04f);
        }

        #submit:active {
            background: #fefa86;
            outline: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5) inset;
        }

        #submit::-moz-focus-inner {
            border: 0;  /* Small centering fix for Firefox */
        }
        #search::-webkit-input-placeholder {
            color: #9c9c9c;
            font-style: italic;
        }

        #search:-moz-placeholder {
            color: #9c9c9c;
            font-style: italic;
        }

        #search:-ms-placeholder {
            color: #9c9c9c;
            font-style: italic;
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

                        <li>{{ Html::linkAction('Backend\Manage\EmailController@email',
                        'Email Lists') }}
                        </li>
                        <li>
                            <form id="searchbox" action="">
                                <input id="search" type="text" placeholder="Type here">
                                <input id="submit" type="submit" value="Search">
                            </form>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.3/vue.js"></script>
<script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
//        $('select').select2({
//            allowClear: true
//        });

        $(document).ready(function () {
            $('#searchBar').change(function () {
                $(this).val();
            });
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