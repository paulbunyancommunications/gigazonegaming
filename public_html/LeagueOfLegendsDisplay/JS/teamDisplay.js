/* Created by Roman on 5/22/17.
 * added comment
 */
function setChampions() {
    checkAndGrabChampion();
}
/* This function is set up for testing purposes only, needs to be changed for production */
// fadInChampion();
// function fadInChampion() {
//     setTimeout(
//         function(){
//             $('#divA0').fadeOut(2000);
//             $('#divA1').fadeOut(2000);
//             $('#divA2').fadeOut(2000);
//             $('#divA3').fadeOut(2000);
//             $('#divA4').fadeOut(2000);
//         },
//         0
//     );
//     setTimeout(
//         function() {
//             $('#divB0').fadeIn(3000);
//             $('#divB1').fadeIn(3000);
//             $('#divB2').fadeIn(3000);
//             $('#divB3').fadeIn(3000);
//             $('#divB4').fadeIn(3000);
//             document.getElementById('divB0').innerHTML= '<img id="0" class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/><div class="championName"><h3>Azir</h3></div>';
//             document.getElementById('divB1').innerHTML= '<img id="1" class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/><div class="championName"><h3>Azir</h3></div>';
//             document.getElementById('divB2').innerHTML= '<img id="2" class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/><div class="championName"><h3>Azir</h3></div>';
//             document.getElementById('divB3').innerHTML= '<img id="3" class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/><div class="championName"><h3>Azir</h3></div>';
//             document.getElementById('divB4').innerHTML= '<img id="4" class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/><div class="championName"><h3>Azir</h3></div>';
//             document.getElementById('C0').innerHTML= '<img class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/>';
//             document.getElementById('C1').innerHTML= '<img class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/>';
//             document.getElementById('C2').innerHTML= '<img class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/>';
//             document.getElementById('C3').innerHTML= '<img class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/>';
//             document.getElementById('C4').innerHTML= '<img class="championImage" src="/LeagueOfLegendsDisplay/Images/defaultChampion.jpg"/>';
//             },
//         2100
//     );
// }
function showExtraStats(id){
setTimeout(function(){
    $('#extra'+id).animate({
        'height': '300px'
    }, 200, 'linear');
},100);
}

function showBackground(){

    $('.backgroundH1').animate({
        'background-position-x': '0%',
        'background-position-y': '100%'
    }, 50000, 'linear',showBackground2);
}
function showBackground2(){

    $('.backgroundH1').animate({
        'background-position-x': '0%',
        'background-position-y': '0%'
    }, 50000, 'linear',showBackground);
}
showBackground();

/*This is used to collapse the columns so that they are smaller allowing for more info on the page if wanted*/
$(document ).on('click', '.championImage', function(){
    $('#' + this.id+ '-0').addClass('hidden');
    $('#' + this.id+ '-1').addClass('hidden');
    $('#' + this.id+ '-2').addClass('hidden');
    $('#' + this.id+ '-3').removeClass('hidden');
    $('#' + this.id+ '-4').addClass('v-align');
    setBoxHeight();
    showExtraStats(this.id);
});
$('#0-3').click( function(){
    $('#0-0').removeClass('hidden');
    $('#0-1').removeClass('hidden');
    $('#0-2').removeClass('hidden');
    $('#0-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
    $('#extra0').height(0);
});
$('#1-3').click( function(){
    $('#1-0').removeClass('hidden');
    $('#1-1').removeClass('hidden');
    $('#1-2').removeClass('hidden');
    $('#1-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
    $('#extra1').height(0);
});
$('#2-3').click( function(){
    $('#2-0').removeClass('hidden');
    $('#2-1').removeClass('hidden');
    $('#2-2').removeClass('hidden');
    $('#2-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
    $('#extra2').height(0);
});
$('#3-3').click( function(){
    $('#3-0').removeClass('hidden');
    $('#3-1').removeClass('hidden');
    $('#3-2').removeClass('hidden');
    $('#3-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
    $('#extra3').height(0);
});
$('#4-3').click( function(){
    $('#4-0').removeClass('hidden');
    $('#4-1').removeClass('hidden');
    $('#4-2').removeClass('hidden');
    $('#4-4').removeClass('v-align');
    $('#'+this.id).addClass('hidden');
    $('#extra4').height(0);
});

function setBoxHeight(){
    $('#D0').height($('#C0').height() - 9);
    $('#D1').height($('#C1').height() - 9);
    $('#D2').height($('#C2').height() - 9);
    $('#D3').height($('#C3').height() - 9);
    $('#D4').height($('#C4').height() - 9);
}

$(window).resize(function(){
    setBoxHeight();
});
//
$(document).ready(GetData());
function GetData(){
    if(!document.getElementById('other')){
          ///Execute cache controller with ajax
        $.ajax({
            method: "GET",
            type: "GET",
            url: "/app/GameDisplay/getData",
            success: function(data){
                if(data){
                    location.reload();
                }else{
                    GetData();
                }

            }

        });
    }
}
