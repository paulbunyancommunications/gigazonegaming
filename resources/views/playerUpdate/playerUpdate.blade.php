@extends('playerUpdate/playerUpdateLayout')

@section('Name')
{{$token->name}}
@stop

@section('Username')
{{$token->username}}
@stop

@section('Email')
{{$token->email}}
@stop

@section('Phone')
{{$token->phone}}
@stop

@section('UserNames')
    @for($i=0;$i<count($user_names);$i++)
        @if(stristr($games[$i]->title,"league") !== false)
            <div class="margin-bottom">
                <label for="summonerName">SummonerName: </label>
                <input type="text" name="summonerName" id="summonerName" placeholder="SummonerName" value="{{$user_names[$i]->username}}"/>
            </div>
            @elseif(stristr($games[$i]->title,"overwatch") !== false)
            <div class="margin-bottom">
                <label for="overwatch">OverwatchName: </label>
                <input type="text" name="overwatch" id="overwatch" placeholder="OverwatchName" value="{{$user_names[$i]->username}}"/>
            </div>
        @endif
    @endfor
@stop

@section('Form')
    @if($tournaments || $games || $teams || $players)
        <div class="text-center">
            <h3>Additional Information:</h3>
        </div>
    @endif
    @if($games)
        <ul>
        @for($i=0;$i<count($games);$i++)
            <li class="list-unstyled"><h4>Game Entered:</h4></li>
            <li>{{$games[$i]->title}}</li>
            @if($tournaments)
                <li class="list-unstyled">
                    <h5>Tournament Entered:</h5>
                    <ul>
                @for($j=0;$j<count($tournaments);$j++)
                    @if($tournaments[$j]->game_id === $games[$i]->id)
                        <li>{{$tournaments[$j]->name}}</li>
                        @if($teams)
                            <li class="list-unstyled">
                                <h6>Team Entered:</h6>
                                <ul>
                                @if($teams[$j]->tournament_id === $tournaments[$j]->id)
                                    <li>{{$teams[$j]->name}}</li>
                                        @if($players)
                                            <li class="list-unstyled">
                                                <h6>Players On Team:</h6>
                                                <ul>
                                                    @for($k=0;$k<count($players[$j]);$k++)
                                                        <li>Username: {{$players[$j][$k]->username}}, Email: {{$players[$j][$k]->email}}</li>
                                                    @endfor
                                                </ul>
                                            </li>
                                        @endif
                                @endif
                                </ul>
                            </li>
                        @endif
                    @endif
                @endfor
                    </ul>
                </li>
            @endif
        @endfor
        </ul>
    @endif
@stop