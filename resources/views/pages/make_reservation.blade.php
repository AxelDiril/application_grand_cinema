@extends('layouts/structure')

@section('titre')
    Réserver des places
@stop

@section('contenu')

    <h2>Réserver des places</h2>

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

    <p>
        <strong>Places disponibles :</strong>
        {{ $iAvailableSeats }}
    </p>

    <h2>Choix du nombre de places</h2>

    @if($iAvailableSeats > 0)

        <form action="{{ url('/confirm_reservation/' . $objScreening->screening_id) }}" class="mb-4" method="POST">

            @csrf

            <label for="seat_id">Choisissez votre place :</label>

            <select name="seat_id" id="seat_id" required>
                <option value="">-- Sélectionnez une place --</option>

                @foreach ($arAvailableSeats as $seat)
                    <option value="{{ $seat->seat_id }}">
                        Place {{ $seat->seat_label }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit">Continuer</button>

        </form>

    @else

        <p>Il ne reste plus de places disponibles pour cette séance.</p>

    @endif

@stop
