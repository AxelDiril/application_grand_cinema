@extends('layouts/structure')

@section('titre')
    Liste des séances
@stop

@section('contenu')
<h2>Liste des séances</h2>

@auth
  @if ( Auth::user()->role_code == "A" )
  <a href="/add_screening">Ajouter une séance</a>
  @endif
@endif

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
  <select name="room_id">
    <option value="all">Tous les salles</option>
    @foreach($arRooms as $room)
        <option value="{{ $room->room_id }}" 
        {{ $room->room_id == $iRoomId ? 'selected' : '' }}>
        {{ $room->room_name }}
        </option>
    @endforeach
  </select>
  <!-- Filtre par jour -->
  <input type="date" name="screening_date" value="{{ $strDate }}">

  <input type="submit" value="Actualiser"/>
</form>

  @if($arScreenings->isEmpty())
    Aucune séance à afficher.
  @else
    @foreach($arScreenings as $screening)
        <a href="/movie/{{ $screening->movie_id }}">{{ $screening->movie->movie_name }}</a>
        le {{ $screening->screening_date }} ({{ $screening->movie->duration_minutes }} min)
        à {{ $screening->room->room_name }}
        @if ($screening->available_seats <= 0)
            Séance complète
        @else
            Places disponibles : {{ $screening->available_seats }}
        @endif
        @auth
          @if ( Auth::user()->role_code == "A" )
            <a href="/edit_screening/{{ $screening->screening_id }}">Modifier</a>
          @endif
        @endif
        <br>


    @endforeach
  @endif
@stop
