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
    <Title>Create Password</Title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <a onclick="window.open('/app/player/login','_self')" class="active" id="login-form-link">Login</a>
                        </div>
                        <div class="col-xs-6">
                            <a onclick="window.open('/app/player/register','_self')" id="register-form-link">Register</a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="login-form" method="POST" role="form">
                                <div class="form-group">
                                    <h2 class="form-signin-heading">Create Password</h2>
                                    {{ csrf_field() }}
                                    <input type="password" name="password" id="password" tabindex="1" class="form-control" placeholder="Password">
                                    <input type="password" name="password_confirmation" id="password_confirmation" tabindex="2" class="form-control" placeholder="Confirm Password">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="password-submit" id="password-submit" tabindex="3" class="form-control btn btn-login" value="Submit">
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
@if($success)
    <div class="alert alert-success">
        <ul>
            <li>{{ $success }}</li>
        </ul>
    </div>
@endif
</body>
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</html>