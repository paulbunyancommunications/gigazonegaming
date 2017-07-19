/**
 * Created by Roman on 6/8/17.
 */
/* This is used to fill in the champions manually if the request cannot be fulfilled*/
function findChampion() {
    document.getElementById("info").innerHTML = "";
    var championArray = [];
    const checkArray =["Aatrox","Ahri","Akali","Alistar","Amumu","Anivia","Annie","Ashe","AurelionSol", "Azir", "Bard",
        "Blitzcrank", "Brand", "Braum", "Caitlyn", "Camille", "Cassiopeia", "Chogath", "Corki", "Darius", "Diana",
        "DrMundo", "Draven", "Ekko", "Elise", "Evelynn", "Ezreal", "Fiddlesticks", "Fiora", "Fizz", "Galio", "Gangplank",
        "Garen", "Gnar", "Gragas", "Graves", "Hecarim", "Heimerdinger", "Illaoi", "Irelia", "Ivern", "Janna", "JarvanIV",
        "Jax", "Jayce", "Jhin", "Jinx", "Kalista", "Karma", "Karthus", "Kassadin", "Katarina", "Kayle", "Kennen", "Khazix",
        "Kindred", "Kled", "KogMaw", "Leblanc", "LeeSin", "Leona", "Lissandra", "Lucian", "Lulu", "Lux", "Malphite",
        "Malzahar", "Maokai", "MasterYi", "MissFortune", "Mordekaiser", "Morgana", "Nami", "Nasus", "Nautilus", "Nidalee",
        "Nocturne", "Nunu", "Olaf", "Orianna", "Pantheon", "Poppy", "Quinn", "Rakan", "Rammus", "RekSai", "Renekton",
        "Rengar", "Riven", "Rumble", "Ryze", "Sejuani", "Shaco", "Shen", "Shyvana", "Singed", "Sion", "Sivir", "Skarner",
        "Sona", "Soraka", "Swain", "Syndra", "TahmKench", "Taliyah", "Talon", "Taric", "Teemo", "Thresh", "Tristana",
        "Trundle", "Tryndamere", "TwistedFate", "Twitch", "Udyr", "Urgot", "Varus", "Vayne", "Veigar", "Velkoz", "Vi",
        "Viktor", "Vladimir", "Volibear", "Warwick", "Wukong", "Xayah", "Xerath", "XinZhao", "Yasuo", "Yorick", "Zac",
        "Zed", "Ziggs", "Zilean", "Zyra"];
    if(document.getElementById("player1").value !== "" && document.getElementById("player2").value !== "" && document.getElementById("player3").value !== ""&& document.getElementById("player4").value !== "" && document.getElementById("player5").value !== ""){
        for(var i=1; i<6; i++)
        {
            if (checkArray.indexOf(document.getElementById("player" + i).value) !== -1) {
                if(document.getElementById("player" + i).value === "Wukong"){
                    championArray.push("http://ddragon.leagueoflegends.com/cdn/img/champion/loading/MonkeyKing_0.jpg");
                }
                else {
                    championArray.push("http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" + document.getElementById("player" + i).value + "_0.jpg");
                }
            }else{
                document.getElementById("info").innerHTML = document.getElementById("info").innerHTML + "Player " + i + ": " +document.getElementById("player" + i).value + " is not a valid name!<br/>";
            }
        }
            if(championArray.length === 5) {
                document.getElementById("info").innerHTML = "Updating Champions...";
                $.ajax({
                    method: "GET",
                    type: "GET",
                    url: "/app/GameDisplay/championsOverride",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        championArray: championArray,
                        team: $("#Team").find("option:selected").text(),
                    },
                    success: function (data) {
                        document.getElementById("info").innerHTML = "<h3>" + data + "</h3>";
                    }
                });
            }
        }else{
        document.getElementById("info").innerHTML="<h3>All Fields Must Be Filled</h3>";
    }
}