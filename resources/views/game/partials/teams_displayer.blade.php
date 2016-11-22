@if($team!=[] and isset($team["team_id"]) and $team["team_id"]!='')
    <tr>
        <td class="text-left">
            {{ Html::linkAction('Backend\Manage\TeamsController@edit', $team["team_name"], array('team_id'=>$team["team_id"]), array('id'=>'edit-'.str_replace(' ', '', $team['team_name']), 'class' => 'btn btn-link btn-wrap text-left', 'title'=>"Edit team ".$team['team_name'])) }}
        </td>
        <td>
            <p class="text-center"><span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x txt-color--midtone"></i>
                    <strong class="fa-stack-1x txt-color--light small">{{$team["team_count"]}}/{{$team["team_max_players"]}}</strong>
                </span></p>
        </td>
        <td>

            <div class="btn-group" role="group" aria-label="Team Actions">
                {{
                   Form::button(
                       '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
                       array('type' => 'button', 'id' => 'submit-toForm-edit-'.$team["team_id"], 'class'=>'btn btn-primary toForm','title'=>"Edit team ".$team['team_name'])
                   )
               }}

                {{
                   Form::button(
                       '<i class="fa fa-print" aria-hidden="true"></i>',
                       array('type' => 'button', 'id' => 'submit-toForm-print-'.$team["team_id"], 'class'=>'btn btn-primary btn-gz toForm print-'.str_replace(' ', '', $team['team_name']),'title'=>"Edit team ".$team['team_name'])
                   )
                }}
                {{
                    Form::button(
                        '<i class="fa fa-trash-o" aria-hidden="true"></i>',
                        array('type' => 'button', 'id' => 'submit-toForm-delete_soft-'.$team["team_id"], 'class'=>'btn btn-danger toForm delete_soft-form-'.str_replace(' ', '', $team["team_name"]),'title'=>"Delete team ".$team['team_name'])
                    )
                }}
                {{
                    Form::button(
                        '<i class="fa fa-address-card" aria-hidden="true"></i>',
                        array('type' => 'button', 'id' => 'submit-toForm-delete_hard-'.$team["team_id"], 'class'=>'btn btn-danger toForm delete_hard-form-'.str_replace(' ', '', $team["team_name"]),'title'=>"Delete team ".$team['team_name'])
                    )
                }}
                {{
                    Form::button(
                        'Players <i class="fa fa-filter" aria-hidden="true"></i>',
                        array('type' => 'button', 'id' => 'submit-toForm-filter-'.$team["team_id"], 'class'=>'btn btn-default toForm filter-'.str_replace(" ","", $team["team_name"]),'title'=>"Filter teams by team ".$team['team_name'])
                    )
                }}
            </div>


            {{--Submit the edit team link--}}
            {{ Html::linkAction('Backend\Manage\TeamsController@edit', 'Edit', array('team_id'=>$team["team_id"]), array('id'=>'submit-toForm-edit-form-'.$team['team_id'], 'class' => 'btn btn-default hidden', 'title'=>"Edit team ".$team['team_name'])) }}

            {{--Load printable view--}}
            {{ Html::linkAction('Api\Championship\PrintingController@printTeam', 'Edit', array('team_id'=>$team["team_id"]), array('id'=>'submit-toForm-print-form-'.$team['team_id'], 'class' => 'btn btn-default hidden', 'title'=>"Print team details for ".$team['team_name'])) }}

            {{ Form::open(array('id' => "submit-toForm-filter-form-".$team["team_id"], 'action' => array('Backend\Manage\PlayersController@filter'), 'class' => "toForms")) }}
            <input name="_method" type="hidden" value="POST">
            <input name="team_sort" type="hidden" value="{{$team["team_id"]}}">
            {{ Form::close() }}

            {{ Form::open(array(
                'id' => "submit-toForm-delete-form-".$team["team_id"],
                'action' => [
                    'Backend\Manage\TeamsController@destroy_soft',
                    $team["team_id"]
                    ],
                'class' => "deletingForms delete_message",
                'onsubmit'=>new Illuminate\Support\HtmlString("return confirm('Are you sure? Deleting the team ". htmlentities($team['team_name']) ." will erase the team and players relations to such team');"))) }}
            <input name="_method" type="hidden" value="DELETE">
            {{ Form::close() }}
            {{ Form::open(array(
                'id' => "submit-toForm-delete_hard-form-".$team["team_id"],
                'action' => [
                    'Backend\Manage\TeamsController@destroy_hard',
                    $team["team_id"]
                    ],
                'class' => "deletingForms delete_message delete_hard-form-".str_replace(' ', '', $team["team_name"])."-hidden",
                'onsubmit'=>new Illuminate\Support\HtmlString("return confirm('Are you sure? Deleting the team ". htmlentities($team['team_name']) ." will erase the team and players and their relations to the games, tournaments associated to them');"))) }}
            <input name="_method" type="hidden" value="DELETE">
            {{ Form::close() }}
            {{ Form::open(array(
                'id' => "submit-toForm-delete_soft-form-".$team["team_id"],
                'action' => [
                    'Backend\Manage\TeamsController@destroy_soft',
                    $team["team_id"]
                    ],
                'class' => "deletingForms delete_message delete_soft-form-".str_replace(' ', '', $team["team_name"])."-hidden",
                'onsubmit'=>new Illuminate\Support\HtmlString("return confirm('Are you sure? Deleting the team ". htmlentities($team['team_name']) ." will erase the team and players and their relations to the games, tournaments associated to them');"))) }}
            <input name="_method" type="hidden" value="DELETE">
            {{ Form::close() }}


        </td>

    </tr>
@endif