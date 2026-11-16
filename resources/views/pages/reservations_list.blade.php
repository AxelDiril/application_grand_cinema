
@extends('layouts/structure')

@section('titre')
    Liste des réservations
@stop

@section('contenu')

    <h2>Liste des réservations</h2>

    <form method="GET">
        <!-- Recherche par nom de l'utilisateur -->
        <input
            type="text"
            name="name"
            placeholder="Nom de l'utilisateur..."
            value="{{ $strName }}"
        >

        <!-- Filtre par identifiant de réservation -->
        <input
            type="number"
            name="reservation_id"
            placeholder="N° réservation..."
            value="{{ $iReservationId }}"
        >

        <!-- Filtre par film -->
        <select name="movie_id">
            <option value="all">Tous les films</option>
            @foreach($arMovies as $movie)
                <option value="{{ $movie->movie_id }}"
                    {{ $movie->movie_id == $iMovieId ? 'selected' : '' }}>
                    {{ $movie->movie_name }}
                </option>
            @endforeach
        </select>

        <!-- Filtre par salle -->
        <select name="room_id">
            <option value="all">Toutes les salles</option>
            @foreach($arRooms as $room)
                <option value="{{ $room->room_id }}"
                    {{ $room->room_id == $iRoomId ? 'selected' : '' }}>
                    {{ $room->room_name }}
                </option>
            @endforeach
        </select>

        <!-- Filtre par date de séance -->
        <input
            type="date"
            name="screening_date"
            value="{{ $strDate }}"
        >

        <input type="submit" value="Actualiser"/>
    </form>

    @if($arReservations->isEmpty())
        Aucune réservation ou billet valide à afficher.
    @else
        @foreach($arReservations as $reservation)

            ID n°{{ $reservation->reservation_id }}

            - Utilisateur : {{ $reservation->user->name }}

            - Film : {{ $reservation->screening->movie->movie_name }}

            - Date de séance : {{ $reservation->screening->screening_date }}

            - Salle : {{ $reservation->screening->room->room_name }}

            - Nombre de places : {{ $reservation->seats_quantity }}

            @if(\Carbon\Carbon::parse($reservation->screening->screening_date)->isPast())
                - Statut : Séance passée
            @else
                - Statut : Billet valide
            @endif

            <br>

        @endforeach
    @endif

@stop
