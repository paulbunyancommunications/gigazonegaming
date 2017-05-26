@extends('LeagueOfLegends/Layout')

@section('TeamName')
 <? echo "Power Rangers"; ?>
@stop
@section('Color')
    <? echo $color; ?>
@stop

<?php for($i = 0; $i < count($team); $i++){ ?>
    @section('Player' . $i . 'Name')
        <? echo "Spartan7Warrior" ?>
    @stop
    @section('Player' . $i . 'Icon')
        <img class="icon" src="https://vignette2.wikia.nocookie.net/leagueoflegends/images/0/00/ProfileIcon0026.png/revision/latest/scale-to-width-down/64?cb=20170517191655"/>
    @stop
    @section('Player' . $i . 'SoloWinLoss')
        <? echo "12|12" ?>
    @stop
    @section('Player' . $i . 'SoloRank')
        <? echo "SILVER III" ?>
    @stop
    @section('Player' . $i . 'FlexWinLoss')
        <? echo "12|12" ?>
    @stop
    @section('Player' . $i . 'FlexRank')
        <? echo "SILVER III" ?>
    @stop
    @section('Player' . $i . 'Champion')
        <?php echo '<div id="divA'. $i .'" class="spinner-container"><div class="spinner-overlay"></div><div class="spinner-image"></div></div>' ; ?>
    @stop
<?php }; ?>