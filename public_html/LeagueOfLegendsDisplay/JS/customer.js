/**
 * Created by Roman on 6/14/17.
 */

$(document).ready(GetTeamNames());

function GetTeamNames(){
    document.getElementById("info").innerHTML="Getting Team Names...";
    setInterval(function(){
        ///Execute cache controller with ajax
        $.ajax({
            method: "GET",
            type: "GET",
            url: "/app/GameDisplay/getTeamName",
            success: function(data){
                if(data){
                    color1 = [];
                    color2 = [];
                    console.log(data);
                    color_1 = data[2].split(";");
                    color_2 = data[3].split(";");
                    for(i=0;i<4;i++) {
                        color1.push(color_1[i].split(":"));
                        color2.push(color_2[i].split(":"));
                    }
                    console.log(color1);
                    document.getElementById('buttonDiv').innerHTML= '<button class="teamButton" id="team1" style="'+color1[0][0]+':'+color1[0][1]+';'+color1[1][0]+':'+color1[1][1]+';'+color1[3][0]+':'+color1[3][1]+';'+'" onclick=window.open("/app/GameDisplay/team1")>'+data[0]+'</button><br/><button class="teamButton" id="team2" style="'+color2[0][0]+':'+color2[0][1]+';'+color2[1][0]+':'+color2[1][1]+';'+color2[3][0]+':'+color2[3][1]+';'+'" onclick=window.open("/app/GameDisplay/team2")>'+data[1]+'</button>';

                    $('#info').addClass("hidden");

                }
                else{
                    document.getElementById('buttonDiv').innerHTML= '<button id="team1" class="teamButton" onclick=window.open("/app/GameDisplay/team1")>Team 1</button><br/><button id ="team2" class="teamButton" onclick=window.open("/app/GameDisplay/team2")>Team 2</button>';
                    $('#info').removeClass("hidden");
                }
            }

        })
    }, 5000);
}