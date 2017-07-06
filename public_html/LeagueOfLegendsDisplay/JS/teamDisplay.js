/* Created by Roman on 5/22/17.
 * added comment
 */

/* This function is set up for testing purposes only, needs to be changed for production */
function fadInChampion() {
    setTimeout(
        function(){
            $('#divA0').fadeOut(2000);
            $('#divA1').fadeOut(2000);
            $('#divA2').fadeOut(2000);
            $('#divA3').fadeOut(2000);
            $('#divA4').fadeOut(2000);
        },
        2000
    );
    setTimeout(
        function() {
            $('#divB0').fadeIn(3000);
            $('#divB1').fadeIn(3000);
            $('#divB2').fadeIn(3000);
            $('#divB3').fadeIn(3000);
            $('#divB4').fadeIn(3000);
        },
        4100
    );
}

/*This function allows the display of victory or defeat*/
function displayWinLoss(){
    team = window.location.href;
    team = team.split('/');
    team = team[5];
    if(team === 'team1') {
        document.getElementById('endResult').innerHTML = '<img  id="victory" src="/LeagueOfLegendsDisplay/Images/victory.png "/>';
    }else{
        document.getElementById('endResult').innerHTML = '<img id="defeat" src="/LeagueOfLegendsDisplay/Images/defeat.png "/>';
    }
    setTimeout(function(){ $('#endResult').fadeIn(3000); $('.mainDiv').css({
        '-webkit-filter': 'blur(5px)',
        '-moz-filter': 'blur(5px)',
        '-o-filter': 'blur(5px)',
        '-ms-filter': 'blur(5px)',
        'filter': 'blur(5px)'
    })},2000);
}
/*This function creates the drop down effect for the extra stats container  */
function showExtraStats(id) {
    $('#' + id).animate({
        'height': '300px'
    }, 200, 'linear');
    id = id.split('');
    $('#S'+id[5]).hide();
}
function removeExtraStats(id){
        $('#'+ id).animate({
            'height': '32px'
        }, 200, 'linear');
        id = id.split('');
        setTimeout(function(){$('#S'+id[5]).show();},200);
}

