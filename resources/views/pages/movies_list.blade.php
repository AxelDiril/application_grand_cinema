@if($arMovies->isEmpty())
    Aucun film à afficher.
@else
    @foreach($arMovies as $movie)
        <a href="/movie/{{$movie->movie_id}}">{{$movie->movie_name}}</a> : {{$movie->duration_minutes}} min
        <br>
    @endforeach
@endif