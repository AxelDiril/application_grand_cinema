<form method="GET">
    <!-- Recherche par nom -->
    <input type="text" name="movie_name" placeholder="Nom du film..." value="{{ $strName }}">
    <!-- Filtre par genre -->
    <select name="genre_id">
        <option value="all">Tous les genres</option>
        @foreach($arGenres as $genre)
            <option value="{{ $genre->genre_id }}" 
            {{ $genre->genre_id == $iGenreId ? 'selected' : '' }}>
            {{ $genre->genre_label }}
            </option>
        @endforeach
    </select>

    <input type="submit" value="Actualiser"/>
</form>

@if($arMovies->isEmpty())
    Aucun film à afficher.
@else
    @foreach($arMovies as $movie)
        <a href="/movie/{{$movie->movie_id}}">{{$movie->movie_name}}</a> : {{$movie->duration_minutes}} min
        <br>
    @endforeach
@endif