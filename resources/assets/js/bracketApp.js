/**
 * Created by nelson_castillo on 10/13/16.
 */

$(document).ready(function() {
    new Vue({
        el: '#brackets',

        data: {
            playerTotal: 0,
            added: '',
            removed: '',
            playerOrderGeneral:0,
            playerNameForm: '',
            playerArray:[
                // {
                //     playerName: '',
                //     playerLevel: 0,
                //     playerOrder: 0
                // }
            ]
        },
        methods: {
            addPlayer: function(){
                if(this.playerNameForm !='') {
                    this.playerOrderGeneral += 1;
                    this.playerTotal += 1;
                    this.playerArray.push({playerName: this.playerNameForm, playerOrder: this.playerOrderGeneral});
                    this.playerNameForm = '';
                    this.removed = '';
                    this.added = this.playerNameForm;
                }
            },
            removePlayer: function(player){
                // this.delete(this.playerArray, player);
                var playerRemoved;
                var array = [2, 5, 9];
                var index = this.playerArray.indexOf(player);
                if (index > -1) {
                    playerRemoved = this.playerArray.splice(index, 1);
                }
alert(playerRemoved);

                // for(var i=0; i<playerArray.length; i++){
                //     if(playerArray[i] == player){
                //         first = playerArray.splice(0,i);
                //     }
                // }
                this.playerTotal -= 1;
                this.removed = playerRemoved.playerName;
                this.added = '';
            },
            createBracket: function(){

            }
        }

    });
});