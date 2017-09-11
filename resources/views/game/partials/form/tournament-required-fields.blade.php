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
    <div class="col-xs-8">
        <input type="text" name="occurring" id="occurring" class="form-control"
               @if(isset($theTournament->occurring))value="{{$theTournament->occurring}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="name" class="control-label col-xs-4">Sign Up Open: </label>
    <div class="col-xs-8">
        <input type="text" name="sign_up_open" id="sign_up_open" class="form-control"
               @if(isset($theTournament->sign_up_open))value="{{$theTournament->sign_up_open}}"@endif/>
    </div>
</div>
<div class="form-group">
    <label for="name" class="control-label col-xs-4">Sign Up Close: </label>
    <div class="col-xs-8">
        <input type="text" name="sign_up_close" id="sign_up_close" class="form-control"
               @if(isset($theTournament->sign_up_close))value="{{$theTournament->sign_up_close}}"@endif/>
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
               @if(isset($theTournament->max_teams))value="{{$theTournament->max_teams}}"@endif/>
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