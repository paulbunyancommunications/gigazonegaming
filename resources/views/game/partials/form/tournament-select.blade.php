<div class="form-group">
    <label for="tournament" class="control-label col-xs-3">Tournament</label>
    <div class="col-xs-9">
    <select id="tournament" name="tournament" class="form-control">
        <option value="0">Select a Tournament</option>
        @foreach($tournaments as $key => $tournament)
            <option value="{{ $tournament['tournament_id'] }}">{{ $tournament['tournament_title'] ? $tournament['tournament_title'] : $tournament['tournament_name'] }}</option>
        @endforeach
    </select>
    </div>
</div>