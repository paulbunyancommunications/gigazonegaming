<li>{{ Form::open(array('id' => "toForm".$team["id"], 'action' => array('Backend\Manage\PlayersController@filter'), 'class' => "toForms")) }}
    <input name="_method" type="hidden" value="POST">
    <input name="team_sort" type="hidden" value="{{$team["id"]}}">
    <?php if(!isset($team['max_players'])) {$team['max_players'] = 0;} ?>
    {!!
        Form::submit(
            $team["name"],
            array('class'=>'teamName btn btn-default list')
        )
    !!}
    <div class="btn disabled fa
                            @if(!isset($team['team_count']) or $team['team_count'] < $team['max_players']) btn-warning" style="color:black!important;"
         @else btn-primary"
    @endif >&#xf0c0;
    @if(isset($team['team_count'])){{$team['team_count']}}
    @else 0
    @endif / {{$team['max_players']}}
    </div>
    {{ Form::close() }}

    &nbsp;&nbsp;
    {{ Html::linkAction('Backend\Manage\TeamsController@edit', '', array('team_id'=>$team["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o','title'=>'Edit')) }}
    &nbsp;&nbsp;
    {{ Form::open(array('id' => "teamFormS".$team["id"], 'action' => array('Backend\Manage\TeamsController@destroy_soft', $team["id"]), 'class' => "deletingForms")) }}
    <input name="_method" type="hidden" value="DELETE">
    {!!
        Form::submit(
            '&#xf014;',
            array('class'=>'btn btn-danger list fa fa-times', 'title'=>'Delete Team and move players to Individual Players list')
        )
    !!}
    {{ Form::close() }}
    {{ Form::open(array('id' => "teamFormH".$team["id"], 'action' => array('Backend\Manage\TeamsController@destroy_hard', $team["id"]), 'class' => "deletingForms")) }}
    <input name="_method" type="hidden" value="DELETE">
    {!!
        Form::submit(
            '&#xf00d; Team & Players',
            array('class'=>'btn btn-danger list fa fa-times2', 'title'=>"There is no coming back if you delete the whole team from the db!")
        )
    !!}
    {{ Form::close() }}
</li>