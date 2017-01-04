<div class="form-group">
    <label for="{{ $field }}" class="control-label col-xs-3">{{ $label }}
        <span id="loading{{ ucfirst($field) }}Spinner" v-show="{{ $loading }}" class="txt-color--branding">&nbsp;<i class="fa fa-refresh fa-spin"></i></span>
        </label>
    <div class="col-xs-9">
        <select name="{{ $field }}" id="{{ $field }}" class="form-control" v-model="{{ $model }}">
            <option>Select a {{ $label }}</option>
            <template v-for="{{ str_singular($options)  }} in {{ $options }}">
                <option v-if="{{ str_singular($options)  }}.id == {{ str_singular($options)  }}Old" :value="{{ str_singular($options)  }}.id" selected="selected">
                    {{ '&#123;&#123;' . str_singular($options) .'.name &#125;&#125;' }}
                </option>
                <option v-else :value="{{ str_singular($options) }}.id">
                    {{ '&#123;&#123;' . str_singular($options) .'.name &#125;&#125;' }}
                </option>
            </template>
        </select>
    </div>
</div>