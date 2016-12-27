<div>
    <div class="col-xs-3 text-right">
        <p class="offset-to-label font--display">{{ $label }}</p>
    </div>
    <div class="col-xs-9 text-left">
        <p class="bold" :id="{{ $field }}" :v-model="{{ $field }}">{{ $value }}</p>
    </div>
</div>