/* Created by Roman on 5/22/17.
 * added comment
 */
/* This function is used to control the fading of the loading "cards" and the champion images once received */
function fadInChampion() {
    setTimeout(
        function(){
            $('#divA0').fadeOut(2000);
            $('#divA1').fadeOut(2000);
            $('#divA2').fadeOut(2000);
            $('#divA3').fadeOut(2000);
            $('#divA4').fadeOut(2000);
            $('#divD0').fadeOut(2000);
            $('#divD1').fadeOut(2000);
            $('#divD2').fadeOut(2000);
            $('#divD3').fadeOut(2000);
            $('#divD4').fadeOut(2000);

        },
        2000
    );
    setTimeout(
        function() {
            $('#divB0').fadeIn(2000);
            $('#divB1').fadeIn(2000);
            $('#divB2').fadeIn(2000);
            $('#divB3').fadeIn(2000);
            $('#divB4').fadeIn(2000);
            $('#divE0').fadeIn(2000);
            $('#divE1').fadeIn(2000);
            $('#divE2').fadeIn(2000);
            $('#divE3').fadeIn(2000);
            $('#divE4').fadeIn(2000);
        },
        4000
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
/*This sets the default view for a mobile layout*/
function mobileDisplay(){
    if(document.getElementById('other')) {
        if ($(window).width() <= 530) {
            for (i = 0; i < 5; i++) {
                $('#' + i + '-0').addClass('hidden');
                $('#' + i + '-1').addClass('hidden');
                $('#' + i + '-2').addClass('hidden');
                $('#' + i + '-4').addClass('hidden').removeClass("col-lg");
                setTimeout(function(){setBoxHeight();},1);
                document.getElementById('extra' + i).innerHTML =
                    '<div class="championRank" id="Rank3"><b>Rank 3</b><br/><img class="minimize I3" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I3' + i +'" onclick=ShowInfo("I3' + i +'")></div>'+
                    '<div class="championRank" id="Rank1"><b>Rank 1</b><br/><img class="explode shadow I1" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I1' + i +'" onclick=ShowInfo("I1' + i +'")></div>'+
                    '<div class="championRank" id="Rank2"><b>Rank 2</b><br/><img class="minimize I2" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I2' + i +'" onclick=ShowInfo("I2' + i +'")></div>'+
                    '<div id="imageInfo1' + i +'" >Image 1</div>' +
                    '<div id="imageInfo2' + i +'" class="hidden">Image 2</div>' +
                    '<div id="imageInfo3' + i +'" class="hidden">Image 3</div>';
            }
            $('#carouselControls').removeClass('hidden');

        }else{
            for (i = 0; i < 5; i++) {
                $('#' + i + '-0').removeClass('hidden');
                $('#' + i + '-1').removeClass('hidden');
                $('#' + i + '-2').removeClass('hidden');
                $('#' + i + '-4').removeClass('hidden').addClass("col-lg");
            }
            $('#carouselControls').addClass('hidden');
        }
    }
}
$(window).resize(function(){mobileDisplay(),setTimeout(function(){setBoxHeight();},1)});
mobileDisplay();
/*This makes sure that when collapsed the champion image and the player stats containers are the same size*/
function setBoxHeight(){
    $('#D0').height($('#C0').height() - 9);
    $('#D1').height($('#C1').height() - 9);
    $('#D2').height($('#C2').height() - 9);
    $('#D3').height($('#C3').height() - 9);
    $('#D4').height($('#C4').height() - 9);
}

/*This function gets the data needed for loading the page*/
$(document).ready(function(){GetData(),setTimeout(function(){setBoxHeight();},1)});

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
            },
        })
    }else{
        UpdateData();
    }
}

/*This function gets the champions, as well as checking the cache for any updated data*/
function UpdateData() {
    checkChamp = false;
    if(!document.getElementsByClassName('championImage')){
        checkChamp = true;
    }

    team = window.location.href;
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
                    document.getElementById('divE' + data[2][i]).innerHTML = '<img class="championImage" src="' + data[1][i] + '"/><div id="'+ "N"+ data[2][i]+'" class="championName"><h3>' + champName[0] + '</h3></div>';
                }
                fadInChampion();
                setTimeout(UpdateData,2000);
            }else{
                setTimeout(UpdateData,2000);
            }

        }
    });
}

$(document).ready(function() {
    $(".collapse").swiperight(function() {
        $('#carouselControls').carousel('prev');
        setTimeout(function(){setBoxHeight();},100);
    });
    $(".collapse").swipeleft(function() {
        $('#carouselControls').carousel('next');
        setTimeout(function(){setBoxHeight();},100);
    });
    $(".collapse-b").swiperight(function() {
        $('#carouselControls').carousel('prev');
        setTimeout(function(){setBoxHeight();},100);
    });
    $(".collapse-b").swipeleft(function() {
        $('#carouselControls').carousel('next');
        setTimeout(function(){setBoxHeight();},100);
    });
});

$(document).ready(function() {
    $("#extra0").swiperight(function(){swipeRightExtra(0)});
    $("#extra1").swiperight(function(){swipeRightExtra(1)});
    $("#extra2").swiperight(function(){swipeRightExtra(2)});
    $("#extra3").swiperight(function(){swipeRightExtra(3)});
    $("#extra4").swiperight(function(){swipeRightExtra(4)});
    $("#extra0").swipeleft(function(){swipeLeftExtra(0)});
    $("#extra1").swipeleft(function(){swipeLeftExtra(1)});
    $("#extra2").swipeleft(function(){swipeLeftExtra(2)});
    $("#extra3").swipeleft(function(){swipeLeftExtra(3)});
    $("#extra4").swipeleft(function(){swipeLeftExtra(4)});
});

