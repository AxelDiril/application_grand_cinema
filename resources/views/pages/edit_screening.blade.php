@extends('layouts/structure')

@section('titre')
    Modifier une séance
@stop

@section('contenu')

    <h2>Modifier une séance</h2>

    <form method="POST" action="{{ url('update_screening/' . $screening_id) }}">

        <!-- Choisir le film -->
        <label for="movie_id">Film :</label>
        <select id="movie_id" name="movie_id">
            @foreach($arMovies as $movie)
                <option value="{{ $movie->movie_id }}"
                    {{ $movie->movie_id == $iMovieId ? 'selected' : '' }}>
                    {{ $movie->movie_name }}
                </option>
            @endforeach
        </select><br>

        <!-- Choisir la salle -->
        <label for="room_id">Salle :</label>
        <select id="room_id" name="room_id">
            @foreach($arRooms as $room)
                <option value="{{ $room->room_id }}"
                    {{ $room->room_id == $iRoomId ? 'selected' : '' }}>
                    {{ $room->room_name }} : {{ $room->capacity }} places
                </option>
            @endforeach
        </select><br>

        <label for="screening_date">Date :</label>
        <input
            id="screening_date"
            type="datetime-local"
            name="screening_date"
            value="{{ $strScreeningDate }}"
            required
        ><br>

        <input type="submit" value="Modifier">
    </form>

@stop