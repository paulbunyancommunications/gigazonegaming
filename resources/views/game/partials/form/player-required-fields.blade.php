<div class="form-group">
    <label for="name" class="control-label col-xs-3">Player Name: </label>
    <div class="col-xs-9">
        <input type="text" name="name" id="name" placeholder="The name of the player"
               class="form-control"
               @if(isset($thePlayer['name']))value="{{$thePlayer['name']}}"
               @else value="{{ old('name') }}" @endif/>
    </div>
</div>
<div class="form-group">
    <label for="username" class="control-label col-xs-3">Player Username: </label>
    <div class="col-xs-9">
        <input type="text" name="username" id="username" placeholder="The username of the player"
               class="form-control"
               @if(isset($thePlayer['username']))value="{{$thePlayer['username']}}"
               @else value="{{ old('username') }}" @endif/>
    </div>
</div>
<div class="form-group">
    <label for="email" class="control-label col-xs-3">Player Email: </label>
    <div class="col-xs-9">
        <input type="text" name="email" id="email" placeholder="The email of the player"
               class="form-control"
               @if(isset($thePlayer['email']))value="{{$thePlayer['email']}}"
               @else value="{{ old('email') }}" @endif/>
    </div>
</div>
<div class="form-group">
    <label for="phone" class="control-label col-xs-3">Player Phone: </label>
    <div class="col-xs-9">
        <input type="text" name="phone" id="phone" placeholder="The phone of the player"
               class="form-control"
               @if(isset($thePlayer['phone']))value="{{$thePlayer['phone']}}"
               @else value="{{ old('phone') }}" @endif/>
    </div>
</div>