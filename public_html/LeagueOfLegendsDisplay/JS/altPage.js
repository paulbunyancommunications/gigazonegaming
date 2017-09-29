/* This is for testing */
$(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("#hiddenToken").text(),
            }});
    }
);

/*This gets the data needed for loading the page from the cache*/
$(document).ready(GetData());
function GetData() {
    if (!document.getElementById("other")) {
        var team = window.location.href;
        team = team.split("/");
        team = team[5];
        ///Execute cache controller with ajax
        $.ajax({
            method: "GET",
            type: "GET",
            url: "/app/GameDisplay/getData",
            data: {
                "_token": "{{ csrf_token() }}",
                team: team
            },
            success: function (data) {
                if (data === "true") {
                    location.reload();
                } else{
                    setTimeout(GetData,5000);
                    $.ajax({
                        method: "GET",
                        type: "GET",
                        url: "/app/GameDisplay/CarouselUpdate",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            team: team
                        },
                        success: function (data) {
                            if(data){
                                $('.carousel-inner').html("");
                                console.log(data);
                                var images = [];
                                $("img").each(function(){
                                    images.push($(this).attr('src'))
                                });
                                $('.carousel-inner').append("<div class='carousel-item active'><img class='d-block img-fluid' src=/" + data[0] + " alt='' style='margin-right:auto; margin-left: auto;'></div>");
                                if(data.length === 1){
                                    $('#carouselExampleSlidesOnly').attr('data-interval', false);
                                }
                                for(i=1;i<data.length;i++) {
                                    $('.carousel-inner').append("<div class='carousel-item'><img class='d-block img-fluid' src=/" + data[i] + " alt='' style='margin-right:auto; margin-left: auto;'></div>");
                                }
                            }
                        }
                    })
                }
            },
        })
    }else{
        UpdateData(true);
    }
}

/* This checks for when champions are entered into the cache as well as any other updated information*/
function UpdateData(checkChamp) {
    var team = window.location.href;
    team = team.split("/");
    team = team[5];
    ///Execute cache controller with ajax
    $.ajax({
        method: "GET",
        type: "GET",
        url: "/app/GameDisplay/Update",
        data: {
            "_token": "{{ csrf_token() }}",
            team: team,
            checkChamp: checkChamp
        },
        success: function (data) {
            console.log(data);

            //If Content has been updated
            if (data[0] === 'true') {
                location.reload();
            }

            //If Champions have been submitted
            else if (data[1] !== 'false') {
                for (var i = 0; i < data[1].length; i++) {
                    var champName = data[1][i].split("/");
                    champName = champName[champName.length - 1].split("_");
                    if(champName[0] === "MonkeyKing"){
                        champName[0] = "Wukong";
                    }
                    document.getElementById("divB" + data[2][i]).innerHTML = '<img id="' + data[2][i] + '" class="championImage" src="' + data[1][i] + '"/><div class="championName"><h3>' + champName[0] + '</h3></div>';
                    document.getElementById("divE" + data[2][i]).innerHTML = '<img id="'+ "M"+ data[2][i]+'" class="championImage" src="' + data[1][i] + '"/><div class="championName"><h3>' + champName[0] + '</h3></div>';
                }
                fadInChampion();
                setTimeout(function () {
                    UpdateData(false)
                }, 2000);
            }else{
                setTimeout(function(){ UpdateData(checkChamp) },2000);
            }

        }
    });
}