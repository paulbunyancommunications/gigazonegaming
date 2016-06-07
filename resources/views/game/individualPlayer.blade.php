
@extends('game.base')

@section('css')
    .separator{
    font-weight:bold;
    font-size:2em;
    font-color:red;
    text-decoration:underline;
    }
    .username, .name{
    min-width:220px!important;
    width:220px!important;
    max-width:220px!important;
    display:inline-block;
    }
    hr{
    height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
    }
    .deletingForms, .toForms{
    display:inline-block;
    }
    .playerTeam{
    display:inline-block;
    min-width:50px;
    }
    #listOfPlayers, #listOfPlayers li, .playerName{
    display:inline-block;
    padding: 0;
    margin: 0;
    min-width:250px;
    max-width:250px;
    }
    #listOfPlayers .playerName{
    padding: 7px 10px;
    }
@endsection
@section('content')
    @if(!isset($maxNumOfPlayers)) {{--*/ $maxNumOfPlayers = 5; /*--}}@endif
    @if(isset($individualPlayers) || $individualPlayers != [])
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(isset($cont_updated) and  $cont_updated)
            <div class="alert alert-success"><strong>Success!</strong> You have updated this Player.</div>
        @endif
        {{ Form::open(array('id' => "playerForm", 'action' => array('Backend\Manage\IndividualPlayersController@change'))) }}
        <div class="form-group">
            <input name="_method" type="hidden" value="POST">
            <input name="id" id="id" type="hidden" value="">
            <div class="form-group">
                <label for="name" style="width:120px; text-align:right;">Player Name: </label> &nbsp; <input type="text" name="name" id="name"  style="width:350px" placeholder="The name of the player" disabled/>
            </div>
            <div class="form-group">
                <label for="username" style="width:120px; text-align:right;">Player Username: </label> &nbsp; <input type="text" name="username" id="username" style="width:350px" placeholder="The username of the player" disabled/>
            </div>
            <div class="form-group">
                <label for="email" style="width:120px; text-align:right;">Player Email: </label> &nbsp; <input type="text" name="email" id="email" style="width:350px" placeholder="The email of the player" disabled/>
            </div>
            <div class="form-group">
                <label for="phone" style="width:120px; text-align:right;">Player Phone: </label> &nbsp; <input type="text" name="phone" id="phone" style="width:350px" placeholder="The phone of the player" disabled/>
            </div>
            <div class="form-group">
                <label for="team_id" style="width:120px; text-align:right;">Player Team Name: </label> &nbsp;
                <input type="text" name="team_name" id="team_name" placeholder="The team of the player" style="width:350px" disabled>
                <input type="text" name="team_id" id="team_id" class="hidden" style="">
                <input type="text" name="tournament_id" id="tournament_id" class="hidden" style="">
                <input type="text" name="game_id" id="game_id" class="hidden" style="">
            </div>
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <input name="_method" type="hidden" value="POST">
            <div class="form-group">
                <label for="game_sort" style="width:120px; text-align:right;">Show options only for this Game: </label>
                <select name="game_sort" id="game_sort" style="width:350px;">

                    <option class="default"> --- </option>
                    @foreach($games as $g)
                        <option id="t_option{{$g['id']}}" value="{{$g['id']}}" class="gameSelector">{{$g['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tournament_sort" style="width:120px; text-align:right;">Filter by Tournament: </label>
                <select name="tournament_sort" id="tournament_sort" style="width:350px;" disabled>
                    <option class="default"> --- </option>
                    @foreach($tournaments as $g)
                        <option id="t_option{{$g['game_id']}}_{{$g['id']}}" value="{{$g['id']}}" >{{$g['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="team_sort" style="width:120px; text-align:right;">Filter by Team: </label>
                <select name="team_sort" id="team_sort" style="width:350px;" disabled>
                    <option class="default"> --- </option>
                    @foreach($teams as $g)
                        <option id="t_option{{$g['tournament_id']}}_{{$g['id']}}" value="{{$g['id']}}" >{{$g['name']}} p#:{{$g['team_count']}}/{{$maxNumOfPlayers}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="form-group">
                    {!! Form::submit( 'Save', array('id'=>'submit_button','class'=>'btn btn-default', 'style'=>'width:350px; text-align:center;', 'disabled'=>"disabled")) !!}
                    {{ Html::link('/manage/individualPlayer/', 'Clear', array('id' => 'reset', 'class' => 'btn btn-danger'))}}
                </div>
            </div>
            {{ Form::close() }}
            {{--*/
                $teamNum = -1;
            /*--}}
            <br />
            <div class="form-group">
                <ul id="listOfPlayers" class="listing">
                        @foreach($individualPlayers as $id => $player)
                            <li>
                                <button class="btn btn-default aPlayer playerName disabled list"
                                     game_id_j="t_option{{$player["game_id"]}}"
                                     game_id="{{$player["game_id"]}}"
                                     player_id="{{$player["id"]}}"
                                     player_name="{{$player["name"]}}"
                                     player_user="{{$player["username"]}}"
                                     player_phone="{{$player["phone"]}}"
                                     player_email="{{$player["email"]}}"
                                >
                                    {{$player["username"]}}
                                </button>
                            </li>
                        @endforeach
                </ul>
            </div>
        </div>

    @else
        <h1>Sorry, no individual players where found on the database!, please create a player before proceding with a player</h1>
        {{ Html::link('/manage/player/', 'Create a Player', array('id' => 'new_player', 'class' => 'btn btn-default'))}}
    @endif

@endsection
@section('js')
    $(document).ready(function() {
        $('#game_sort').on("change", function() {
            $('#tournament_sort option').removeAttr("disabled");
            var val_g = $('#game_sort option:selected').val();
            $("#game_id").val(val_g);
            var d_id = $('#game_sort option[value="'+val_g+'"]').attr("id");
            $('#tournament_sort').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
            $('#tournament_sort option').prop("disabled", true);
            $('#tournament_sort option[id^="'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
            $('#tournament_sort option.default').attr("selected","selected");
            $('#tournament_sort').select2({
                allowClear: true
            });
        console.log(d_id);
            $('.aPlayer').addClass("disabled");
            $('.aPlayer[game_id_j="'+d_id+'"]').removeClass("disabled");
            $("#name").val("");
            $("#username").val("");
            $("#email").val("");
            $("#phone").val("");

    });
        $('#tournament_sort').on("change", function() {
            var val_g = $('#tournament_sort option:selected').val();
            $("#tournament_id").val(val_g);
            var d_id = $('#tournament_sort option[value="'+val_g+'"]').attr("id");
            d_id = d_id.split('_')[2];
            $('#team_sort').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
            $('#team_sort option').prop("disabled", true);
            $('#team_sort option[id^="t_option'+d_id+'_"]').prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
            $('#team_sort option[id^="t_option'+d_id+'_"]:first-child').attr("selected","selected");
            $('#team_sort').select2({
                allowClear: true
            });
        });
        $('#team_sort').on("change", function() {
            var val_g = $('#team_sort option:selected').val();
            var name_g = $('#team_sort option:selected').text();
            $("#team_name").val(name_g);
            $("#team_id").val(val_g);
            $("#submit_button").prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled");
        });
        $('.aPlayer').click(function(){
            if(!$(this).hasClass("disabled")){
                if($(this).attr( "game_id" ) == $("#game_id").val()){
                    $("#name").prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled").val($(this).attr( "player_name" ));
                    $("#id").val($(this).attr( "player_id" ));
                    $("#username").prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled").val($(this).attr( "player_user" ));
                    $("#email").prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled").val($(this).attr( "player_email" ));
                    $("#phone").prop("disabled", false).attr('disabled',false).removeProp('disabled').removeAttr("disabled").val($(this).attr( "player_phone" )  );
                }else{
                    alert("The player didn't sign up for this game");
                }
            }
            return false;
        });

        $( "#target" ).submit(function( event ) {
            var ret = false;
            if($(this).attr( "game_id" ) != $("#game_id").val()){
                ret = true;
            }
            if($('#username') == ''){
                ret = true;
            }
            if($('#id') == ''){
                ret = true;
            }
            if(ret){
                event.preventDefault();
                return ret;
            }
        });
    });
@endsection