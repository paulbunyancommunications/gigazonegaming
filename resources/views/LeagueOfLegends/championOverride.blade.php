<!doctype html>
<html lang="{{ config('app.locale')}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href='/LeagueOfLegendsDisplay/CSS/override.css'/>
    <link rel="stylesheet" type="text/css" href='/LeagueOfLegendsDisplay/CSS/teamDisplay.css'/>
    <Title>Champion Override</Title>
</head>
<body class="mainDiv">
<h1>Champion Override</h1>
    <label class="playerHeading" for="player1">Player1:</label><br/><input list="champions" id="player1"><br/>
    <label class="playerHeading" for="player2">Player2:</label><br/><input list="champions" id="player2"><br/>
    <label class="playerHeading" for="player3">Player3:</label><br/><input list="champions" id="player3"><br/>
    <label class="playerHeading" for="player4">Player4:</label><br/><input list="champions" id="player4"><br/>
    <label class="playerHeading" for="player5">Player5:</label><br/><input list="champions" id="player5"><br/>
    <button onclick="findChampion();">Submit</button><br/>

    <div style="display: inline-block;" id="demo1"></div>
    <div style="display: inline-block;" id="demo2"></div>
    <div style="display: inline-block;" id="demo3"></div>
    <div style="display: inline-block;" id="demo4"></div>
    <div style="display: inline-block;" id="demo5"></div>

    <datalist id="champions">
        <option value="Aatrox">
        <option value="Ahri">
        <option value="Akali">
        <option value="Alistar">
        <option value="Amumu">
        <option value="Anivia">
        <option value="Annie">
        <option value="Ashe">
        <option value="AurelionSol">
        <option value="Azir">
        <option value="Bard">
        <option value="Blitzcrank">
        <option value="Brand">
        <option value="Braum">
        <option value="Caitlyn">
        <option value="Camille">
        <option value="Cassiopeia">
        <option value="Chogath">
        <option value="Corki">
        <option value="Darius">
        <option value="Diana">
        <option value="DrMundo">
        <option value="Draven">
        <option value="Ekko">
        <option value="Elise">
        <option value="Evelynn">
        <option value="Ezreal">
        <option value="Fiddlesticks">
        <option value="Fiora">
        <option value="Fizz">
        <option value="Galio">
        <option value="Gangplank">
        <option value="Garen">
        <option value="Gnar">
        <option value="Gragas">
        <option value="Graves">
        <option value="Hecarim">
        <option value="Heimerdinger">
        <option value="Illaoi">
        <option value="Irelia">
        <option value="Ivern">
        <option value="Janna">
        <option value="JarvanIV">
        <option value="Jax">
        <option value="Jayce">
        <option value="Jhin">
        <option value="Jinx">
        <option value="Kalista">
        <option value="Karma">
        <option value="Karthus">
        <option value="Kassadin">
        <option value="Katarina">
        <option value="Kayle">
        <option value="Kennen">
        <option value="Khazix">
        <option value="Kindred">
        <option value="Kled">
        <option value="KogMaw">
        <option value="Leblanc">
        <option value="LeeSin">
        <option value="Leona">
        <option value="Lissandra">
        <option value="Lucian">
        <option value="Lulu">
        <option value="Lux">
        <option value="Malphite">
        <option value="Malzahar">
        <option value="Maokai">
        <option value="MasterYi">
        <option value="MissFortune">
        <option value="Mordekaiser">
        <option value="Morgana">
        <option value="Nami">
        <option value="Nasus">
        <option value="Nautilus">
        <option value="Nidalee">
        <option value="Nocturne">
        <option value="Nunu">
        <option value="Olaf">
        <option value="Orianna">
        <option value="Pantheon">
        <option value="Poppy">
        <option value="Quinn">
        <option value="Rakan">
        <option value="Rammus">
        <option value="RekSai">
        <option value="Renekton">
        <option value="Rengar">
        <option value="Riven">
        <option value="Rumble">
        <option value="Ryze">
        <option value="Sejuani">
        <option value="Shaco">
        <option value="Shen">
        <option value="Shyvana">
        <option value="Singed">
        <option value="Sion">
        <option value="Sivir">
        <option value="Skarner">
        <option value="Sona">
        <option value="Soraka">
        <option value="Swain">
        <option value="Syndra">
        <option value="TahmKench">
        <option value="Taliyah">
        <option value="Talon">
        <option value="Taric">
        <option value="Teemo">
        <option value="Thresh">
        <option value="Tristana">
        <option value="Trundle">
        <option value="Tryndamere">
        <option value="TwistedFate">
        <option value="Twitch">
        <option value="Udyr">
        <option value="Urgot">
        <option value="Varus">
        <option value="Vayne">
        <option value="Veigar">
        <option value="Velkoz">
        <option value="Vi">
        <option value="Viktor">
        <option value="Vladimir">
        <option value="Volibear">
        <option value="Warwick">
        <option value="MonkeyKing">
        <option value="Xayah">
        <option value="Xerath">
        <option value="XinZhao">
        <option value="Yasuo">
        <option value="Yorick">
        <option value="Zac">
        <option value="Zed">
        <option value="Ziggs">
        <option value="Zilean">
        <option value="Zyra">
    </datalist>
</body>
<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>
<script src="/LeagueOfLegendsDisplay/JS/override.js"></script>
<script>

</script>
</html>
