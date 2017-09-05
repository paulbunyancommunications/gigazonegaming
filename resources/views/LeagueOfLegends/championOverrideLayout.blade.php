@extends('LeagueOfLegends/championOverride')
<?php $otherChampionList = ["Aatrox","Ahri","Akali","Alistar","Amumu","Anivia","Annie","Ashe","AurelionSol", "Azir", "Bard",
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
    "Viktor", "Vladimir", "Volibear", "Warwick", "MonkeyKing", "Xayah", "Xerath", "XinZhao", "Yasuo", "Yorick", "Zac",
    "Zed", "Ziggs", "Zilean", "Zyra"];?>
@section('options')
    @if(count($allChampions) != 0)
        <option value=""></option>
        @for($i=0;$i<count($allChampions);$i++)
            <option value="{{$allChampions[$i]}}">{{$allChampions[$i]}}</option>
        @endfor
    @else
        <option value=""></option>
        @for($i=0;$i<count($otherChampionList);$i++)
            <option value="{{$otherChampionList[$i]}}">{{$otherChampionList[$i]}}</option>
        @endfor
    @endif
@stop