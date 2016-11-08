@extends('game.base')

@section('css')
    tr{
        border:1px dotted #090909;
    }td, th{
        border-right:1px solid #090909;
        border-left:1px solid #090909;
        padding:3px 5px;
    }
    .fa-eye,
    .fa-eye-slash{
        z-index:9999;
        background-color:#e29e3a!important;
    }
    tr:nth-child(even) {background: #CCC}
    tr:nth-child(odd) {background: #FFF}
    .black{
        background-color:#000!important;
    }
    th:hover{
        color:#e29e3a;
    }
    @media print {
        tr{
            border:1px dotted #090909!important;
           
        }td, th{
            border-right:1px solid #090909!important;
            border-left:1px solid #090909!important;
            padding:3px 5px!important;
           
        }
        tr:nth-child(even) {
            background: #CCC;!important;
           
        }
        tr:nth-child(odd) {
            background: #FFF;!important;
           
        }
        .not_printable{display:none!important;}
        th:not(.printable){display:none!important;}
        td:not(.printable){display:none!important;}
    }
@endsection
@section('content')
    <a href="#" id="create_link" class="not_printable"><i class="fa fa-cloud-download" aria-hidden="true"></i> Create csv.</a>
<table  id="print_table">
    <thead>
    <tr>
        <td id="row0" class="not_printable col-md-2 text-center fa fa-3x fa-eye"></td>
        <td id="row1" class="not_printable col-md-2 text-center fa fa-3x fa-eye"></td>
        <td id="row2" class="not_printable col-md-2 text-center fa fa-3x fa-eye"></td>
        <td id="row3" class="not_printable col-md-2 text-center fa fa-3x fa-eye"></td>
        <td id="row4" class="not_printable col-md-1 text-center fa fa-3x fa-eye"></td>
        <td id="row5" class="not_printable col-md-1 text-center fa fa-3x fa-eye"></td>
        <td id="row6" class="not_printable col-md-1 text-center fa fa-3x fa-eye"></td>
        <td id="row7" class="not_printable col-md-1 text-center fa fa-3x fa-eye"></td>
    </tr>
    <tr>
        <th class="printable row0 col-md-2 text-center">Player Username <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row1 col-md-2 text-center">Player Name <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row2 col-md-2 text-center">Player Email <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row3 col-md-2 text-center">Player Phone <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row4 col-md-1 text-center">Capitan <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row5 col-md-1 text-center">Team <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row6 col-md-1 text-center">Tournament <span class="not_printable fa fa-sort"></span></th>
        <th class="printable row7 col-md-1 text-center">Game <span class="not_printable fa fa-sort"></span></th>
    </tr>
    </thead>
    <tbody>
@foreach($playerList as $player)
    <tr>
        <td class="printable row0 text-center">{{$player['player_username']}}</td>
        <td class="printable row1 text-center">{{$player['player_name']}}</td>
        <td class="printable row2 text-center">{{$player['player_email']}}</td>
        <td class="printable row3 text-center">{{$player['player_phone']}}</td>
        <td class="printable row4 text-center">@if($player['player_id'] == $player['team_captain']) Yes @else No @endif</td>
        <td class="printable row5 text-center">{{$player['team_name']}}</td>
        <td class="printable row6 text-center">{{$player['tournament_name']}}</td>
        <td class="printable row7 text-center">{{$player['game_title']}}</td>
    </tr>
@endforeach
    </tbody>
</table>
@endsection
@section('js')
    var csvContent = "Username, Name, Email, Phone, Captain, Team, Tournament, Game, \n @foreach($playerList as $player){{$player['player_username']}}, {{$player['player_name']}}, {{$player['player_email']}}, {{$player['player_phone']}}, @if($player['player_id'] == $player['team_captain']) Yes @else No @endif, {{$player['team_name']}}, {{$player['tournament_name']}}, {{$player['game_title']}}, \n @endforeach";

    var download = function(content, fileName, mimeType) {
    var a = document.createElement('a');
    mimeType = mimeType || 'application/octet-stream';

    if (navigator.msSaveBlob) { // IE10
    return navigator.msSaveBlob(new Blob([content], { type: mimeType }),     fileName);
    } else if ('download' in a) { //html5 A[download]
    a.href = 'data:' + mimeType + ',' + encodeURIComponent(content);
    a.setAttribute('download', fileName);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    return true;
    } else { //do iframe dataURL download (old ch+FF):
    var f = document.createElement('iframe');
    document.body.appendChild(f);
    f.src = 'data:' + mimeType + ',' + encodeURIComponent(content);

    setTimeout(function() {
    document.body.removeChild(f);
    }, 333);
    return true;
    }
    }
    $("#create_link").click(function(){
        download(csvContent, 'dowload.csv', 'text/csv');
    });
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="/app/content/js/print.js"></script>
@endsection