{{--*/
    $teamNum = -1;
/*--}}
{{--@foreach($play as $id => $player)--}}
        {{--<li>--}}
            {{--<div class="playerName btn btn-default list disabled" >@if(isset($player["username"]) and $player["username"]!=''){{$player["username"]}}@else <span class="bg-danger">no username registered</span> @endif<br />@if(isset($player["name"]) and $player["name"]!=''){{$player["name"]}}@else <span class="bg-danger">no name registered</span> @endif<br />@if(isset($player["email"]) and $player["email"]!=''){{$player["email"]}}@else <span class="bg-danger">no email registered</span> @endif<br />@if(isset($player["phone"]) and $player["phone"]!=''){{$player["phone"]}}@else <span class="bg-danger">no phone registered</span> @endif<br /></div>--}}
                {{--{{ Html::linkAction('Backend\Manage\PlayersController@edit', '', array('id'=>$player["id"]), array('id'=>'player-edit-'.str_replace(' ', '',$player["username"]),'class' => 'btn btn-success list fa fa-pencil-square-o', 'title'=>'Edit')) }}--}}
                {{--{{ Form::open(array('id' => "playerForm".$player["id"], 'action' => array('Backend\Manage\PlayersController@destroy', $player["id"]), 'class' => "deletingForms")) }}--}}
                    {{--<input name="_method" type="hidden" value="DELETE">--}}
                {{--{!!--}}
                    {{--Form::submit(--}}
                        {{--'&#xf014; &#xf1c0;',--}}
                        {{--array('class'=>'btn btn-danger list fa delete_message', 'title'=>"Delete From Database Permanently!!!")--}}
                    {{--)--}}
                {{--!!}--}}
                {{--{{ Form::close() }}--}}
                {{--</li>--}}
{{--@endforeach--}}

@foreach($play as $id => $player)
@if($player!=[] and isset($player["id"]) and $player["id"]!='')
    <tr>
        <td class="text-left">
            {{ Html::linkAction('Backend\Manage\PlayersController@edit', $player["name"], array('id'=>$player["id"]), array('id'=>'edit-'.$player['id'], 'class' => 'btn btn-link btn-wrap text-left', 'title'=>"Edit player ".$player['name'])) }}
        </td>
        <td class="text-left">
            {{ Html::linkAction('Backend\Manage\PlayersController@edit', $player["username"], array('id'=>$player["id"]), array('id'=>'edit-'. $player['id'].'-a', 'class' => 'btn btn-link btn-wrap text-left', 'title'=>"Edit player ".$player['username'])) }}
        </td>
        <td class="text-left">
            {{ Html::linkAction('Backend\Manage\PlayersController@edit', $player["email"], array('id'=>$player["id"]), array('id'=>'edit-'. $player['id'].'-b', 'class' => 'btn btn-link btn-wrap text-left', 'title'=>"Edit player ".$player['email'])) }}
        </td>
        <td>

            <div class="btn-group" role="group" aria-label="Tournament Actions">
                {{
                   Form::button(
                       '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
                       array('type' => 'button', 'id' => 'submit-toForm-edit-'.$player["id"], 'class'=>'btn btn-primary toForm edit-form-'.str_replace(' ', '', $player['username']),'title'=>"Edit tournament ".$player['username'])
                   )
               }}

                {{
                   Form::button(
                       '<i class="fa fa-print" aria-hidden="true"></i>',
                       array('type' => 'button', 'id' => 'submit-toForm-print-'.$player["id"], 'class'=>'btn btn-primary btn-gz toForm print-'.str_replace(' ', '', $player['username']),'title'=>"Edit tournament ".$player['username'])
                   )
                }}

                {{
                    Form::button(
                        '<i class="fa fa-trash-o" aria-hidden="true"></i>',
                        array('type' => 'button', 'id' => 'submit-toForm-delete-'.$player["id"], 'class'=>'btn btn-danger toForm delete-form-'.str_replace(' ', '', $player['username']),'title'=>"Delete tournament ".$player['username'])
                    )
                }}
            </div>

            {{--Submit the edit tournament link--}}
            {{ Html::linkAction('Backend\Manage\PlayersController@edit', 'Edit', array('id'=>$player["id"]), array('id'=>'submit-toForm-edit-form-'.$player['id'], 'class' => 'btn btn-default hidden', 'title'=>"Edit tournament ".$player['username'])) }}

            {{--Load printable view--}}
            {{ Html::linkAction('Api\Championship\PrintingController@printPlayer', 'Edit', array('id'=>$player["id"]), array('id'=>'submit-toForm-print-form-'.$player['id'], 'class' => 'btn btn-default hidden', 'title'=>"Print tournament details for ".$player['username'])) }}


            {{ Form::open(array(
                'id' => "submit-toForm-delete-form-".$player["id"],
                'action' => [
                    'Backend\Manage\PlayersController@destroy',
                    $player["id"]
                    ],
                'class' => "deletingForms delete_message'",
                'onsubmit'=>"return confirm('Are you sure? Deleting the tournament ". htmlentities($player['username']) ." will erase all teams and players relations to such tournament and teams (but not to the game)');")) }}
            <input name="_method" type="hidden" value="DELETE">
            {{ Form::close() }}


        </td>

    </tr>
@endif
@endforeach