/*These functions create the constantly moving header image behind the team name */
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
$(document).on('click', '.championImage', function(){
    $('#' + this.id+ '-0').addClass('hidden');
    $('#' + this.id+ '-1').addClass('hidden');
    $('#' + this.id+ '-2').addClass('hidden');
    $('#' + this.id+ '-3').removeClass('hidden');
    $('#' + this.id+ '-4').addClass('v-align');
    setBoxHeight();
});
$(document).on('click', '#M0', function(){
    $('#0-0').removeClass('hidden');
    $('#0-1').removeClass('hidden');
    $('#0-2').removeClass('hidden');
    $('#0-4').removeClass('v-align');
    $('#0-3').addClass('hidden');
});
$(document).on('click', '#M1', function(){
    $('#1-0').removeClass('hidden');
    $('#1-1').removeClass('hidden');
    $('#1-2').removeClass('hidden');
    $('#1-4').removeClass('v-align');
    $('#1-3').addClass('hidden');
});
$(document).on('click', '#M2', function(){
    $('#2-0').removeClass('hidden');
    $('#2-1').removeClass('hidden');
    $('#2-2').removeClass('hidden');
    $('#2-4').removeClass('v-align');
    $('#2-3').addClass('hidden');
});
$(document).on('click', '#M3', function(){
    $('#3-0').removeClass('hidden');
    $('#3-1').removeClass('hidden');
    $('#3-2').removeClass('hidden');
    $('#3-4').removeClass('v-align');
    $('#3-3').addClass('hidden');
});
$(document).on('click', '#M4', function(){
    $('#4-0').removeClass('hidden');
    $('#4-1').removeClass('hidden');
    $('#4-2').removeClass('hidden');
    $('#4-4').removeClass('v-align');
    $('#4-3').addClass('hidden');
});
/*This sets the defauly view for a mobile layout*/
function mobileDisplay(){
    if(document.getElementById('other')) {
        if ($(window).width() <= 530) {
            for (i = 0; i < 5; i++) {
                $('#' + i + '-0').addClass('hidden');
                $('#' + i + '-1').addClass('hidden');
                $('#' + i + '-2').addClass('hidden');
                $('#' + i + '-3').removeClass('hidden');
                $('#' + i + '-4').addClass('v-align');
                setBoxHeight();
                document.getElementById('extra' + i).innerHTML = '<button id="' + "S" + i + '" onclick=showExtraStats($(this).parent().attr("id"))>Expand</button><b class="collapse-M-heading">&nbsp;&nbsp;Spells&nbsp;&nbsp;</b><br/><b class="collapse-M-heading">&nbsp;&nbsp;Runes&nbsp;&nbsp;</b><br/><button onclick=removeExtraStats($(this).parent().attr("id"))>Collapse</button>';
            }
        } else {
            for (i = 0; i < 5; i++) {
                $('#' + i + '-0').removeClass('hidden');
                $('#' + i + '-1').removeClass('hidden');
                $('#' + i + '-2').removeClass('hidden');
                $('#' + i + '-3').addClass('hidden');
                $('#' + i + '-4').removeClass('v-align');
                setBoxHeight();
                document.getElementById('extra' + i).innerHTML = '<button id="' + "S" + i + '" onclick=showExtraStats($(this).parent().attr("id"))>Expand</button><b class="collapse-M-heading">&nbsp;&nbsp;Spells&nbsp;&nbsp;</b><br/><b class="collapse-M-heading">&nbsp;&nbsp;Runes&nbsp;&nbsp;</b><br/><button onclick=removeExtraStats($(this).parent().attr("id"))>Collapse</button>';
            }
        }
    }
}
$(window).resize(function(){mobileDisplay();});
mobileDisplay();
/*This makes sure that when collapsed the champion image and the player stats containers are the same size*/
function setBoxHeight(){
    $('#D0').height($('#C0').height() - 9);
    $('#D1').height($('#C1').height() - 9);
    $('#D2').height($('#C2').height() - 9);
    $('#D3').height($('#C3').height() - 9);
    $('#D4').height($('#C4').height() - 9);
}
$(document).ready($(window).resize(function(){
    setBoxHeight();
}));

$(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $("#hiddenToken").text(),
                'Testing': document.getElementsByName('Testing')[0].getAttribute("content")
            }});
    }
);


/*This function gets the data needed for loading the page*/
$(document).ready(GetData());

function GetData() {
    if (!document.getElementById('other')) {
        var team = window.location.href;
        team = team.split('/');
        team = team[5];
        ///Execute cache controller with ajax
        $.ajax({
            method: "GET",
            type: "GET",
            url: "/app/GameDisplay/getData",
            data: {
                '_token': "{{ csrf_token() }}",
                team: team
            },
            success: function (data) {
                if (data === 'true') {
                    location.reload();

                } else{
                    setTimeout(GetData,2000);
                }
            }

        });
    }else{
        UpdateData(true);
    }
}
function UpdateData(checkChamp) {
    var team = window.location.href;
    team = team.split('/');
    team = team[5];
    ///Execute cache controller with ajax
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/Update",
        data: {
            '_token': "{{ csrf_token() }}",
            team: team,
            checkChamp: checkChamp
        },
        success: function (data) {
            console.log(data);
            if (data[0] === 'true') {
                location.reload();
            }
            else if (data[1] !== 'false') {
                for (var i = 0; i < data[1].length; i++) {
                    champName = data[1][i].split("/");
                    champName = champName[champName.length - 1].split("_");
                    if(champName[0] === "MonkeyKing"){
                        champName[0] = "Wukong";
                    }
                    document.getElementById('divB' + data[2][i]).innerHTML = '<img id="' + data[2][i] + '" class="championImage" src="' + data[1][i] + '"/><div class="championName"><h3>' + champName[0] + '</h3></div>';
                    document.getElementById('C' + data[2][i]).innerHTML = '<img id="'+ "M"+ data[2][i]+'" class="championImage" src="' + data[1][i] + '"/><div class="championName"><h3>' + champName[0] + '</h3></div>';
                }
                fadInChampion();
                setTimeout(function(){ UpdateData(false) },2000);
            }else{
                setTimeout(function(){ UpdateData(checkChamp) },2000);
            }

        }
    });
}
