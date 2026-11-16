@extends('layouts.structure')

@section('titre')
    Mes billets
@endsection

@section('contenu')

    <h1>Mes réservations</h1>

    @if($arReservations->isEmpty())

        <p>Vous n'avez pas encore effectué de réservation.</p>

    @else

        @foreach($arReservations as $reservation)

            <div>
                <h2>Réservation n°{{ $reservation->reservation_id }}</h2>

                <p>
                    <strong>Film :</strong>
                    {{ $reservation->screening->movie->movie_name }}
                </p>

                <p>
                    <strong>Date de la séance :</strong>
                    {{ \Carbon\Carbon::parse($reservation->screening->screening_date)->format('d/m/Y à H:i') }}
                </p>

                <p>
                    <strong>Salle :</strong>
                    {{ $reservation->screening->room->room_name }}
                </p>

                <p>
                    <strong>Nombre de places :</strong>
                    {{ $reservation->seats_quantity }}
                </p>

                <p>
                    <strong>Prix total :</strong>
                    {{ number_format($reservation->seats_quantity * 10, 2, ',', ' ') }} €
                </p>

                @if(\Carbon\Carbon::parse($reservation->screening->screening_date)->isPast())
                - Statut : Séance passée
                @else
                    - Statut : Billet valide
                @endif

                <a href="{{ url('/download_ticket/' . $reservation->reservation_id) }}">
                    Télécharger mon billet
                </a>
            </div>

            <hr>

        @endforeach

    @endif

@endsection