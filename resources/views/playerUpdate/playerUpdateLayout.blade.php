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
    <form class="text-center mainDiv" method="POST">
        {{ csrf_field() }}
        <div class="margin-bottom">
            <label for="name" class="">Name: </label>
            <input type="text" name="name" id="name" placeholder="Name" value="@yield('Name')"/>
        </div>
        <div class="margin-bottom">
            <label for="username">Username: </label>
            <input type="text" name="username" id="username" placeholder="Username" value="@yield('Username')"/>
        </div>
        @yield('UserNames')
        <div class="margin-bottom">
            <label for="email">Email: </label>
            <input type="text" name="email" id="email" value="@yield('Email')" readonly/>
        </div>
        <div class="margin-bottom">
            <label for="phone">Phone: </label>
            <input type="text" name="phone" id="phone" placeholder="Phone" value="@yield('Phone')"/>
        </div>
        <button class="btn" onclick="window.open('/app/player/playerUpdate','_self')">Update</button>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger text-center">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
        </div>
    @endif
    @if (session('success'))
        <div class=" alert alert-success text-center">
            {{session('success')}}
        </div>
    @endif
    <div class="mainDiv">
        @yield('Form')
    </div>
    <div class="text-center">
        <button class="btn margin-sm-top" id="logout" onclick="window.open('/app/player/logout','_self')">Logout</button>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script src="/resources/assets/js/playerUpdate.js"></script>
</html>