
@if($tournament!=[] and isset($tournament["tournament_id"]) and $tournament["tournament_id"]!='')
    <tr>
        <td class="text-left">
            {{ Html::linkAction('Backend\Manage\TournamentsController@edit', $tournament["tournament_name"], array('tournament_id'=>$tournament["tournament_id"]), array('id'=>'edit-'.str_replace(' ', '', $tournament['tournament_name']), 'class' => 'btn btn-link btn-wrap text-left', 'title'=>"Edit tournament ".$tournament['tournament_name'])) }}
        </td>
        <td>
                <p><span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x txt-color--midtone"></i>
                    <strong class="fa-stack-1x txt-color--light small">{{$tournament["max_players"]}}</strong>
                </span></p>
        </td>
        <td>

            <div class="btn-group" role="group" aria-label="Tournament Actions">
                {{
                   Form::button(
                       '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
                       array('type' => 'button', 'id' => 'submit-toForm-edit-'.$tournament["tournament_id"], 'class'=>'btn btn-primary toForm','title'=>"Edit tournament ".$tournament['tournament_name'])
                   )
               }}

                {{
                   Form::button(
                       '<i class="fa fa-print" aria-hidden="true"></i>',
                       array('type' => 'button', 'id' => 'submit-toForm-print-'.$tournament["tournament_id"], 'class'=>'btn btn-primary btn-gz toForm','title'=>"Edit tournament ".$tournament['tournament_name'])
                   )
                }}

                {{
                    Form::button(
                        '<i class="fa fa-trash-o" aria-hidden="true"></i>',
                        array('type' => 'button', 'id' => 'submit-toForm-delete-'.$tournament["tournament_id"], 'class'=>'btn btn-danger toForm','title'=>"Delete tournament ".$tournament['tournament_name'])
                    )
                }}
                {{
                    Form::button(
                        'Teams <i class="fa fa-filter" aria-hidden="true"></i>',
                        array('type' => 'button', 'id' => 'submit-toForm-filter-'.$tournament["tournament_id"], 'class'=>'btn btn-default toForm filter-'.str_replace(" ","", $tournament["tournament_name"]),'title'=>"Filter teams by tournament ".$tournament['tournament_name'])
                    )
                }}
            </div>


            {{--Submit the edit tournament link--}}
            {{ Html::linkAction('Backend\Manage\TournamentsController@edit', 'Edit', array('tournament_id'=>$tournament["tournament_id"]), array('id'=>'submit-toForm-edit-form-'.$tournament['tournament_id'], 'class' => 'btn btn-default hidden', 'title'=>"Edit tournament ".$tournament['tournament_name'])) }}

            {{--Load printable view--}}
            {{ Html::linkAction('Backend\Manage\TournamentsController@printTournament', 'Edit', array('tournament_id'=>$tournament["tournament_id"]), array('id'=>'submit-toForm-print-form-'.$tournament['tournament_id'], 'class' => 'btn btn-default hidden', 'title'=>"Print tournament details for ".$tournament['tournament_name'])) }}

            {{ Form::open(array('id' => "submit-toForm-filter-form-".$tournament["tournament_id"], 'action' => array('Backend\Manage\TeamsController@filter'), 'class' => "toForms")) }}
                <input name="_method" type="hidden" value="POST">
                <input name="tournament_sort" type="hidden" value="{{$tournament["tournament_id"]}}">
            {{ Form::close() }}

            {{ Form::open(array(
                'id' => "submit-toForm-delete-form-".$tournament["tournament_id"],
                'action' => [
                    'Backend\Manage\TournamentsController@destroy',
                    $tournament["tournament_id"]
                    ],
                'class' => "deletingForms delete_message'",
                'onsubmit'=>"return confirm('Are you sure? Deleting the tournament ". htmlentities($tournament['tournament_name']) ." will erase all teams and players relations to such tournament and teams (but not to the game)');")) }}
            <input name="_method" type="hidden" value="DELETE">
            {{ Form::close() }}


        </td>

    </tr>
@endif