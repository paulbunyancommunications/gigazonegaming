@extends('game.base')
@section('css')
    li button{
        font-weight:bold;
        background:none;
        border:0;
        padding:0;
        margin:0;
        color:red;
        font-family: 'FontAwesome';
        float:right;
    }
@endsection
@section('content')
    <div class="containter">
        <div id="brackets">
            <div id="addPlayersDiv">
                <div>
                    <input type="text" id="playerName" v-model="playerNameForm" />
                    <button  v-on:click="addPlayer" class="btn btn-primary" >Add Player</button>
                    <button  v-on:click="createBracket" class="btn btn-primary" >Create bracket</button>
                </div>
            </div>
            <div>Players Registered: @{{ playerTotal }}</div>
            <hr />
            <ul class="list-group" v-if="playerArray!=[]" >
                <li v-for="playerX in playerArray" class='list-group-item'>@{{ playerX.playerName }}<button  v-on:click="removePlayer">&#10007</button></li>
            </ul>
        </div>
    </div>
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/bracketApp.js"></script>
@endsection