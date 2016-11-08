/**
 * Created by nelson_castillo on 10/13/16.
 */

$(document).ready(function() {
    new Vue({
        el: '#brackets',
        data: {
            addOrUpdate: 'add',
            tournamentIDForm: '',
            tournamentNameForm: '',
            playerUsernameForm: '',
            playerFirstNameForm: '',
            playerLastNameForm: '',
            playerPhoneForm: '',
            playerEmailForm: '',
            playerScoreForm: '',
            playerTotal: 0,
            added: '',
            removed: '',
            updated: false,
            id_update: -1,
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
                        'username': this.playerUsernameForm,
                        'firstName': this.playerFirstNameForm,
                        'lastName': this.playerLastNameForm,
                        'phone': this.playerPhoneForm,
                        'email': this.playerEmailForm,
                        'score': this.playerScoreForm
                    });
                    this.playerUsernameForm = '';
                    this.playerFirstNameForm = '';
                    this.playerLastNameForm = '';
                    this.playerPhoneForm = '';
                    this.playerEmailForm = '';
                    this.playerScoreForm = '';
                    this.added = this.playerNameForm;
                    this.id_update = -1;
                    this.removed = '';
                    this.addOrUpdate = 'add';
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
                this.id_update = -1;
                this.removed = playerRemoved.playerName;
                this.added = '';
                this.addOrUpdate = 'add';
            },
            saveUpdatedPlayer: function(){
                if (this.id_update > -1) {
                    this.playerArray[this.id_update]['username'] = this.playerUsernameForm;
                    this.playerArray[this.id_update]['firstName'] = this.playerFirstNameForm;
                    this.playerArray[this.id_update]['lastName'] = this.playerLastNameForm;
                    this.playerArray[this.id_update]['phone'] = this.playerPhoneForm;
                    this.playerArray[this.id_update]['email'] = this.playerEmailForm;
                    this.playerArray[this.id_update]['score'] = this.playerScoreForm;
                }
                this.playerUsernameForm = '';
                this.playerFirstNameForm = '';
                this.playerLastNameForm = '';
                this.playerPhoneForm = '';
                this.playerEmailForm = '';
                this.playerScoreForm = '';
                this.removed = '';
                this.added = '';
                this.addOrUpdate = 'add';
                this.id_update = -1;
            },
            editPlayer: function(player){
                // this.delete(this.playerArray, player);
                var playerUpdate;
                var index = this.playerArray.indexOf(player);
                if (index > -1) {
                    this.id_update = index;
                    this.updated = true;
                }else{
                    this.id_update = -1;
                }
                this.playerUsernameForm = this.playerArray[index]['username'];
                this.playerFirstNameForm = this.playerArray[index]['firstName'];
                this.playerLastNameForm = this.playerArray[index]['lastName'];
                this.playerPhoneForm = this.playerArray[index]['phone'];
                this.playerEmailForm = this.playerArray[index]['email'];
                this.playerScoreForm = this.playerArray[index]['score'];
                this.added = '';
                this.removed = '';
                this.addOrUpdate = 'update';
            },
            createBracket: function(){
                alert(tournaments);
                alert(this.tournamentIDForm);
                alert(this.tournamentSelect.text);

            },
            tournamentPicked: function () {
                var encodedString = btoa(this.playerArray);
            },
            saveIntoJson: function () {
                var theCodesJson = [];

                $.ajax({
                    type: 'POST',
                    url: 'thePost/theStreetsJson.php',
                    async: false,
                    beforeSend: function (xhr) {
                        if (xhr && xhr.overrideMimeType) {
                            xhr.overrideMimeType('application/json;charset=utf-8');
                        }
                    },
                    dataType: 'json',
                    success: function (data) {
                        theStreetsJson = data;

                    }
                });
            },
            loadJson: function () {
                var theCodesJson = [];

                $.ajax({
                    type: 'GET',
                    url: 'thePost/theStreetsJson.php',
                    async: false,
                    beforeSend: function (xhr) {
                        if (xhr && xhr.overrideMimeType) {
                            xhr.overrideMimeType('application/json;charset=utf-8');
                        }
                    },
                    dataType: 'json',
                    success: function (data) {
                        theStreetsJson = data;

                    }
                });
            }


        }

    });
});