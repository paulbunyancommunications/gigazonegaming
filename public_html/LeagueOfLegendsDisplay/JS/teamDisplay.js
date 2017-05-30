/**
 * Created by Roman on 5/22/17.
 * added comment
 */

function showTeams(){
    $("#Team").children('option').each(function(i) {
        $('#submit').addClass('hidden');
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).removeClass('hidden');
            //$('#submit').removeClass('hidden');
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
                //$('#submit').addClass('hidden');
                $('#Color').addClass('hidden');
            }
        }
        if(counter == 0){
            $("#Team").addClass("hidden");
            //$('#submit').addClass('hidden');
            $('#Color').addClass('hidden');
        }else{
            $("#Team").removeClass("hidden");
            // $('#submit').removeClass('hidden');
            $('#Color').removeClass('hidden');
        }
    });

}
showTeams();

    $('#Color').change(function() {
        if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team') {
            $('#submit').removeClass('hidden');
        }
        else{
            $('#submit').addClass('hidden');
        }
    });
    $('#Team').change(function() {
    if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team') {
        $('#submit').removeClass('hidden');
    }
    else{
        $('#submit').addClass('hidden');
    }
    });
    $('#Tournament').change(function() {
        if($("#Color option:selected").text() !== 'Select a Color' && $("#Team option:selected").text() !== 'Select a Team') {
            $('#submit').removeClass('hidden');
        }
        else{
            $('#submit').addClass('hidden');
        }
    });

function teamView(){
    window.open('https://gigazonegaming.localhost/app/GameDisplay/'+$( '#Tournament option:selected').text()+'/'+$( '#Team option:selected').text()+'/'+$( '#Color option:selected').text());
}
// setTimeout(
//     function(){
//         $('#divA0').fadeOut(2000);
//         $('#divA1').fadeOut(2000);
//         $('#divA2').fadeOut(2000);
//         $('#divA3').fadeOut(2000);
//         $('#divA4').fadeOut(2000);
//     },
//     2000
// );
// setTimeout(
//     function() {
//         $('#divB0').fadeIn(3000);
//         $('#divB1').fadeIn(3000);
//         $('#divB2').fadeIn(3000);
//         $('#divB3').fadeIn(3000);
//         $('#divB4').fadeIn(3000);
//     },
//     4100
// );