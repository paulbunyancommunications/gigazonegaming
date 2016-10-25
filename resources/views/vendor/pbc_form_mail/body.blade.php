@if( isset($data['head']))
    <p>{!! $data['head'] !!}</p>
@endif
@if( ! empty($data['fields']))
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th class="text-left">Field</th>
        <th class="text-right">Value</th>
    </tr>
    </thead>
    <tbody>
            @foreach ($data['fields'] as $field)
                <tr>
                    <td class="question text-left" id="{{ ($field['field']) ? (str_slug($field['field'])) : (str_slug($field['label'])) }}-response-label">{{ $field['label'] }}</td>
                    <td class="answer text-right" id="{{ ($field['field']) ? (str_slug($field['field'])) : (str_slug($field['label'])) }}-response-value">
                        @if (is_array($field['value']))
                            <ul>
                                @for ($i=0; $i < count($field); $i++)
                                    <li>{{ $field['value'][$i] }}</li>
                                @endfor
                            </ul>
                        @else
                            {{ $field['value'] }}
                        @endif
                    </td>
                </tr>
            @endforeach
    </tbody>
</table>
@endif