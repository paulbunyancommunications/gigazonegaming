@extends('LeagueOfLegends/altLayout')

@section('carousel-contents')
    <div class="carousel-item active">
        <img class="d-block img-fluid" src="/{{$images[0]}}" alt="" style="margin-right:auto; margin-left: auto;">
    </div>
    @for($i=1;$i<count($images);$i++)
        <div class="carousel-item">
            <img class="d-block img-fluid" src="/{{$images[$i]}}" alt="" style="margin-right:auto; margin-left: auto;">
        </div>

    @endfor
@stop