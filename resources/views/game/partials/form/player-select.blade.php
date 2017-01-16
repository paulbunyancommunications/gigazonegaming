<div class="form-group">
    <label for="player" class="control-label col-xs-3">Player</label>
    <div class="col-xs-9">
        <select id="player" name="player" class="form-control">
            <option>Select a Player</option>
                @foreach($players as $key => $player)
                    <option value="{{ $player['id'] }}">{{ $player['name'] }}</option>
                @endforeach
        </select>
    </div>
</div>