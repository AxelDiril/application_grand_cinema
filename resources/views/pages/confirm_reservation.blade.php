
@extends('layouts/structure')

@section('titre')
    Confirmer la réservation
@stop

@section('contenu')

    <h2>Récapitulatif de votre réservation</h2>

    <p>Note : Cette première version de l'application n'intégrant pas de gestionnaire de paiement,
        la transaction est fictive pour tester les réservations en base de données.
    </p>

    <h2>Détails de la séance</h2>

    <p>
        <strong>Film :</strong>
        {{ $objScreening->movie->movie_name }}
    </p>

    <p>
        <strong>Durée :</strong>
        {{ $objScreening->movie->duration_minutes }} minutes
    </p>

    <p>
        <strong>Salle :</strong>
        {{ $objScreening->room->room_name }}
    </p>

    <p>
        <strong>Date et heure :</strong>
        {{ \Carbon\Carbon::parse($objScreening->screening_date)->format('d/m/Y à H:i') }}
    </p>

    <h2>Détails de la réservation</h2>

    <p>Place choisie : {{ $objSeat->seat_label }}</p>

    <p>Prix total : {{ number_format($fTotalPrice, 2, ',', ' ') }} €</p>

    <form action="{{ url('/add_reservation/' . $objScreening->screening_id) }}" method="POST">

        @csrf

        <input type="hidden" name="seat_id" value="{{ $objSeat->seat_id }}">

        <button type="submit">Confirmer et valider ma réservation</button>

    </form>

@endsection
