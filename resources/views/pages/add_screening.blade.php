@extends('layouts/structure')

@section('titre')
    Ajouter une séance
@stop

@section('contenu')

    <h2>Ajouter une séance</h2>

    <form method="POST" action="{{ url('insert_screening') }}">
        <!-- Choisir le film -->
        <select name="movie_id" required>
            @foreach($arMovies as $movie)
                <option value="{{ $movie->movie_id }}" >
                {{ $movie->movie_name }}
                </option>
            @endforeach
        </select>

        <!-- Choisir la salle -->
        <select name="room_id" required>
          @foreach($arRooms as $room)
              <option value="{{ $room->room_id }}">
              {{ $room->room_name }} : {{ $room->capacity }} places
              </option>
          @endforeach
        </select>

        <input type="datetime-local" name="screening_date" required>

        <input type="submit" value="Créer"/>
    </form>

@stop