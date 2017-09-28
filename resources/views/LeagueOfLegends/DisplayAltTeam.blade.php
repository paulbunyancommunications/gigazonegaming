@extends('LeagueOfLegends/altLayout')

@section('carousel-contents')
    <div class="carousel-item active">
        <img class="waitCarousel img-fluid" src="/{{$images[0]}}" alt="">
    </div>
    @for($i=1;$i<count($images);$i++)
        <div class="carousel-item">
            <img class="waitCarousel img-fluid" src="/{{$images[$i]}}" alt="">
        </div>
    @endfor
@stop