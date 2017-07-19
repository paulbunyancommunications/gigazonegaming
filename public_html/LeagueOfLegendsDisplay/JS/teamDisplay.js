/* Created by Roman on 5/22/17.
 * added comment
 */
/* This function is used to control the fading of the loading "cards" and the champion images once received */
function fadInChampion() {
    setTimeout(
        function(){
            for(let i=0;i<5;i++) {
                $('#divA'+i).fadeOut(2000);
                $('#divD'+i).fadeOut(2000);
            }
        },
        2000
    );
    setTimeout(
        function() {
            for(let i=0;i<5;i++) {
                $('#divB'+i).fadeIn(2000);
                $('#divE'+i).fadeIn(2000);
            }
        },
        4000
    );
}

/* This function allows the display of victory or defeat at the end of the game */
function displayWinLoss(){
    let team = window.location.href;
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

/* This checks the width of the window and according to what it is, it either gives the mobile view or the default view */
function mobileDisplay(){
    if(document.getElementById('other')) {
        if ($(window).width() <= 530) {
            for (let i = 0; i < 5; i++) {
                $('#' + i + '-0').addClass('hidden');
                $('#' + i + '-1').addClass('hidden');
                $('#' + i + '-2').addClass('hidden');
                $('#' + i + '-4').addClass('hidden').removeClass("col-lg");
                setTimeout(function(){setBoxHeight();},1);
                document.getElementById('extra' + i).innerHTML =
                    '<div class="championRankMinimize" id="Rank3' + i +'"><b>Rank 3</b><br/><img class="I3" src="/LeagueOfLegendsDisplay/Images/Ahri.png" alt id="I3' + i +'"></div>'+
                    '<div class="championRank" id="Rank1' + i +'"><b>Rank 1</b><br/><img class="I1" src="/LeagueOfLegendsDisplay/Images/defaultChampIcon.png" alt id="I1' + i +'"></div>'+
                    '<div class="championRankMinimize" id="Rank2' + i +'"><b>Rank 2</b><br/><img class="I2" src="/LeagueOfLegendsDisplay/Images/Amumu.png" alt id="I2' + i +'"></div>'+
                    '<div style="width;100%;"><div class="championNav" onclick= switchPlaces('+ i +',"+")>&lt</div>'+
                    '<div class="championNav" onclick= switchPlaces('+ i +',"-") >&gt</div></div>'+
                    '<div id="imageInfo1' + i +'" >Image 1</div>' +
                    '<div id="imageInfo2' + i +'" class="hidden">Image 2</div>' +
                    '<div id="imageInfo3' + i +'" class="hidden">Image 3</div>';
            }
            $('#carouselControls').removeClass('hidden');
        }else{
            for (let i = 0; i < 5; i++) {
                $('#' + i + '-0').removeClass('hidden');
                $('#' + i + '-1').removeClass('hidden');
                $('#' + i + '-2').removeClass('hidden');
                $('#' + i + '-4').removeClass('hidden').addClass("col-lg");
            }
            $('#carouselControls').addClass('hidden');
        }
    }
}
$(window).resize(function(){mobileDisplay()});
$(window).resize(function(){setTimeout(function(){setBoxHeight();},1)});
mobileDisplay();

/*This makes sure that when collapsed the champion image and the player stats container are the same size*/
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

/* This is for testing */
$(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $("#hiddenToken").text(),
            }});
    }
);

/*This gets the data needed for loading the page from the cache*/
$(document).ready(GetData());
function GetData() {
    if (!document.getElementById('other')) {
        let team = window.location.href;
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
        UpdateData(true);
    }
}

/* This checks for when champions are entered into the cache as well as any other updated information*/
function UpdateData(checkChamp) {
    let team = window.location.href;
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
                for (let i = 0; i < data[1].length; i++) {
                    let champName = data[1][i].split("/");
                    champName = champName[champName.length - 1].split("_");
                    if(champName[0] === "MonkeyKing"){
                        champName[0] = "Wukong";
                    }
                    document.getElementById('divB' + data[2][i]).innerHTML = '<img id="' + data[2][i] + '" class="championImage" src="' + data[1][i] + '"/><div class="championName"><h3>' + champName[0] + '</h3></div>';
                    document.getElementById('divE' + data[2][i]).innerHTML = '<img id="'+ "M"+ data[2][i]+'" class="championImage" src="' + data[1][i] + '"/><div class="championName"><h3>' + champName[0] + '</h3></div>';
                }
                fadInChampion();
                setTimeout(function(){ UpdateData(false) },2000);
            }else{
                setTimeout(function(){ UpdateData(checkChamp) },2000);
            }

        }
    });
}

/* This changes the main carousel to the previous or next summoner */
$(document).ready(function() {
    $(".collapse").swipeleft(function() {
        $('#carouselControls').carousel('next');
        setTimeout(function(){setBoxHeight();},100);
    }).swiperight(function() {
        $('#carouselControls').carousel('prev');
        setTimeout(function(){setBoxHeight();},100);
    });

    $(".collapse-b").swipeleft(function() {
        $('#carouselControls').carousel('next');
        setTimeout(function(){setBoxHeight();},100);
    }).swiperight(function() {
        $('#carouselControls').carousel('prev');
        setTimeout(function(){setBoxHeight();},100);
    });
});

/* This allows the swiping effect in the extra stats container to change ranked champions */
$(document).ready(function() {
    $("#extra0").swiperight(function(){switchPlaces(0,"-")}).swipeleft(function(){switchPlaces(0,"+")});
    $("#extra1").swiperight(function(){switchPlaces(1,"-")}).swipeleft(function(){switchPlaces(1,"+")});
    $("#extra2").swiperight(function(){switchPlaces(2,"-")}).swipeleft(function(){switchPlaces(2,"+")});
    $("#extra3").swiperight(function(){switchPlaces(3,"-")}).swipeleft(function(){switchPlaces(3,"+")});
    $("#extra4").swiperight(function(){switchPlaces(4,"-")}).swipeleft(function(){switchPlaces(4,"+")});
});

/* This displays the information according to what champion is inside the #Rank1 div */
function ShowInfo(i){
    if ($("#Rank1"+ i).find("b").text() === "Rank 1"){
        $("#imageInfo1"+i).removeClass('hidden');
        $("#imageInfo2"+i+",#imageInfo3"+i).addClass('hidden');
    }
    else if ($("#Rank1"+i).find("b").text() === "Rank 2"){
        $("#imageInfo1"+i+",#imageInfo3"+i).addClass('hidden');
        $("#imageInfo2"+i).removeClass('hidden');
    }
    else{
        $("#imageInfo1"+i+",#imageInfo2"+i).addClass('hidden');
        $("#imageInfo3"+i).removeClass('hidden');
    }
}
/* This replaces the contents of the Rank divs in the extra stats to view different champions */
function switchPlaces(i,sign){
    x = document.getElementById("Rank1"+ i).innerHTML;
    y = document.getElementById("Rank2"+ i).innerHTML;
    z = document.getElementById("Rank3"+ i).innerHTML;
    if(sign === "-") {
        document.getElementById("Rank1" + i).innerHTML = z;
        document.getElementById("Rank2" + i).innerHTML = x;
        document.getElementById("Rank3" + i).innerHTML = y;
    }else {
        document.getElementById("Rank1" + i).innerHTML = y;
        document.getElementById("Rank2" + i).innerHTML = z;
        document.getElementById("Rank3" + i).innerHTML = x;
    }
    ShowInfo(i);
}