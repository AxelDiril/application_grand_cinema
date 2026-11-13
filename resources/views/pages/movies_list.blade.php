@if($arMovies->isEmpty())

@else
    @foreach($arMovies as $movie)
        {{$movie->movie_name}} : {{$movie->duration_minutes}} min<br>
    @endforeach
@endif