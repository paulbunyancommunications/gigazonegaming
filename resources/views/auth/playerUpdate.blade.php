<!doctype html>
<html lang="{{ config('app.locale')}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="@autoVersion('/app/content/libraries/bootstrap/css/bootstrap.css')">
    <link rel="stylesheet" href="@autoVersion('/bower_components/select2/dist/css/select2.min.css')">
    <link rel="stylesheet" href="@autoVersion('/bower_components/font-awesome/css/font-awesome.css')">
    <link rel="stylesheet" href="@autoVersion('/app/content/css/app.css')">
    <link rel="stylesheet" href="@autoVersion('/app/content/css/playerUpdate.css')">
    <Title>Player Update</Title>
</head>
<body>
    <h1 class="txt-center">Player Update</h1>
    <div class="text-center mainDiv">
        <div class="margin-bottom">
            <label for="name" class="">Name: </label>
            <input type="text" name="name" id="name" placeholder="Name" value="{{$token->name}}"/>
        </div>
        <div class="margin-bottom">
            <label for="username">Username: </label>
            <input type="text" name="username" id="username" placeholder="Username" value="{{$token->username}}"/>
        </div>
        <div class="margin-bottom">
            <label for="email">Email: </label>
            <input type="text" name="email" id="email" placeholder="Email" value="{{$token->email}}"/>
        </div>
        <div class="margin-bottom">
            <label for="phone">Phone: </label>
            <input type="text" name="phone" id="phone" placeholder="Phone" value="{{$token->phone}}"/>
        </div>
        <button class="btn" onclick="UpdatePlayerInfo()">Update</button>
    </div>
    <div class="mainDiv text-center">
        <h3>Additional Information</h3>
            <h4>Tournaments Entered:</h4>
                <div class="margin-bottom">
                    <b>League of Legends</b><br/>
                    Team Name: <br/>
                    Summoner Name: {{$token->username}}
                </div>
                <div class="margin-bottom">
                    <b>OverWatch</b><br/>
                    Team Name: <br/>
                    Player Name:
                </div>
                <div class="margin-bottom">
                    <b>Madden</b><br/>
                    Team Name: <br/>
                    Player Name:
                </div>
    </div>
<button onclick="">Logout</button>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script src="/resources/assets/js/playerUpdate.js"></script>
</html>