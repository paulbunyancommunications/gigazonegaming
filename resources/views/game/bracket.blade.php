@extends('game.base')
@section('css')
    li button, li span{
        font-weight:bold;
        background:none;
        border:0;
        padding:0 0 0 10px;
        margin:0;
        font-family: 'FontAwesome';
    }
    li button{
        {{--float:right;--}}
    }
    li button.red{
        color:red;
    }
    li button.green{
        color:green;
    }
    li button.blue{
        color:blue;
    }
@endsection
@section('content')
    <div class="containter">
        <div id="brackets">
            <div id="addPlayersDiv" class="form-horizontal">
                <div class="form-group">
                    <label for="tournamentIDForm" class="control-label col-xs-3">Filter by Tournament: </label>
                    <div class="col-xs-6">
                        <select id="tournamentIDForm" v-el:tournamentSelect v-model="tournamentIDForm" v-on:change="tournamentPicked" class="form-control">
                            <option>---</option>
                            @foreach($tournaments as $g)
                                <option id="tournament_{{$g['id']}}" value="{{$g['id']}}">{{$g['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button  v-on:click="createBracket" class="btn btn-primary col-xs-3" >Create Board</button>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <input type="text" id="playerFirstNameForm" v-model="playerFirstNameForm" class="form-control fa"
                               placeholder="First Name"/>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" id="playerLastNameForm" v-model="playerLastNameForm" class="form-control fa"
                               placeholder="Last Name"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <input type="text" id="playerUsernameForm" v-model="playerUsernameForm" class="form-control fa"
                               placeholder="&#xf2be Username"/>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" id="playerPhoneForm" v-model="playerPhoneForm" class="form-control fa"
                               placeholder="&#xf095 Phone"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <input type="email" id="playerEmailForm" v-model="playerEmailForm" class="form-control fa"
                               placeholder="&#xf003 Email"/>
                    </div>
                    <div class="col-xs-6">
                        <input type="number" id="playerScoreForm" v-model="playerScoreForm" class="form-control fa"
                               placeholder="&#xf11b Score"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6 col-xs-offset-3">
                        <button v-if="addOrUpdate=='add'" v-on:click="addPlayer" class="btn btn-primary form-control">
                            Add
                            Player
                        </button>
                        <button v-if="addOrUpdate=='update'" v-on:click="saveUpdatedPlayer"
                                class="btn btn-primary form-control"> Update Player
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="tournamentIDForm!=''">Players Registered in @{{ tournamentNameForm }}: @{{ playerTotal }}</div>
            <div v-if="removed!=''">Last Player Removed: @{{ removed }}</div>
            <div v-if="added!=''">Last Player Added: @{{ added }}</div>
            <hr />
            <ul class="list-group" v-if="playerArray!=[]" >
                <li v-for="playerX in playerArray" class='list-group-item'> <span>&#xf259 &#xf2bb</span> @{{ playerX.firstName }} @{{ playerX.lastName }} [<span>&#xf0f0</span> @{{ playerX.username }} ] <button class="" disabled="disabled">&#xf003:@{{ playerX.email }}</button> <button class="" disabled="disabled">&#xf095: @{{ playerX.phone }}</button> <button class="green" v-on:click="editPlayer(playerX)">&#xf044</button> <button class="red" v-on:click="removePlayer(playerX)">&#10007</button> <button class="blue" disabled="disabled">&#xf11b: @{{ playerX.score }}</button></li>
            </ul>
        </div>
    </div>
@endsection
@section('js')
    var tournaments = [{ value: -1, text: "---"}, @foreach($tournaments as $k => $t){ value: {{$t['id']}}, text:'{{$t['name']}}' },@endforeach];
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/bracketApp.js"></script>
@endsection