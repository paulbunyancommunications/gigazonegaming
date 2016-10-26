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
                    <input type="text" id="tournamentNameForm" v-model="tournamentNameForm" placeholder="Tournament Name" />
                    <button  v-on:click="createBracket" class="btn btn-primary" >Create bracket</button>
                </div>
                <div>
                    <input type="text" id="playerUsernameForm" v-model="playerUsernameForm" placeholder="Username" />
                    <input type="text" id="playerFirstNameForm" v-model="playerFirstNameForm"  placeholder="First Name" />
                    <input type="text" id="playerLastNameForm" v-model="playerLastNameForm"  placeholder="Last Name"/>
                    <input type="text" id="playerPhoneForm" v-model="playerPhoneForm"  placeholder="Phone"/>
                    <input type="email" id="playerEmailForm" v-model="playerEmailForm"  placeholder="Email"/>
                    <input type="number" id="playerScoreForm" v-model="playerScoreForm"  placeholder="Score"/>
                    <button v-if="addOrUpdate=='add'" v-on:click="addPlayer" class="btn btn-primary" > Add Player</button>
                    <button v-if="addOrUpdate=='update'" v-on:click="saveUpdatedPlayer" class="btn btn-primary" > Update Player</button>
                </div>
            </div>
            <div v-if="tournamentNameForm!=''">Players Registered in @{{ tournamentNameForm }}: @{{ playerTotal }}</div>
            <div v-if="removed!=''">Last Player Removed: @{{ removed }}</div>
            <div v-if="added!=''">Last Player Added: @{{ added }}</div>
            <hr />
            <ul class="list-group" v-if="playerArray!=[]" >
                <li v-for="playerX in playerArray" class='list-group-item'>@{{ playerX.Username }}: @{{ playerX.FirstName }} @{{ playerX.LastName }}<button  v-on:click="removePlayer(playerX)">&#10007</button> <input type="number" :value = "playerX.Score" v-on:change="updatePlayer(playerX)"/></li>
            </ul>
            <ul class="list-group" v-if="playerArray!=[]" >
                <li v-for="playerX in playerArray" class='list-group-item'>@{{ playerX.Username }}: @{{ playerX.FirstName }} @{{ playerX.LastName }} score: @{{ playerX.Score }}</li>
            </ul>
        </div>
    </div>
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/bracketApp.js"></script>
@endsection