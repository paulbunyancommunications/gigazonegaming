/**
 * Created by Roman on 6/14/17.
 */

$(document).ready(GetTeamNames());

function GetTeamNames(){
    document.getElementById("info").innerHTML="Getting Team Names...";
    x = setInterval(function(){
        ///Execute cache controller with ajax
        $.ajax({
            method: "GET",
            type: "GET",
            url: "/app/GameDisplay/getTeamName",
            success: function(data){
                if(data){
                    console.log(data);
                    document.getElementById('team1').innerHTML= data[0];
                    document.getElementById('team2').innerHTML= data[1];
                    clearInterval(x);
                    $('#info').addClass("hidden");
                }
            }

        })
    }, 5000);
}