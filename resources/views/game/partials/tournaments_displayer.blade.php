
@if($tournament!=[] and isset($tournament["id"]) and $tournament["id"]!='')
<li>{{ Form::open(array('id' => "toForm".$tournament["id"], 'action' => array('Backend\Manage\TeamsController@filter'), 'class' => "toForms")) }}
    <input name="_method" type="hidden" value="POST">
    <input name="team_sort" type="hidden" value="{{$tournament["id"]}}">
    {!!
        Form::submit(
            $tournament["name"],
            array('class'=>'tournamentName btn btn-default list')
        )
    !!}
    {{ Form::close() }}

    <div class='btn btn-primary list fa' title='{{$tournament["max_players"]}}' disabled>{{$tournament["max_players"]}}</div>

    {{ Html::linkAction('Backend\Manage\TournamentsController@edit', '', array('tournament_id'=>$tournament["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o', 'title'=>"Edit")) }}
    &nbsp;&nbsp;
    {{ Form::open(array('id' => "tournamentForm".$tournament["id"], 'action' => array('Backend\Manage\TournamentsController@destroy', $tournament["id"]), 'class' => "deletingForms")) }}
    <input name="_method" type="hidden" value="DELETE">
    {!!
        Form::submit(
            '&#xf014; &#xf1c0;',
            array('class'=>'btn btn-danger list fa fa-times', 'title'=>"Delete From Database")
        )
    !!}
    {{ Form::close() }}
</li>
@endif