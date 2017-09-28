/**
 * Created by Roman on 6/8/17.
 */
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams(){
    $("#Team,#Team-1").children("option").each(function() {
        const id = $("#Tournament").find("option:selected").val();
        if($(this).attr("id") === id){
            $(this).show();
        }else{
            $(this).hide();
            $("#Team").val("default");
            $("#Color").val("default");
            $("#Team-1").val("default");
            $("#Color-1").val("default");
            if($(this).val() === "default"){
                $("#defaultTeam").attr("selected","selected");
                $("#defaultColor").attr("selected","selected");
                $("#defaultTeam-1").attr("selected","selected");
                $("#defaultColor-1").attr("selected","selected");
            }
        }
    });
}
showTeams();

/* These functions make sure there is a valid selection in all of the dropdowns before the submit button is displayed */
$("#Color,#Team,#Color-1,#Team-1,#Tournament").change(function() {
    if($("#Tournament").find("option:selected").text() !== "Select a Tournament" && $("#Color").find("option:selected").text() !== "Select a Color" && $("#Team").find("option:selected").text() !== "Select a Team" && $("#Color-1").find("option:selected").text() !== "Select a Color" && $("#Team-1").find("option:selected").text() !== "Select a Team") {
        $("#submit").removeClass("startButtonDisabled").prop("disabled", false);
    }
    else{
        $("#submit").addClass("startButtonDisabled").prop("disabled", true);
    }
});

const testing =  document.getElementsByName("Testing").length;

/**
 * Initialize Ajax with a token
 */
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $("#hiddenToken").text(),
        'Testing': false
    }
});

/**
* Caches Champions for team 1 and 2
*/
function submitCache(){
    $("#loader").removeClass("hidden");
    $("#submit").addClass("hidden");
    document.getElementById("info").innerHTML = "Please Wait...";

    ///Set up cache arrays for team and color
    const team = [$("#Team").find("option:selected").text(), $( "#Team-1").find("option:selected").text()];
    const color = [$("#Color").find("option:selected").text(), $("#Color-1").find("option:selected").text()];

    ///Execute cache controller with ajax
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/cache",
        data: {
            tournament: $("#Tournament").find("option:selected").text(),
            team: team,
            color: color,
        },
        success: function(data){
            //Reset Info Box
            document.getElementById("info").innerHTML = "";

            //If there is an error display it in the info box else display the results.
            if(data.ErrorCode){
                document.getElementById("info").innerHTML = data.ErrorMessage;
            }else{
                document.getElementById("info").innerHTML = "<h3 class='console-header'>UPDATED</h3><br/><span class='console-sub-header'>Team 1:</span> " + data.teamName[0] + "<br/><span class='console-sub-header'>Color:</span> " + data.colors[0] + "<br/><span class='console-sub-header'>Players Array:</span> [" + data.teamInfo[0].summonerArray + "]<br/><span class='console-sub-header'>Icons:</span> [" + data.teamInfo[0].iconArray + "]<br/><span class='console-sub-header'>Solo Ranks:</span> [" + data.teamInfo[0].soloRankArray + "]<br/><span class='console-sub-header'>Solo Win Losses:</span> [" + data.teamInfo[0].soloWinLossArray + "]<br/><span class='console-sub-header'>Flex Ranks:</span> [" + data.teamInfo[0].flexRankArray + "]<br/><span class='console-sub-header'>Flex Rank Win Losses:</span> [" + data.teamInfo[0].flexWinLossArray + "]<br/><h3 class='console-header'>UPDATED</h3><br/><span class='console-sub-header'>Team 2:</span> " + data.teamName[1] + "<br/><span class='console-sub-header'>Color:</span> " + data.colors[1] + "<br/><span class='console-sub-header'>Players Array:</span> [" + data.teamInfo[1].summonerArray + "]<br/><span class='console-sub-header'>Icons:</span> [" + data.teamInfo[1].iconArray + "]<br/><span class='console-sub-header'>Solo Ranks:</span> [" + data.teamInfo[1].soloRankArray + "]<br/><span class='console-sub-header'>Solo Win Losses:</span> [" + data.teamInfo[1].soloWinLossArray + "]<br/><span class='console-sub-header'>Flex Ranks:</span> [" + data.teamInfo[1].flexRankArray + "]<br/><span class='console-sub-header'>Flex Rank Win Losses:</span> [" + data.teamInfo[1].flexWinLossArray+"]<br/>";
            }
            console.log(data);

            //Remove loading animation and restore the submit button.
            $("#loader").addClass("hidden");
            $("#submit").removeClass("hidden");
        }
    });
}

/**
 * Loads the cache player objects and then checks to see if their champion is ready. If so it caches the champions
 */
function getChampions() {
    document.getElementById("info").innerHTML = "Please Wait...";
    const team = [$( "#Team").find("option:selected").text(), $( "#Team-1").find("option:selected").text()];
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/cacheChampions",
        success: function(data){
            console.log(data);
            if(data.ErrorCode === "true"){
                document.getElementById("info").innerHTML = data.ErrorMessage;
            }else{
                document.getElementById("info").innerHTML = data.Champions;
            }
        }

    });
}

/**
 * Clears all the cache.
*/
function clearCache(){
    document.getElementById("info").innerHTML = "Please Wait...";
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/clear",
        success: function(data){
            document.getElementById("info").innerHTML = data;
        }
    });
}