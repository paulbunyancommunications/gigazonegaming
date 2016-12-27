<div class="form-group">
    <div class="col-md-12">
        <input type="submit"
               name="submit"
               id="submit"
               class="btn btn-default btn-primary btn-block btn-gz btn-wrap"
               value="{{ $value }}"
               @if (isset($extra) && count($extra) > 0)
               @foreach($extra as $key => $val)
               {{ $key }}="{{ $val }}"
               @endforeach
               @endif
        >
    </div>
</div>