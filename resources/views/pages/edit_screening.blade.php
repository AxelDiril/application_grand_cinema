@extends('layouts/structure')

@section('titre')
    Modifier une séance
@stop

@section('contenu')

    <h2>Modifier une séance</h2>

    <form method="POST" action="{{ url('update_screening/' . $screening_id) }}">
        @csrf

        <!-- Choisir le film -->
        <select name="movie_id" required>
            @foreach($arMovies as $movie)
                <option value="{{ $movie->movie_id }}"
                    {{ $movie->movie_id == $iMovieId ? 'selected' : '' }}>
                    {{ $movie->movie_name }}
                </option>
            @endforeach
        </select>

        <!-- Choisir la salle -->
        <select name="room_id" required>
            @foreach($arRooms as $room)
                <option value="{{ $room->room_id }}"
                    {{ $room->room_id == $iRoomId ? 'selected' : '' }}>
                    {{ $room->room_name }} : {{ $room->capacity }} places
                </option>
            @endforeach
        </select>

        <input
            type="datetime-local"
            name="screening_date"
            value="{{ $strScreeningDate }}"
            required
        >

        <input type="submit" value="Modifier"/>
    </form>

@stop