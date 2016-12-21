<div class="form-group">
    <lable for="game" class="control-label col-xs-3">Game</lable>
    <div class="col-xs-9">
    <select id="game" name="game" class="form-control">
        <option>Select a game</option>
        @foreach($games as $key => $game)
            <option value="{{ $game['game_id'] }}">{{ $game['game_title'] ? $game['game_title'] : $game['game_name'] }}</option>
        @endforeach
    </select>
    </div>
</div>