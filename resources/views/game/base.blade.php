<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controller</title>
    <link rel="stylesheet" href="@autoVersion('/app/content/libraries/bootstrap/css/bootstrap.css')">
    <link rel="stylesheet" href="@autoVersion('/bower_components/select2/dist/css/select2.min.css')">
    <link rel="stylesheet" href="@autoVersion('/bower_components/font-awesome/css/font-awesome.css')">
    <link rel="stylesheet" href="@autoVersion('/app/content/css/app.css')">
    <style type="text/css" href="">
        @yield('css')
        body{
            overflow: scroll;
        }
        .container{
            width: 100%!important;
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
                @include('game.partials.navigation.main')
            </div>
            <div class="messages-container">
                {!! $messageHtml !!}
            </div>
            <div class="row">
                <div class="col-md-12" id="root">
                    @yield('content')
                </div>
            </div>
        </div>
        {{-- if the requirejs flag is set then load libraries as atm, otherwise load them normally --}}
        @if (isset($requireJs) && $requireJs === true)
            <script src="@autoVersion('/app/content/js/common-require.js')"></script>
            <script src="@autoVersion('/bower_components/requirejs/require.js')"></script>
            <script src="@autoVersion('/app/content/js/main-require.js')"></script>
        @else
            <script type="text/javascript" src="@autoVersion('/bower_components/jquery/dist/jquery.min.js')"></script>
            <script type="text/javascript" src="@autoVersion('/app/content/libraries/bootstrap/js/bootstrap.min.js')"></script>
            <script type="text/javascript" src="@autoVersion('/bower_components/select2/dist/js/select2.full.min.js')"></script>
        @endif
        @yield('js-sheet')
    </body>
</html>