/**
 * Created by Roman on 6/8/17.
 */
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams(){
    $("#Team").children('option').each(function(i) {
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).removeClass('startButtonDisabled').prop('disabled', false);
            $('#Color').removeClass('startButtonDisabled').prop('disabled', false);
            counter++;
        }else{
            $("#Team").val('default');
            $("#Color").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam").attr('selected','selected');
                $("#defaultColor").attr('selected','selected');
            }else {
                $(this).addClass('startButtonDisabled').prop('disabled', true);
                $('#Color').addClass('startButtonDisabled').prop('disabled', true);
            }
        }
        if(counter == 0){
            $("#Team").addClass('startButtonDisabled').prop('disabled', true);
            $('#Color').addClass('startButtonDisabled').prop('disabled', true);
        }else{
            $("#Team").removeClass('startButtonDisabled').prop('disabled', false);
            $('#Color').removeClass('startButtonDisabled').prop('disabled', false);
        }
    });

}
/*This allows the display of teams and colors after a valid tournament is selected*/
function showTeams2(){
    $("#Team-1").children('option').each(function(i) {
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).removeClass('startButtonDisabled').prop('disabled', false);
            $('#Color-1').removeClass('startButtonDisabled').prop('disabled', false);
            counter++;
        }else{
            $("#Color-1").val('default');
            $("#Team-1").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam-1").attr('selected','selected');
                $("#defaultColor-1").attr('selected','selected');
            }else {
                $(this).addClass('startButtonDisabled').prop('disabled', true);
                $('#Color-1').addClass('startButtonDisabled').prop('disabled', true);
            }
        }
        if(counter == 0){
            $("#Team-1").addClass('startButtonDisabled').prop('disabled', true);
            $('#Color-1').addClass('startButtonDisabled').prop('disabled', true);
        }else{
            $("#Team-1").removeClass('startButtonDisabled').prop('disabled', false);
            $('#Color-1').removeClass('startButtonDisabled').prop('disabled', false);
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