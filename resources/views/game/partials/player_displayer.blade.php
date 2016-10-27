
{{--*/
    $teamNum = -1;
/*--}}
@foreach($play as $id => $player)
        <li>
            <div class="playerName btn btn-default list disabled" >@if(isset($player["username"]) and $player["username"]!=''){{$player["username"]}}@else <span class="bg-danger">no username registered</span> @endif<br />@if(isset($player["name"]) and $player["name"]!=''){{$player["name"]}}@else <span class="bg-danger">no name registered</span> @endif<br />@if(isset($player["email"]) and $player["email"]!=''){{$player["email"]}}@else <span class="bg-danger">no email registered</span> @endif<br />@if(isset($player["phone"]) and $player["phone"]!=''){{$player["phone"]}}@else <span class="bg-danger">no phone registered</span> @endif<br /></div>
                &nbsp;&nbsp;
                {{ Html::linkAction('Backend\Manage\PlayersController@edit', '', array('id'=>$player["id"]), array('class' => 'btn btn-success list fa fa-pencil-square-o', 'title'=>'Edit')) }}
                &nbsp;&nbsp;
                &nbsp;
                {{ Form::open(array('id' => "playerForm".$player["id"], 'action' => array('Backend\Manage\PlayersController@destroy', $player["id"]), 'class' => "deletingForms")) }}
                    <input name="_method" type="hidden" value="DELETE">
                {!!
                    Form::submit(
                        '&#xf014; &#xf1c0;',
                        array('class'=>'btn btn-danger list fa delete_message', 'title'=>"Delete From Database Permanently!!!")
                    )
                !!}
                {{ Form::close() }}
                </li>
@endforeach