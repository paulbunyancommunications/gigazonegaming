/* Created by Roman on 5/22/17.
 * added comment
 */
function setChampions() {
    checkAndGrabChampion();
}
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
/*This function creates the drop down effect for the extra stats container  */
function showExtraStats(id) {
    $('#' + id).animate({
        'height': '300px'
    }, 200, 'linear');
    id = id.split('');
    $('#S'+id[5]).hide();
}

$(window).resize(function(){for(i=0;i<5;i++){$('#extra'+ i).css({'height': '32px'});setTimeout(function(){$('#S'+i).show();},200);}});

/*This function resets the drop down effect for the extra stats container*/
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
/*This sets the default view for a mobile layout*/
function mobileDisplay(){
    if(document.getElementById('other')) {
        if ($(window).width() <= 530) {
            for (i = 0; i < 5; i++) {
                $('#' + i + '-0').addClass('hidden');
                $('#' + i + '-1').addClass('hidden');
                $('#' + i + '-2').addClass('hidden');
                $('#' + i + '-3').removeClass('hidden');
                $('#' + i + '-4').addClass('v-align');
                $(document).ready(setBoxHeight());
                document.getElementById('extra' + i).innerHTML = '<button id="' + "S" + i + '" onclick=showExtraStats($(this).parent().attr("id"))>Expand</button><div class="carousel" id="' + "carousel" + i + '"><figure class="spinner" id="' + "spinner" + i + '"><img class="minimize" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I1"><img class="explode" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I2"><img class="explode" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I3"></figure><figure class="spinnerRank" id="' + "championRank" + i + '"><div>Rank 1</div><div>Rank 2</div><div>Rank 3</div></figure></div><span style="float:left" class="ss-icon" onclick=galleryspin("-","' + "spinner" + i + '","' + "championRank" + i + '")>&lt;</span><span style="float:right" class="ss-icon" onclick=galleryspin("","' + "spinner" + i + '","' + "championRank" + i + '")>&gt;</span><br/><div id="image1Infospinner'+ i +'" class="playerInfo">Image 1</div><div id="image2Infospinner'+ i +'" class="hidden playerInfo">Image 2</div><div id="image3Infospinner'+ i +'" class="hidden playerInfo">Image 3</div><button onclick=removeExtraStats($(this).parent().attr("id"))>Collapse</button>';
            }
        } else {
            for (i = 0; i < 5; i++) {
                $('#' + i + '-0').removeClass('hidden');
                $('#' + i + '-1').removeClass('hidden');
                $('#' + i + '-2').removeClass('hidden');
                $('#' + i + '-3').addClass('hidden');
                $('#' + i + '-4').removeClass('v-align');
                $(document).ready(setBoxHeight());
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
$(document).ready(function(){setBoxHeight();});

$(window).resize(function(){setBoxHeight();});
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
                    document.getElementById('divE' + data[2][i]).innerHTML = '<i id="'+ "M"+ data[2][i]+'" style="position: absolute;bottom:5px;right:0;" class="fa fa-plus-square" aria-hidden="true"></i><img class="championImage" src="' + data[1][i] + '"/><div id="'+ "N"+ data[2][i]+'" class="championName"><h3>' + champName[0] + '</h3></div>';
                }
                fadInChampion();
                setTimeout(UpdateData,2000);
            }else{
                setTimeout(UpdateData,2000);
            }

        }
    });
}
/*Testing a carousel*/
angle = 0;
angle0 = 0;
angle1 = 0;
angle2 = 0;
angle3 = 0;
angle4 = 0;
counterVar = 0;
counterVar0 = 0;
counterVar1 = 0;
counterVar2 = 0;
counterVar3 = 0;
counterVar4 = 0;
prevAngle = 0;
prevAngle0 = 0;
prevAngle1 = 0;
prevAngle2= 0;
prevAngle3 = 0;
prevAngle4 = 0;
function galleryspin(sign,id,rank) {
    if (id === "spinner0"){
        angle = angle0;
        counterVar = counterVar0;
        prevAngle = prevAngle0;

    }else if (id === "spinner1"){
        angle = angle1;
        counterVar = counterVar1;
        prevAngle = prevAngle1;
    }else if (id === "spinner2"){
        angle = angle2;
        counterVar = counterVar2;
        prevAngle = prevAngle2;
    }else if (id === "spinner3"){
        angle = angle3;
        counterVar = counterVar3;
        prevAngle = prevAngle3;
    }else{
        angle = angle4;
        counterVar = counterVar4;
        prevAngle = prevAngle4;
    }
    spinner = document.querySelector("#"+id);
    rankSpinner = document.querySelector("#"+rank);
    prevAngle = angle;
    if (!sign) { angle = angle + 120; } else { angle = angle - 120; }
    spinner.setAttribute("style","-webkit-transform: rotateY("+ angle +"deg); -moz-transform: rotateY("+ angle +"deg); transform: rotateY("+ angle +"deg);");
    rankSpinner.setAttribute("style","-webkit-transform: rotateY("+ angle +"deg); -moz-transform: rotateY("+ angle +"deg); transform: rotateY("+ angle +"deg);");
    if(prevAngle === angle){
        counterVar = 0;
    }
    else if(prevAngle < angle){
        counterVar++;
        if(counterVar === 3){
            counterVar = 0;
        }
    }else{
        counterVar--;
        if(counterVar === -1) {
            counterVar = 2;
        }
    }
    if(counterVar === 0){
        $("#"+id).children("#I1").addClass("minimize").removeClass("explode");
        $("#"+id).children("#I2").addClass("explode").removeClass("minimize");
        $("#"+id).children("#I3").addClass("explode").removeClass("minimize");

        $('#image3Info'+id).addClass("hidden");
        $('#image2Info'+id).addClass("hidden");
        $('#image1Info'+id).removeClass("hidden");
    }else if(counterVar === 1){
        $("#"+id).children("#I1").addClass("explode").removeClass("minimize");
        $("#"+id).children("#I2").addClass("minimize").removeClass("explode");
        $("#"+id).children("#I3").addClass("explode").removeClass("minimize");

        $('#image3Info' + id).addClass("hidden");
        $('#image2Info' + id).removeClass("hidden");
        $('#image1Info' + id).addClass("hidden");
    }else{
        $("#"+id).children("#I1").addClass("explode").removeClass("minimize");
        $("#"+id).children("#I2").addClass("explode").removeClass("minimize");
        $("#"+id).children("#I3").addClass("minimize").removeClass("explode");

        $('#image3Info'+id).removeClass("hidden");
        $('#image2Info'+id).addClass("hidden");
        $('#image1Info'+id).addClass("hidden");
    }
    if (id === "spinner0"){
        angle0 = angle;
        counterVar0 = counterVar;
        prevAngle0 = prevAngle;

    }else if (id === "spinner1"){
        angle1 = angle;
        counterVar1 = counterVar;
        prevAngle1 = prevAngle;
    }else if (id === "spinner2"){
        angle2 = angle;
        counterVar2 = counterVar;
        prevAngle2 = prevAngle;
    }else if (id === "spinner3"){
        angle3 = angle;
        counterVar3 = counterVar;
        prevAngle3 = prevAngle;
    }else{
        angle4 = angle;
        counterVar4 = counterVar;
        prevAngle4 = prevAngle;
    }
}
$("#carousel0").on("swipeleft",function(){
    galleryspin("-","spinner0");
});
$("#carousel1").on("swipeleft",function(){
    galleryspin("-","spinner1");
});
$("#carousel2").on("swipeleft",function(){
    galleryspin("-","spinner2");
});
$("#carousel3").on("swipeleft",function(){
    galleryspin("-","spinner3");
});
$("#carousel4").on("swipeleft",function(){
    galleryspin("-","spinner4");
});
$("#carousel0").on("swiperight",function(){
    galleryspin("","spinner0");
});
$("#carousel1").on("swiperight",function(){
    galleryspin("","spinner1");
});
$("#carousel2").on("swiperight",function(){
    galleryspin("","spinner2");
});
$("#carousel3").on("swiperight",function(){
    galleryspin("","spinner3");
});
$("#carousel4").on("swiperight",function(){
    galleryspin("","spinner4");
});