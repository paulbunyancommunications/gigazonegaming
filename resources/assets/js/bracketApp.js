/**
 * Created by nelson_castillo on 10/13/16.
 */

$(document).ready(function() {
    new Vue({
        el: '#brackets',

        data: {
            playerTotal: 0,
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
                this.playerOrderGeneral+=1;
                this.playerTotal +=1;
                this.playerArray.push({playerName:this.playerNameForm, playerOrder:this.playerOrderGeneral});
                this.playerNameForm= '';
            },
            removePlayer: function(){

            },
            createBracket: function(){

            }
        }

    });
});