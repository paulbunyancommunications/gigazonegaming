<!doctype html>
<html lang="{{ config('app.locale')}}" style="@yield('Color')">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href='/LeagueOfLegendsDisplay/CSS/teamDisplay.css'/>
    <Title>Team Display</Title>
</head>
<body class="altBodyBackground">
<div class="teamName">@yield('TeamName')</div>
<button onclick="window.open('/app/GameDisplay/override');" style="bottom: 0; right: 0; position: fixed; z-index: 300;">Champion Override</button>
</body>

<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>
<script src="/LeagueOfLegendsDisplay/JS/teamDisplay.js"></script>
</html>