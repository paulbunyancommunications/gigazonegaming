<div class="form-group">
    <label for="name" class="control-label col-xs-4">Tournament Name: </label>
    <div class="col-xs-8">
        <input type="text" name="name" id="name" class="form-control"
               placeholder="The name of the tournament"
               @if(isset($theTournament->name))value="{{$theTournament->name}}"@endif/>
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