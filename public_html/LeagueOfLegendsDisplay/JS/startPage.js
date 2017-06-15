/**
 * Created by Roman on 6/8/17.
 */
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams(){
    $("#Team").children('option').each(function(i) {
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).show();
        }else{
            $(this).hide();
            $("#Team").val('default');
            $("#Color").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam").attr('selected','selected');
                $("#defaultColor").attr('selected','selected');
            }
        }
    });

}
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams2(){
    $("#Team-1").children('option').each(function(i) {
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).show();
        }else{
            $(this).hide();
            $("#Team-1").val('default');
            $("#Color-1").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam-1").attr('selected','selected');
                $("#defaultColor-1").attr('selected','selected');
            }
        }
    });

}
showTeams();
showTeams2();

/* These three blocks make sure there is a valid selection in all of the dropdowns before the submit button is displayed */
$('#Color').change(function() {
    if($("#Tournament option:selected").text() !== 'Select a Tournament' && $("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('startButtonDisabled').prop('disabled', false);
    }
    else{
        $('#submit').addClass('startButtonDisabled').prop('disabled', true);
    }
});
$('#Team').change(function() {
    if($("#Tournament option:selected").text() !== 'Select a Tournament' && $("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('startButtonDisabled').prop('disabled', false);
    }
    else{
        $('#submit').addClass('startButtonDisabled').prop('disabled', true);
    }
});
$('#Color-1').change(function() {
    if($("#Tournament option:selected").text() !== 'Select a Tournament' && $("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('startButtonDisabled').prop('disabled', false);
    }
    else{
        $('#submit').addClass('startButtonDisabled').prop('disabled', true);
    }
});
$('#Team-1').change(function() {
    if($("#Tournament option:selected").text() !== 'Select a Tournament' && $("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('startButtonDisabled').prop('disabled', false);
    }
    else{
        $('#submit').addClass('startButtonDisabled').prop('disabled', true);
    }
});
$('#Tournament').change(function() {
    if($("#Tournament option:selected").text() !== 'Select a Tournament' && $("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('startButtonDisabled').prop('disabled', false);
    }
    else{
        $('#submit').addClass('startButtonDisabled').prop('disabled', true);
    }
});

function teamView(){
    window.open('/app/GameDisplay/'+$( '#Tournament option:selected').text()+'/'+$( '#Team option:selected').text()+'/'+$( '#Color option:selected').text());
}

function submitCache(){

    $('#loader').removeClass('hidden');
    $('#submit').addClass('hidden');
    document.getElementById('info').innerHTML = 'Please Wait...';
    ///Set up cache arrays for team and color
    var team = [$( '#Team option:selected').text(), $( '#Team-1 option:selected').text()];
    var color = [$('#Color option:selected').text(), $('#Color-1 option:selected').text()];
    ///Execute cache controller with ajax
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/cache",
        data: {
            '_token': "{{ csrf_token() }}",
            tournament: $('#Tournament option:selected').text(),
            team: team,
            color: color
        },
        success: function(data){
            document.getElementById('info').innerHTML = "";
            if(data.ErrorCode){
                document.getElementById('info').innerHTML = data.ErrorMessage;
            }else{
                document.getElementById('info').innerHTML = "<h3 class='console-header'>UPDATED</h3><br/><span class='console-sub-header'>Team 1:</span> " + data.teamName[0] + "<br/><span class='console-sub-header'>Color:</span> " + data.colors[0] + "<br/><span class='console-sub-header'>Players Array:</span> [" + data.teamInfo[0].summonerArray + "]<br/><span class='console-sub-header'>Icons:</span> [" + data.teamInfo[0].iconArray + "]<br/><span class='console-sub-header'>Solo Ranks:</span> [" + data.teamInfo[0].soloRankArray + "]<br/><span class='console-sub-header'>Solo Win Losses:</span> [" + data.teamInfo[0].summonerArray + "]<br/><span class='console-sub-header'>Flex Ranks:</span> [" + data.teamInfo[0].flexRankArray + "]<br/><span class='console-sub-header'>Flex Rank Win Losses:</span> [" + data.teamInfo[0].flexWinLossArray + "]<br/><h3 class='console-header'>UPDATED</h3><br/><span class='console-sub-header'>Team 2:</span> " + data.teamName[1] + "<br/><span class='console-sub-header'>Color:</span> " + data.colors[1] + "<br/><span class='console-sub-header'>Players Array:</span> [" + data.teamInfo[1].summonerArray + "]<br/><span class='console-sub-header'>Icons:</span> [" + data.teamInfo[1].iconArray + "]<br/><span class='console-sub-header'>Solo Ranks:</span> [" + data.teamInfo[1].soloRankArray + "]<br/><span class='console-sub-header'>Solo Win Losses:</span> [" + data.teamInfo[1].summonerArray + "]<br/><span class='console-sub-header'>Flex Ranks:</span> [" + data.teamInfo[1].flexRankArray + "]<br/><span class='console-sub-header'>Flex Rank Win Losses:</span> [" + data.teamInfo[1].flexWinLossArray+"]<br/>";
            }
            console.log(data);
            //Alert Data that has been updated in the cache
            $('#loader').addClass('hidden');
            $('#submit').removeClass('hidden');
        }

    });
}
function GetChampions() {
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/cache",
        data: {
            '_token': "{{ csrf_token() }}",
            tournament: $('#Tournament option:selected').text(),
            team: team,
            color: color
        },success: function(data){

        }

    });
}
function clearCache(){
    document.getElementById('info').innerHTML = 'Please Wait...';
    ///Set up cache arrays for team and color
    ///Execute cache controller with ajax
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/clear",
        success: function(data){
            document.getElementById('info').innerHTML = data;
        }

    });
}