/**
 * Created by Roman on 6/8/17.
 */
/* This is used to fill in the champions manually if the request cannot be fulfilled*/
function findChampion() {
    document.getElementById("info").innerHTML = "";
    var championArray = [];
    if(document.getElementById("player1").value !== "" && document.getElementById("player2").value !== "" && document.getElementById("player3").value !== ""&& document.getElementById("player4").value !== "" && document.getElementById("player5").value !== ""){
        for(var i=1; i<6; i++)
        {
            championArray.push("http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" + document.getElementById("player" + i).value + "_0.jpg");
        }
            if(championArray.length === 5) {
                document.getElementById("info").innerHTML = "Updating Champions...";
                $.ajax({
                    method: "GET",
                    type: "GET",
                    url: "/app/GameDisplay/championsOverride",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        championArray: championArray,
                        team: $("#Team").find("option:selected").text(),
                    },
                    success: function (data) {
                        document.getElementById("info").innerHTML = "<h3>" + data + "</h3>";
                    }
                });
            }
        }else{
        document.getElementById("info").innerHTML="<h3>All Fields Must Be Filled</h3>";
    }
}