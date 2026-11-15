
@extends('layouts/structure')

@section('titre')
    Confirmer la réservation
@stop

@section('contenu')

    <h1>Récapitulatif de votre réservation</h1>

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

    <p>
        <strong>Nombre de places :</strong>
        {{ $iSeatsQuantity }}
    </p>

    <p>
        <strong>Prix total :</strong>
        {{ number_format($fTotalPrice, 2, ',', ' ') }} €
    </p>

    <form action="{{ url('/add_reservation/' . $objScreening->screening_id) }}" method="POST">

        @csrf

        <input
            type="hidden"
            name="seats_quantity"
            value="{{ $iSeatsQuantity }}"
        >

        <button type="submit">Confirmer et valider ma réservation</button>

    </form>

@endsection
