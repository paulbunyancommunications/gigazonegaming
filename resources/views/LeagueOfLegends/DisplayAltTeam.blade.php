@extends('LeagueOfLegends/altLayout')

@section('carousel-contents')
    @for($i=0;$i<count($images);$i++)
        <div class="carousel-item{{ $i===0 ? ' active' : ''  }}">
            <img class="waitCarousel img-fluid" src="/{{$images[$i]}}" alt="">
        </div>
    @endfor
@stop