/**
 * Created by Roman on 6/8/17.
 */
/* This is used to fill in the champions manually if the request cannot be fulfilled*/
function findChampion() {
    document.getElementById("demo1").innerHTML = '<img src=http://ddragon.leagueoflegends.com/cdn/img/champion/loading/' + document.getElementById("player1").value + '_0.jpg />';
    document.getElementById("demo2").innerHTML = '<img src=http://ddragon.leagueoflegends.com/cdn/img/champion/loading/' + document.getElementById("player2").value + '_0.jpg />';
    document.getElementById("demo3").innerHTML = '<img src=http://ddragon.leagueoflegends.com/cdn/img/champion/loading/' + document.getElementById("player3").value + '_0.jpg />';
    document.getElementById("demo4").innerHTML = '<img src=http://ddragon.leagueoflegends.com/cdn/img/champion/loading/' + document.getElementById("player4").value + '_0.jpg />';
    document.getElementById("demo5").innerHTML = '<img src=http://ddragon.leagueoflegends.com/cdn/img/champion/loading/' + document.getElementById("player5").value + '_0.jpg />';
}