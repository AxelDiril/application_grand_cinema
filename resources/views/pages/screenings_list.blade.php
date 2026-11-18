@extends('layouts/structure')

@section('titre')
    Liste des séances
@stop

@section('contenu')
<h2>Liste des séances</h2>

@auth
  @if ( Auth::user()->role_code == "A" )
    <a class="btn btn-primary" href="/add_screening">Ajouter une séance</a>
  @endif
@endif

  <form method="GET">
    <!-- Recherche par nom -->
    <label for="movie_name">Nom du film :</label>
    <input id="movie_name" type="text" name="movie_name" placeholder="Nom du film..." value="{{ $strName }}">
    <!-- Filtre par genre -->
    <label for="genre_id">Genre :</label>
    <select id="genre_id" name="genre_id">
          <option value="all">Tous les genres</option>
          @foreach($arGenres as $genre)
              <option value="{{ $genre->genre_id }}"
              {{ $genre->genre_id == $iGenreId ? 'selected' : '' }}>
              {{ $genre->genre_label }}
              </option>
          @endforeach
    </select>
    <label for="room_id">Salle :</label>
    <select id="room_id" name="room_id">
      <option value="all">Tous les salles</option>
      @foreach($arRooms as $room)
          <option value="{{ $room->room_id }}"
          {{ $room->room_id == $iRoomId ? 'selected' : '' }}>
          {{ $room->room_name }}
          </option>
      @endforeach
    </select>
    <!-- Filtre par jour -->
    <label for="screening_id">Date :</label>
    <input id="screening_id" type="date" name="screening_date" value="{{ $strDate }}">

    <input type="submit" value="Actualiser">
  </form>

    @if($arScreenings->isEmpty())
      Aucune séance à afficher.
    @else
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th>Film</th>
          <th>Date</th>
          <th>Durée</th>
          <th>Salle</th>
          <th>Places disponibles</th>
          <th>Actions</th>
        </tr>
      @foreach($arScreenings as $screening)
        <tr>
          <td><a href="/movie/{{ $screening->movie_id }}">{{ $screening->movie->movie_name }}</a></td>
          <td>{{ $screening->screening_date }}</td>
          <td>{{ $screening->movie->duration_minutes }} min</td>
          <td>{{ $screening->room->room_name }}</td>
          @if ($screening->available_seats <= 0)
            <td class="text-danger">Séance complète</td>
          @else
            <td>{{ $screening->available_seats }}</td>
          @endif
            <td><a class="btn btn-primary" href="/make_reservation/{{ $screening->screening_id }}">Réserver</a>
          @auth
            @if ( Auth::user()->role_code == "A" )
              <a class="btn btn-secondary" href="/edit_screening/{{ $screening->screening_id }}">Modifier</a>
              <a class="btn btn-danger" href="/delete_screening/{{ $screening->screening_id }}">Supprimer</a>
            @endif
          @endif
          </td>
        </tr>
      @endforeach
      </table>
    @endif
@stop
