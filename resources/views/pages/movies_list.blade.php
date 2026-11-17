@extends('layouts/structure')

@section('titre')
    Liste des films
@stop

@section('contenu')

    <h2>Liste des films</h2>

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
        <table>
            <tr>
                <th>Nom</th>
                <th>Durée</th>
            </tr>
        @foreach($arMovies as $movie)
            <tr>
                <td><a href="/movie/{{$movie->movie_id}}">{{$movie->movie_name}}</a></td>
                <td>{{$movie->duration_minutes}} min</td>
            </tr>
        @endforeach
        </table>
    @endif
@stop