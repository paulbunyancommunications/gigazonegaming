<!doctype html>
<html lang="{{ config('app.locale')}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/app/content/css/teamDisplay.css">
    <link rel="stylesheet" type="text/css" href="/app/content/css/override.css">


    <Title>Champion Override</Title>
</head>
<body class="mainDiv">
<h1>Champion Override</h1>
<select  id="Team">
    <option>Team 1</option>
    <option>Team 2</option>
</select><br/>
<label class="playerHeading" for="player1">Player1:</label><br/><select id="player1">@yield('options')</select><br/>
<label class="playerHeading" for="player2">Player2:</label><br/><select id="player2">@yield('options')</select><br/>
<label class="playerHeading" for="player3">Player3:</label><br/><select id="player3">@yield('options')</select><br/>
<label class="playerHeading" for="player4">Player4:</label><br/><select id="player4">@yield('options')</select><br/>
<label class="playerHeading" for="player5">Player5:</label><br/><select id="player5">@yield('options')</select><br/>
    <button id='SubmitChamps' class="startButton" onclick="findChampion();">Submit</button>
<div id="info"></div>
</body>
<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>
<script src="/LeagueOfLegendsDisplay/JS/override.js">
</script>
</html>
