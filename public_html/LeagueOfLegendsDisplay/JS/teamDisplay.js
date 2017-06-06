/* Created by Roman on 5/22/17.
 * added comment
 */
/*This is where the js is for the start page*/

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
showTeams();

/* These three blocks make sure there is a valid selection in all of the dropdowns before the submit button is displayed */
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

function setChampions() {
    checkAndGrabChampion();
}

/*This begins the js that is used on the team display page*/
function fadInChampion() {
    setTimeout(
        function(){
            $('#divA0').fadeOut(2000);
            $('#divA1').fadeOut(2000);
            $('#divA2').fadeOut(2000);
            $('#divA3').fadeOut(2000);
            $('#divA4').fadeOut(2000);
        },
        0
    );
    setTimeout(
        function() {
            $('#divB0').fadeIn(3000);
            $('#divB1').fadeIn(3000);
            $('#divB2').fadeIn(3000);
            $('#divB3').fadeIn(3000);
            $('#divB4').fadeIn(3000);
        },
        2100
    );
}


function showBackground(){

    $('.backgroundH1').animate({
        'background-position-x': '0%',
        'background-position-y': '100%'
    }, 50000, 'linear',showBackground2);
};
function showBackground2(){

    $('.backgroundH1').animate({
        'background-position-x': '0%',
        'background-position-y': '0%'
    }, 50000, 'linear',showBackground);
};
showBackground();

/*This is used to collapse the columns so that they are smaller allowing for more info on the page if wanted*/
$(document ).on('click', '.championImage', function(){
    console.log("here");
    console.log($(this).id);
    console.log(this.id);
    $('#' + this.id+ '-0').addClass('hidden');
    $('#' + this.id+ '-1').addClass('hidden');
    $('#' + this.id+ '-2').addClass('hidden');
    $('#' + this.id+ '-3').removeClass('hidden');
    $('#' + this.id+ '-4').addClass('v-align');
});
$('#0-3').click( function(){
    $('#0-0').removeClass('hidden');
    $('#0-1').removeClass('hidden');
    $('#0-2').removeClass('hidden');
    $('#0-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
});
$('#1-3').click( function(){
    $('#1-0').removeClass('hidden');
    $('#1-1').removeClass('hidden');
    $('#1-2').removeClass('hidden');
    $('#1-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
});
$('#2-3').click( function(){
    $('#2-0').removeClass('hidden');
    $('#2-1').removeClass('hidden');
    $('#2-2').removeClass('hidden');
    $('#2-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
});
$('#3-3').click( function(){
    $('#3-0').removeClass('hidden');
    $('#3-1').removeClass('hidden');
    $('#3-2').removeClass('hidden');
    $('#3-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
});
$('#4-3').click( function(){
    $('#4-0').removeClass('hidden');
    $('#4-1').removeClass('hidden');
    $('#4-2').removeClass('hidden');
    $('#4-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
});

