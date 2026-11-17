
@extends('layouts/structure')

@section('titre')
    Liste des réservations
@stop

@section('contenu')

    <h2>Liste des réservations</h2>

    <form method="GET">
        <!-- Recherche par nom de l'utilisateur -->
        <label for="name">Nom d'utilisateur :</label>
        <input
            type="text"
            id="name"
            name="name"
            placeholder="Nom de l'utilisateur..."
            value="{{ $strName }}"
        >

        <!-- Filtre par identifiant de réservation -->
        <label for="reservation_id">N° de réservation :</label>
        <input
            type="number"
            id="reservation_id"
            name="reservation_id"
            placeholder="N° réservation..."
            value="{{ $iReservationId }}"
        >

        <!-- Filtre par film -->
        <label for="movie_id">Film :</label>
        <select id="movie_id" name="movie_id">
            <option value="all">Tous les films</option>
            @foreach($arMovies as $movie)
                <option value="{{ $movie->movie_id }}"
                    {{ $movie->movie_id == $iMovieId ? 'selected' : '' }}>
                    {{ $movie->movie_name }}
                </option>
            @endforeach
        </select>

        <!-- Filtre par salle -->
        <label for="room_id">Salle :</label>
        <select id="room_id" name="room_id">
            <option value="all">Toutes les salles</option>
            @foreach($arRooms as $room)
                <option value="{{ $room->room_id }}"
                    {{ $room->room_id == $iRoomId ? 'selected' : '' }}>
                    {{ $room->room_name }}
                </option>
            @endforeach
        </select>

        <!-- Filtre par date de séance -->
        <label for="screening_date">Date :</label>
        <input
            id="screening_date"
            type="date"
            name="screening_date"
            value="{{ $strDate }}"
        >

        <input type="submit" value="Actualiser">
    </form>

    @if($arReservations->isEmpty())
        Aucune réservation ou billet valide à afficher.
    @else
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>N° de réservation</th>
                <th>Utilisateur</th>
                <th>Film</th>
                <th>Date</th>
                <th>Salle</th>
                <th>Nombre de places</th>
                <th>Validité</th>
            </tr>
        @foreach($arReservations as $reservation)
            <tr>
                <td>{{ $reservation->reservation_id }}</td>
                <td>{{ $reservation->user->name }}</td>
                <td>{{ $reservation->screening->movie->movie_name }}</td>
                <td>{{ $reservation->screening->screening_date }}</td>
                <td>{{ $reservation->screening->room->room_name }}</td>
                <td>{{ $reservation->seats_quantity }}</td>
                @if(\Carbon\Carbon::parse($reservation->screening->screening_date)->isPast())
                    <td class="text-danger">Séance passée</td>
                @else
                    <td>Valide</td>
                @endif
            </tr>
        @endforeach
        </table>
    @endif

@stop
