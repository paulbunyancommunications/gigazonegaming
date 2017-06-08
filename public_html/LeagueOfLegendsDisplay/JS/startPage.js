/**
 * Created by Roman on 6/8/17.
 */
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams(){
    $("#Team").children('option').each(function(i) {
        $('#submit').addClass('hidden');
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).removeClass('hidden');
            $('#Color').removeClass('hidden');
            counter++;
        }else{
            $("#Team").val('default');
            $("#Color").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam").attr('selected','selected');
                $("#defaultColor").attr('selected','selected');
            }else {
                $(this).addClass('hidden');
                $('#Color').addClass('hidden');
            }
        }
        if(counter == 0){
            $("#Team").addClass("hidden");
            $('#Color').addClass('hidden');
        }else{
            $("#Team").removeClass("hidden");
            $('#Color').removeClass('hidden');
        }
    });

}
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams2(){
    $("#Team-1").children('option').each(function(i) {
        $('#submit').addClass('hidden');
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).removeClass('hidden');
            $('#Color-1').removeClass('hidden');
            counter++;
        }else{
            $("#Color-1").val('default');
            $("#Team-1").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam-1").attr('selected','selected');
                $("#defaultColor-1").attr('selected','selected');
            }else {
                $(this).addClass('hidden');
                $('#Color-1').addClass('hidden');
            }
        }
        if(counter == 0){
            $("#Team-1").addClass("hidden");
            $('#Color-1').addClass('hidden');
        }else{
            $("#Team-1").removeClass("hidden");
            $('#Color-1').removeClass('hidden');
        }
    });

}
showTeams();
showTeams2();

/* These three blocks make sure there is a valid selection in all of the dropdowns before the submit button is displayed */
$('#Color').change(function() {
    if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('hidden');
    }
    else{
        $('#submit').addClass('hidden');
    }
});
$('#Team').change(function() {
    if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('hidden');
    }
    else{
        $('#submit').addClass('hidden');
    }
});
$('#Color-1').change(function() {
    if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('hidden');
    }
    else{
        $('#submit').addClass('hidden');
    }
});
$('#Team-1').change(function() {
    if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('hidden');
    }
    else{
        $('#submit').addClass('hidden');
    }
});
$('#Tournament').change(function() {
    if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team' && $("#Color-1 option:selected").text() !== 'Select a Color' && $("#Team-1 option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('hidden');
    }
    else{
        $('#submit').addClass('hidden');
    }
});

function teamView(){
    window.open('https://gigazonegaming.localhost/app/GameDisplay/'+$( '#Tournament option:selected').text()+'/'+$( '#Team option:selected').text()+'/'+$( '#Color option:selected').text());
}
