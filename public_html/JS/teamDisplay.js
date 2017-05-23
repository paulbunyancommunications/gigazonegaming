/**
 * Created by Roman on 5/22/17.
 * added comment
 */

function showTeams(){
    $("#Team").children('option').each(function(i) {
        var id = $("#Tournament  option:selected").val();
        var counter = 0;
        if($(this).attr('t_id') == id){
            $(this).removeClass('hidden');
            $('#submit').removeClass('hidden');
            counter++;
        }else{
            $("#Team").val('default');
            if($(this).val() == "default"){
                $("#defaultTeam").attr('selected','selected');
            }else {
                $(this).addClass('hidden');
                $('#submit').addClass('hidden');
            }
        }
        if(counter == 0){
            $("#Team").addClass("hidden");
            $('#submit').addClass('hidden');
        }else{
            $("#Team").removeClass("hidden");
            $('#submit').removeClass('hidden');
        }
    });
}
showTeams();

function viewTeam(){
    window.open('https://gigazonegaming.localhost/app/GameDisplay/'+$( '#Tournament option:selected').text()+'/'+$( '#Team option:selected').text());
}