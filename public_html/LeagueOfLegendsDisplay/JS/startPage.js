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
    window.open('https://gigazonegaming.localhost/app/GameDisplay/'+$( '#Tournament option:selected').text()+'/'+$( '#Team option:selected').text()+'/'+$( '#Color option:selected').text());
}

function loadingAnimation($Bool) {

    document.getElementById('Info').innerHTML = "Loading.";
    document.getElementById('Info').innerHTML = "Loading..";
    document.getElementById('Info').innerHTML = "Loading...";

}

function submitCache(){

    $('#loader').removeClass('hidden');
    $('#submit').addClass('hidden');

    ///Set up cache arrays for team and color
    var team = [$( '#Team option:selected').text(), $( '#Team-1 option:selected').text()];
    var color = [$( '#Color option:selected').text(), $( '#Color-1 option:selected').text()];

    ///Execute cache controller with ajax
    $.ajax({
        method: "GET",
        type: "GET",
        url: "https://gigazonegaming.localhost/app/GameDisplay/cache",
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
                document.getElementById('info').innerHTML = "UPDATED \nTeam 1: " + data.teamName[0] + "\nColor: " + data.colors[0] + " Players Array: " + data.teamInfo[0].summonerArray + " Icons: " + data.teamInfo[0].iconArray + " Solo Ranks: " + data.teamInfo[0].soloRankArray + " Solo Win Losses: " + data.teamInfo[0].summonerArray + " Flex Ranks: " + data.teamInfo[0].flexRankArray + " Flex Rank Win Losses: " + data.teamInfo[0].flexWinLossArray + "UPDATED \nTeam 2: " + data.teamName[1] + "\nColor: " + data.colors[1] + " Players Array: " + data.teamInfo[1].summonerArray + " Icons: " + data.teamInfo[1].iconArray + " Solo Ranks: " + data.teamInfo[1].soloRankArray + " Solo Win Losses: " + data.teamInfo[1].summonerArray + " Flex Ranks: " + data.teamInfo[1].flexRankArray + " Flex Rank Win Losses: " + data.teamInfo[1].flexWinLossArray;

            }

            console.log(data);
            //Alert Data that has been updated in the cache
            $('#loader').addClass('hidden');
            $('#submit').removeClass('hidden');
        }

    });
}
