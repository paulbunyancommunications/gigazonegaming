<html><header></header>
<body>
{{--{{dd($playerList)}}--}}
<table>
    <thead>
    <tr>
        <th>Player Username</th>
        <th>Player Name</th>
        <th>Player Email</th>
        <th>Player Phone</th>
        <th>Team Capitan</th>
        <th>Team</th>
        <th>Tournament</th>
        <th>Game</th>
    </tr>
    </thead>
    <tbody>
@foreach($playerList as $player)

    <tr>
        <td>{{$player['player_username']}}</td>
        <td>{{$player['player_name']}}</td>
        <td>{{$player['player_email']}}</td>
        <td>{{$player['player_phone']}}</td>
        <td>@if($player['player_id'] == $player['team_captain']) Yes @else No @endif</td>
        <td>{{$player['team_name']}}</td>
        <td>{{$player['tournament_name']}}</td>
        <td>{{$player['game_title']}}</td>
    </tr>
@endforeach
    </tbody>
</table>
</body>
</html>