championCounter0 = 0;
championCounter1 = 0;
championCounter2 = 0;
championCounter3 = 0;
championCounter4 = 0;
function swipeRightExtra(i){
    if(i === 0){championCounter = championCounter0;}
    else if(i === 1){championCounter = championCounter1;}
    else if(i === 2){championCounter = championCounter2;}
    else if(i === 3){championCounter = championCounter3;}
    else{championCounter = championCounter4;}
    if(championCounter === -2){championCounter = 1;}
    if(championCounter === 0){
        $("#I3"+i).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I2"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I1"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+i).parent().parent().find("div#imageInfo1"+i).addClass('hidden');
        $("#I3"+i).parent().parent().find("div#imageInfo2"+i).addClass('hidden');
        $("#I3"+i).parent().parent().find("div#imageInfo3"+i).removeClass('hidden');
        championCounter--;
    }
    else if(championCounter === -1){
        $("#I2"+i).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I1"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I2"+i).parent().parent().find("div#imageInfo1"+i).addClass('hidden');
        $("#I2"+i).parent().parent().find("div#imageInfo2"+i).removeClass('hidden');
        $("#I2"+i).parent().parent().find("div#imageInfo3"+i).addClass('hidden');
        championCounter--;
    }
    else{
        $("#I1"+i).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I2"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I1"+i).parent().parent().find("div#imageInfo1"+i).removeClass('hidden');
        $("#I1"+i).parent().parent().find("div#imageInfo2"+i).addClass('hidden');
        $("#I1"+i).parent().parent().find("div#imageInfo3"+i).addClass('hidden');
        championCounter--;
    }
    if(i === 0){championCounter0 = championCounter;}
    else if(i === 1){championCounter1 = championCounter;}
    else if(i === 2){championCounter2 = championCounter;}
    else if(i === 3){championCounter3 = championCounter;}
    else{championCounter4 = championCounter;}
}

function swipeLeftExtra(i){
    if(i === 0){championCounter = championCounter0;}
    else if(i === 1){championCounter = championCounter1;}
    else if(i === 2){championCounter = championCounter2;}
    else if(i === 3){championCounter = championCounter3;}
    else{championCounter = championCounter4;}
    if(championCounter === 2){championCounter = -1;}

    if(championCounter === 0){
        $("#I2"+i).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I1"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I2"+i).parent().parent().find("div#imageInfo1"+i).addClass('hidden');
        $("#I2"+i).parent().parent().find("div#imageInfo2"+i).removeClass('hidden');
        $("#I2"+i).parent().parent().find("div#imageInfo3"+i).addClass('hidden');
        championCounter++;
    }
    else if(championCounter === 1){
        $("#I3"+i).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I2"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I1"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+i).parent().parent().find("div#imageInfo1"+i).addClass('hidden');
        $("#I3"+i).parent().parent().find("div#imageInfo2"+i).addClass('hidden');
        $("#I3"+i).parent().parent().find("div#imageInfo3"+i).removeClass('hidden');
        championCounter++;
    }
    else{
        $("#I1"+i).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I2"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+i).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I1"+i).parent().parent().find("div#imageInfo1"+i).removeClass('hidden');
        $("#I1"+i).parent().parent().find("div#imageInfo2"+i).addClass('hidden');
        $("#I1"+i).parent().parent().find("div#imageInfo3"+i).addClass('hidden');
        championCounter++;
    }
    if(i === 0){championCounter0 = championCounter;}
    else if(i === 1){championCounter1 = championCounter;}
    else if(i === 2){championCounter2 = championCounter;}
    else if(i === 3){championCounter3 = championCounter;}
    else{championCounter4 = championCounter;}
}

function ShowInfo(id){
    array = id.split("");
    if (id.includes("I1")){
        $("#"+id).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I2"+array[2]).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+array[2]).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#"+id).parent().parent().find("div#imageInfo1"+array[2]).removeClass('hidden');
        $("#"+id).parent().parent().find("div#imageInfo2"+array[2]).addClass('hidden');
        $("#"+id).parent().parent().find("div#imageInfo3"+array[2]).addClass('hidden');
    }
    else if (id.includes("I2")){
        $("#"+id).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I1"+array[2]).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I3"+array[2]).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#"+id).parent().parent().find("div#imageInfo1"+array[2]).addClass('hidden');
        $("#"+id).parent().parent().find("div#imageInfo2"+array[2]).removeClass('hidden');
        $("#"+id).parent().parent().find("div#imageInfo3"+array[2]).addClass('hidden');

    }
    else{
        $("#"+id).addClass('shadow').removeClass('minimize').addClass('explode');
        $("#I2"+array[2]).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#I1"+array[2]).removeClass('shadow').addClass('minimize').removeClass('explode');
        $("#"+id).parent().parent().find("div#imageInfo1"+array[2]).addClass('hidden');
        $("#"+id).parent().parent().find("div#imageInfo2"+array[2]).addClass('hidden');
        $("#"+id).parent().parent().find("div#imageInfo3"+array[2]).removeClass('hidden');

    }
}