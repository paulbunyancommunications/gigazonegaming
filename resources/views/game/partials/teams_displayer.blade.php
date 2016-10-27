<li>{{ Form::open(array('id' => "toForm".$team["team_id"], 'action' => array('Backend\Manage\PlayersController@filterTeam'), 'class' => "toForms")) }}
    <input name="_method" type="hidden" value="POST">
    <input name="team_sort" type="hidden" value="{{$team["team_id"]}}">
    <?php if(!isset($team['team_max_players'])) {$team['team_max_players'] = 0;} ?>
    {!!
        Form::submit(
            $team['team_name'],
            array('class'=>'teamName btn btn-default')
        )
    !!}
    <div class="btn disabled fa
                            @if(!isset($team['team_count']) or $team['team_count'] < $team['team_max_players']) btn-warning" style="color:black!important;"
         @else btn-primary"
    @endif >&#xf0c0;
    @if(isset($team['team_count'])){{$team['team_count']}}
    @else 0
    @endif / {{$team['team_max_players']}}
    </div>
    {{ Form::close() }}

    &nbsp;&nbsp;
    {{ Html::linkAction('Backend\Manage\TeamsController@edit', '', array('team_id'=>$team["team_id"]), array('id' => "edit-".str_replace(' ', '', $team["team_name"]), 'class' => 'btn btn-success list fa fa-pencil-square-o','title'=>'Edit')) }}
    &nbsp;&nbsp;
    {{ Form::open(array('id' => "teamFormS".$team["team_id"], 'action' => array('Backend\Manage\TeamsController@destroy_soft', $team["team_id"]), 'class' => "deletingForms")) }}
    <input name="_method" type="hidden" value="DELETE">
    {!!
        Form::submit(
            '&#xf014;',
            array('id' => "delete-soft-".str_replace(' ', '', $team["team_name"]), 'class'=>'btn btn-danger list fa delete_message', 'title'=>'Delete Team and move players to Individual Players list for Team Tournament')
        )
    !!}
    {{ Form::close() }}
    {{ Form::open(array('id' => "teamFormH".$team["team_id"], 'action' => array('Backend\Manage\TeamsController@destroy_hard', $team["team_id"]), 'class' => "deletingForms")) }}
    <input name="_method" type="hidden" value="DELETE">
    {!!
        Form::submit(
            '&#xf00d; &#xf0c0; &#xf067; &#xf235;',
            array('id' => "delete-hard-".str_replace(' ', '', $team["team_name"]), 'class'=>'btn btn-danger list fa delete_message2', 'title'=>"There is no coming back if you delete the team from the db and all the players from this team from all other teams, tournaments and games!!!!!!")
        )
    !!}
    {{ Form::close() }}
</li>