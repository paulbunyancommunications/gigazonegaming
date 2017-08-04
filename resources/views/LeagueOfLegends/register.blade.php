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
    <Title>Player Register</Title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <a onclick="window.open('/app/player/login','_self')" id="login-form-link">Login</a>
                        </div>
                        <div class="col-xs-6">
                            <a onclick="window.open('/app/player/register','_self')" class="active" id="register-form-link">Register</a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="register-form" action="" method="POST" role="form">
                                <div class="form-group">
                                    <h2 class="form-signin-heading">Register</h2>
                                    {{ csrf_field() }}

                                    <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address">
                                    <input type="text" name="username" id="username" tabindex="2" class="form-control" placeholder="Username">
                                    <input type="text" name="phone" id="phone" tabindex="3" class="form-control" placeholder="Phone Number EX: 1234567890">
                                    <input type="password" name="password" id="password" tabindex="4" class="form-control" placeholder="Password">
                                    <input type="password" name="password_confirmation" id="password_confirmation" tabindex="5" class="form-control" placeholder="Confirm Password">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="register-submit" id="register-submit" tabindex="6" class="form-control btn btn-register" value="Register Now">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if($message != "" && $message == "Login")
<h4 class="text-center">Successfully Added! You can now <a onclick="window.open('/app/player/login','_self')">{{$message}}</a></h4>
    @else
    <h4 class="text-center">{{$message}}</h4>
@endif
</body>
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script src="/LeagueOfLegendsDisplay/JS/playerUpdate.js"></script>
</html>