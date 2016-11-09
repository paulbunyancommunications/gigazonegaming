@if ($searchTerm)
    <div class="col-md-12"><div class="well well-dark alert" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle" aria-hidden="true"></i>
            </button>
    <h2>Searching for &#8220;{{$searchTerm}}&#8221;</h2>
    @foreach($search as $key => $value)
        <div class="col-md-{{ floor(12 / count($search))  }}">
        <h3>{{ $key }} {!! is_array($value) ? "<small class='uppercase'>(" . count($value) . " result". (count($value) > 1 || count($value) === 0 ? 's' : null) .")</small>" : null !!}</h3>
        @if (is_array($value) && count(is_array($value)))
            <ul>
                @foreach($value as $column => $columnValue)
                    @if ($key == 'Game')
                        <li>{{ Html::linkAction('Backend\Manage\GamesController@edit', $columnValue->title, array('id'=>$columnValue->id), array('id'=>'search-to-edit-'. strtolower($key) .'-'.$columnValue->id, 'class' => 'btn btn-link btn-wrap text-left', 'title'=>$key." ".$columnValue->name)) }}</li>
                    @elseif ($key == 'Team')
                        <li>{{ Html::linkAction('Backend\Manage\TeamsController@edit', $columnValue->name, array('id'=>$columnValue->id), array('id'=>'search-to-edit-'. strtolower($key) .'-'.$columnValue->id, 'class' => 'btn btn-link btn-wrap text-left', 'title'=>$key." ".$columnValue->name)) }}</li>
                    @elseif ($key == 'Tournament')
                        <li>{{ Html::linkAction('Backend\Manage\TournamentsController@edit', $columnValue->name, array('id'=>$columnValue->id), array('id'=>'search-to-edit-'. strtolower($key) .'-'.$columnValue->id, 'class' => 'btn btn-link btn-wrap text-left', 'title'=>$key." ".$columnValue->name)) }}</li>
                    @elseif ($key == 'Player')
                        <li>{{ Html::linkAction('Backend\Manage\PlayersController@edit', ($columnValue->name ? $columnValue->name : $columnValue->username), array('id'=>$columnValue->id), array('id'=>'search-to-edit-'. strtolower($key) .'-'.$columnValue->id, 'class' => 'btn btn-link btn-wrap text-left', 'title'=>$key." ".$columnValue->name)) }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
        </div>
    @endforeach
    <div class="clearfix"></div>
</div></div>
@endif