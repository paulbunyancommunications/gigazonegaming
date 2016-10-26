/**
 * Created by nelson_castillo on 10/13/16.
 */

$(document).ready(function() {
    new Vue({
        el: '#brackets',

        data: {
            addOrUpdate: 'add',
            tournamentNameForm: 'Tournament Name',
            playerUsernameForm: '',
            playerFirstNameForm: '',
            playerLastNameForm: '',
            playerPhoneForm: '',
            playerEmailForm: '',
            playerScoreForm: '',
            playerTotal: 0,
            added: '',
            removed: '',
            playerOrderGeneral:0,
            playerNameForm: '',
            playerArray:[
                // {
                // Username: this.playerUsernameForm,
                // FirstName: this.playerFirstNameForm,
                // LastName: this.playerLastNameForm,
                // Phone: this.playerPhoneForm,
                // Email: this.playerEmailForm,
                // Score: this.playerScoreForm
                // }
            ]
        },
        methods: {
            addPlayer: function(){
                if(this.playerFirstNameForm !='' && this.playerLastNameForm !='' && this.playerUsernameForm !='') {
                    this.playerOrderGeneral += 1;
                    this.playerTotal += 1;
                    this.playerArray.push({
                        Username: this.playerUsernameForm,
                        FirstName: this.playerFirstNameForm,
                        LastName: this.playerLastNameForm,
                        Phone: this.playerPhoneForm,
                        Email: this.playerEmailForm,
                        Score: this.playerScoreForm
                    });
                    this.playerUsernameForm = '';
                    this.playerFirstNameForm = '';
                    this.playerLastNameForm = '';
                    this.playerPhoneForm = '';
                    this.playerEmailForm = '';
                    this.playerScoreForm = '';
                    this.removed = '';
                    this.added = this.playerNameForm;
                }
            },
            removePlayer: function(player){
                // this.delete(this.playerArray, player);
                var playerRemoved;
                var index = this.playerArray.indexOf(player);
                if (index > -1) {
                    playerRemoved = this.playerArray.splice(index, 1);
                }
                this.playerTotal -= 1;
                this.removed = playerRemoved.playerName;
                this.added = '';
            },
            updatePlayer: function(player){
                // this.delete(this.playerArray, player);
                var playerUpdate;
                var index = this.playerArray.indexOf(player);
                if (index > -1) {
                    this.playerArray[index]['score'] = this.playerScoreForm;
                }
                this.removed = '';
                this.added = '';
                this.addOrUpdate = 'update'
                this.playerUsernameForm = '';
                this.playerFirstNameForm = '';
                this.playerLastNameForm = '';
                this.playerPhoneForm = '';
                this.playerEmailForm = '';
                this.playerScoreForm = '';
                this.removed = '';
                this.added = '';
            },
            updatePlayer: function(player){
                // this.delete(this.playerArray, player);
                var playerUpdate;
                var index = this.playerArray.indexOf(player);
                if (index > -1) {
                    this.playerScoreForm = this.playerArray[index]['score'];
                }
                alert(this.playerArray[index]);
                this.removed = '';
                this.added = '';
                this.addOrUpdate = 'add';
                this.playerUsernameForm = '';
                this.playerFirstNameForm = '';
                this.playerLastNameForm = '';
                this.playerPhoneForm = '';
                this.playerEmailForm = '';
                this.playerScoreForm = '';
                this.removed = '';
                this.added = '';
            },
            createBracket: function(){
                for(var i=0; this.playerArray.length > i; i++){

                }
            }
        }

    });
});