<div class="form-group">
    <label for="name" class="control-label col-xs-4">Tournament Name: </label>
    <div class="col-xs-8">
        <input type="text" name="name" id="name" class="form-control"
               placeholder="The name of the tournament"
               @if(isset($theTournament->name))value="{{$theTournament->name}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="name" class="control-label col-xs-4">Tournament Title: </label>
    <div class="col-xs-8">
        <input type="text" name="title" id="title" class="form-control"
               placeholder="The title of the tournament"
               @if(isset($theTournament->title))value="{{$theTournament->title}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="name" class="control-label col-xs-4">Occurring On: </label>
    <div class="col-xs-4">
        <input type="text" name="occurring_date" id="occurring_date" class="form-control datepicker"
               @if(isset($theTournament->occurring_date))value="{{$theTournament->occurring_date}}"@endif/>
    </div>
    <div class="col-xs-4">
        <input type="text" name="occurring_time" id="occurring_time" class="form-control timepicker"
               @if(isset($theTournament->occurring_time))value="{{$theTournament->occurring_time}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="name" class="control-label col-xs-4">Sign Up Open: </label>
    <div class="col-xs-4">
        <input type="text" name="sign_up_open_date" id="sign_up_open_date" class="form-control datepicker"
               @if(isset($theTournament->sign_up_open_date))value="{{$theTournament->sign_up_open_date}}"@endif/>
    </div>
    <div class="col-xs-4">
        <input type="text" name="sign_up_open_time" id="sign_up_open_time" class="form-control timepicker"
               @if(isset($theTournament->sign_up_open_time))value="{{$theTournament->sign_up_open_time}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="name" class="control-label col-xs-4">Sign Up Close: </label>
    <div class="col-xs-4">
        <input type="text" name="sign_up_close_date" id="sign_up_close_date" class="form-control datepicker"
               @if(isset($theTournament->sign_up_close_date))value="{{$theTournament->sign_up_close_date}}"@endif/>
    </div>
    <div class="col-xs-4">
        <input type="text" name="sign_up_close_time" id="sign_up_close_time" class="form-control timepicker"
               @if(isset($theTournament->sign_up_close_time))value="{{$theTournament->sign_up_close_time}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="max_players" class="control-label col-xs-4">Players per Team: </label>
    <div class="col-xs-8">
        <input type="number" min="1" max="20" name="max_players" id="max_players" class="form-control"

               placeholder="The maximum amount of players per team"
               @if(isset($theTournament->max_players))value="{{$theTournament->max_players}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="max_teams" class="control-label col-xs-4">Teams per Tournament: </label>
    <div class="col-xs-8">
        <input type="number" min="2" max="60" name="max_teams" id="max_teams" class="form-control"
               placeholder="The maximum amount of teams per tournament"
               @if(isset($theTournament->max_teams))value=@if($theTournament->max_teams<2) {{"2"}} @else "{{$theTournament->max_teams}}" @endif @endif/>
    </div>
</div>
<div class="form-group">
    <label for="game_id" class="control-label col-xs-4">Tournament Game ID: </label>
    <div class="col-xs-8">
        <select type="text" name="game_id" id="game_id" class="form-control">
            <option>---</option>
            @foreach($games as $key => $game)
                <option value="{{$game['game_id']}}"
                        @if(isset($theTournament['game_id']) and $theTournament['game_id'] == $game['game_id']) selected @endif
                >{{ $game['game_name'] }}</option>
            @endforeach
        </select>
    </div>
</div>